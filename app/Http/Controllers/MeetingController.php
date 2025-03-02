<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeetingRequest;
use App\Models\User;
use App\Models\ZoomMeeting;
use App\Queries\MeetingDataTable;
use App\Repositories\MeetingRepository;
use Auth;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laracasts\Flash\Flash;

/**
 * Class MeetingController
 */
class MeetingController extends AppBaseController
{
    /**
     * @var MeetingRepository
     */
    private $meetingRepository;

    /**
     * MeetingController constructor.
     *
     * @param  MeetingRepository  $meetingRepository
     */
    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if (getLoggedInUser()->hasRole('Member')) {
            return redirect()->back();
        }

        if ($request->ajax()) {
            return Datatables::of((new MeetingDataTable())->get())->make(true);
        }

        return view('meetings.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $timeZones = getTimeZone();
        $users = User::where('id', '!=', Auth::id())->pluck('name', 'id');

        return view('meetings.create', compact('users', 'timeZones'));
    }

    /**
     * @param  int  $meeting
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function edit($meeting)
    {
        $timeZones = getTimeZone();
        $meeting = ZoomMeeting::with('members')->findOrFail($meeting);
        if ($meeting->status == ZoomMeeting::STATUS_FINISHED) {
            Flash::error('Sorry, Can not update finished meeting.');

            return redirect()->route('meetings.index');
        }

        $meetingTimeZone = array_flip($timeZones)[$meeting->time_zone];
        $members = $meeting->members->pluck('id')->toArray();
        $users = User::where('id', '!=', Auth::id())->pluck('name', 'id');

        return view('meetings.edit', compact('users', 'meeting', 'timeZones', 'members', 'meetingTimeZone'));
    }

    /**
     * @param  CreateMeetingRequest  $request
     * @return RedirectResponse
     */
    public function store(CreateMeetingRequest $request): RedirectResponse
    {
        $this->meetingRepository->store($request->all());
        Flash::success('Meeting saved successfully.');

        return redirect()->route('meetings.index');
    }

    /**
     * @param $meeting
     * @param  CreateMeetingRequest  $request
     * @return RedirectResponse
     */
    public function update($meeting, CreateMeetingRequest $request): RedirectResponse
    {
        $this->meetingRepository->updateZoomMeeting($meeting, $request->all());
        Flash::success('Meeting updated successfully.');

        return redirect()->route('meetings.index');
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     *
     * @throws Exception
     */
    public function showMemberMeetings(Request $request)
    {
        if (! getLoggedInUser()->hasRole('Member')) {
            return redirect()->back();
        }

        if ($request->ajax()) {
            return Datatables::of((new MeetingDataTable())->get($member = true))->make(true);
        }

        return view('meetings.members_index');
    }

    /**
     * @param  ZoomMeeting  $meeting
     * @return JsonResponse
     */
    public function destroy(ZoomMeeting $meeting): JsonResponse
    {
        $this->meetingRepository->deleteMeeting($meeting->id);

        return $this->sendSuccess('Meeting deleted successfully.');
    }

    /**
     * @param  ZoomMeeting  $meeting
     * @param $status
     * @return JsonResponse
     */
    public function changeMeetingStatus(ZoomMeeting $meeting, $status): JsonResponse
    {
        $this->meetingRepository->changeMeetingStatus($meeting->id, $status);

        return $this->sendSuccess('Meeting status updated successfully.');
    }
}
