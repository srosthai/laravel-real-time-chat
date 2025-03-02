<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\View\View;
use Livewire\Component;

class BlockedUserSearch extends Component
{
    public $users = [];

    public $blockedContactIds = [];

    public $searchTerm;

    public $blockedUsersCount;

    protected $listeners = [
        'clearSearchOfBlockedUsers' => 'clearSearchUsers',
        'addBlockedUserId' => 'addBlockedUserId',
        'removeBlockedUserId' => 'removeBlockedUserId',
    ];

    /**
     * @param  array  $ids
     */
    public function setBlockedContactIds($ids)
    {
        $this->blockedContactIds = $ids;
    }

    /**
     * @return array
     */
    public function getBlockedContactIds()
    {
        return $this->blockedContactIds;
    }

    /**
     * initialize variables
     *
     * @param $blockedByMeUserIds
     */
    public function mount($blockedByMeUserIds)
    {
        $this->setBlockedContactIds($blockedByMeUserIds);
        $this->getBlockedUsersCount();
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $this->searchUsers();

        return view('livewire.blocked-user-search');
    }

    /**
     * clear search
     */
    public function clearSearchUsers()
    {
        $this->searchTerm = '';

        $this->searchUsers();
    }

    /**
     * search users and apply filters
     */
    public function searchUsers()
    {
        $users = $this->getBlockUsersQuery()
            ->when($this->searchTerm, function ($query) {
                return $query->where(function ($q) {
                    $q->whereRaw('name LIKE ?', ['%'.strtolower($this->searchTerm).'%'])
                    ->orWhereRaw('email LIKE ?', ['%'.strtolower($this->searchTerm).'%']);
                });
            })
            ->orderBy('name', 'asc')
            ->select(['id', 'is_online', 'gender', 'photo_url', 'name', 'email'])
            ->limit(20)
            ->get();

        $this->users = $users;
    }

    /**
     * @param  int  $userId
     */
    public function addBlockedUserId($userId)
    {
        $blockedUsersIds = $this->getBlockedContactIds();
        array_push($blockedUsersIds, $userId);
        $this->setBlockedContactIds($blockedUsersIds);
        $this->getBlockedUsersCount();
    }

    /**
     * @param  int  $userId
     */
    public function removeBlockedUserId($userId)
    {
        $blockedContactIds = $this->getBlockedContactIds();
        if (($key = array_search($userId, $blockedContactIds)) !== false) {
            unset($blockedContactIds[$key]);
        }
        $this->setBlockedContactIds($blockedContactIds);
        $this->getBlockedUsersCount();
    }

    public function getBlockedUsersCount()
    {
        $this->blockedUsersCount = $this->getBlockUsersQuery()->count();
    }

    /**
     * @return Builder
     */
    public function getBlockUsersQuery()
    {
        return User::whereIn('id', $this->getBlockedContactIds());
    }
}
