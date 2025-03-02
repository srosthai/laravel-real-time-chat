<?php

namespace App\Http\Controllers\API;

use App\Events\UserEvent;
use App\Exceptions\ApiOperationFailedException;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\SendMessageRequest;
use App\Models\ChatRequestModel;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ChatRepository;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ChatAPIController
 */
class ChatAPIController extends AppBaseController
{
    /** @var ChatRepository */
    private $chatRepository;

    /**
     * Create a new controller instance.
     *
     * @param  ChatRepository  $chatRepository
     */
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * This function return latest conversations of users.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getLatestConversations(Request $request)
    {
        $input = $request->all();
        $conversations = $this->chatRepository->getLatestConversations($input);

        return $this->sendResponse(['conversations' => $conversations], 'Conversations retrieved successfully.');
    }

    /**
     * This function return latest conversations of users.
     *
     * @return JsonResponse
     */
    public function getArchiveConversations(Request $request)
    {
        $input = $request->all();
        $input['isArchived'] = 1;
        $conversations = $this->chatRepository->getLatestConversations($input);

        return $this->sendResponse(['conversations' => $conversations], 'Conversations retrieved successfully.');
    }

    /**
     * @param  SendMessageRequest  $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function sendMessage(SendMessageRequest $request)
    {
        $conversation = $this->chatRepository->sendMessage($request->all());

        return $this->sendResponse(['message' => $conversation], 'Message sent successfully.');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updateConversationStatus(Request $request)
    {
        $data = $this->chatRepository->markMessagesAsRead($request->all());

        return $this->sendResponse($data, 'Status updated successfully.');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     *
     * @throws ApiOperationFailedException
     */
    public function addAttachment(Request $request)
    {
        $files = $request->file('file');

        foreach ($files as $file) {
            $fileData['attachment'] = $this->chatRepository->addAttachment($file);
            $extension = $file->getClientOriginalExtension();
            $fileData['message_type'] = $this->chatRepository->getMessageTypeByExtension($extension);
            $fileData['file_name'] = $file->getClientOriginalName();
            $fileData['unique_code'] = uniqid();
            $data['data'][] = $fileData;
        }
        $data['success'] = true;

        return $this->sendData($data);
    }

    /**
     * @param  int|string  $id
     * @return JsonResponse
     */
    public function deleteConversation($id)
    {
        if (is_string($id) && ! is_numeric($id)) {
            $this->chatRepository->deleteGroupConversation($id);
        } else {
            $this->chatRepository->deleteConversation($id);
        }

        return $this->sendSuccess('Conversation deleted successfully.');
    }

    /**
     * @param  Conversation  $conversation
     * @param  Request  $request
     * @return JsonResponse
     */
    public function deleteMessage(Conversation $conversation, Request $request)
    {
        $deleteMessageTime = config('configurable.delete_message_time');
        if ($conversation->time_from_now_in_min > $deleteMessageTime) {
            return $this->sendError('You can not delete message older than '.$deleteMessageTime.' minutes.', 422);
        }

        if ($conversation->from_id != getLoggedInUserId()) {
            return $this->sendError('You can not delete this message.', 403);
        }

        $previousMessageId = $request->get('previousMessageId');
        $previousMessage = $this->chatRepository->find($previousMessageId);
        $this->chatRepository->deleteMessage($conversation->id);

        return $this->sendResponse(['previousMessage' => $previousMessage], 'Message deleted successfully.');
    }

    /**
     * @param  Conversation  $conversation
     * @return JsonResponse
     */
    public function show(Conversation $conversation)
    {
        return $this->sendResponse($conversation->toArray(), 'Conversation retrieved successfully');
    }

    /**
     * @param  Conversation  $conversation
     * @param  Request  $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function deleteMessageForEveryone(Conversation $conversation, Request $request)
    {
        $deleteMessageTime = config('configurable.delete_message_for_everyone_time');
        if ($conversation->time_from_now_in_min > $deleteMessageTime) {
            return $this->sendError('You can not delete message older than '.$deleteMessageTime.' minutes.', 422);
        }

        if ($conversation->from_id != getLoggedInUserId()) {
            return $this->sendError('You can not delete this message.', 403);
        }

        $conversation->delete();

        $previousMessageId = $request->get('previousMessageId');
        $previousMessage = $this->chatRepository->find($previousMessageId);
        unset($previousMessage->replayMessage);

        broadcast(new UserEvent(
            [
                'id' => $conversation->id,
                'type' => User::MESSAGE_DELETED,
                'from_id' => $conversation->from_id,
                'previousMessage' => $previousMessage,
            ], $conversation->to_id))->toOthers();

        return $this->sendResponse(['previousMessage' => $previousMessage], 'Message deleted successfully.');
    }

    /**
     * @param  SendMessageRequest  $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function sendChatRequest(SendMessageRequest $request)
    {
        $isRequestSend = $this->chatRepository->sendChatRequest($request->all());
        if ($isRequestSend) {
            return $this->sendSuccess('Chat request send successfully.');
        }

        return $this->sendError('Chat request has already been sent.');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function acceptChatRequest(Request $request)
    {
        $chatRequestModel = ChatRequestModel::whereId($request->id)->first();
        $chatRequestModel->status = ChatRequestModel::STATUS_ACCEPTED;
        $chatRequestModel->save();

        $input = $chatRequestModel->toArray();
        $input['message'] = $chatRequestModel->receiver->name.' has accepted your chat request.';
        $this->chatRepository->sendAcceptDeclineChatRequestNotification($input, User::CHAT_REQUEST_ACCEPTED);

        return $this->sendResponse($chatRequestModel, 'Chat request accepted successfully.');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function declineChatRequest(Request $request)
    {
        $chatRequestModel = ChatRequestModel::find($request->id);
        $chatRequestModel->status = ChatRequestModel::STATUS_DECLINE;
        $chatRequestModel->save();

        Conversation::whereFromId($chatRequestModel->from_id)->whereToId($chatRequestModel->owner_id)->update(['status' => 1]);

        $input = $chatRequestModel->toArray();
        $input['message'] = $chatRequestModel->receiver->name.' has declined your chat request.';
        $this->chatRepository->sendAcceptDeclineChatRequestNotification($input);

        return $this->sendResponse($chatRequestModel, 'You have declined given user request !');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     *
     * @throws ApiOperationFailedException
     */
    public function imageUpload(Request $request)
    {
        $input = $request->all();
        $images = $input['images'];
        unset($input['images']);
        $input['from_id'] = Auth::id();
        $input['to_type'] = Conversation::class;
        $conversation = [];
        foreach ($images as $image) {
            $fileName = Conversation::uploadBase64Image($image, Conversation::PATH);
            $input['message'] = $fileName;
            $input['status'] = 0;
            $input['message_type'] = 1;
            $input['file_name'] = $fileName;
            $conversation[] = $this->chatRepository->sendMessage($input);
        }

        return $this->sendResponse($conversation, 'File uploaded');
    }
}
