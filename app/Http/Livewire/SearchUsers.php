<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

class SearchUsers extends Component
{
    public $users = [];

    public $myContactIds = [];

    public $searchTerm;

    public $male;

    public $female;

    protected $listeners = ['clearSearchUsers' => 'clearSearchUsers'];

    /**
     * @param  array  $ids
     */
    public function setMyContactIds($ids)
    {
        $this->myContactIds = $ids;
    }

    /**
     * @return array
     */
    public function getMyContactIds()
    {
        return $this->myContactIds;
    }

    /**
     * initialize variables
     *
     * @param $myContactIds
     * @param $blockUserIds
     */
    public function mount($myContactIds, $blockUserIds)
    {
        $userIds = array_unique(array_merge($blockUserIds, array_keys($blockUserIds)));
        $userIds = array_unique(array_merge($userIds, $myContactIds));
        $this->setMyContactIds($userIds);
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $this->searchUsers();

        return view('livewire.search-users');
    }

    public function clearSearchUsers()
    {
        $this->male = false;
        $this->female = false;
        $this->searchTerm = '';

        $this->searchUsers();
    }

    /**
     * search users and apply filters
     */
    public function searchUsers()
    {
        $male = $this->male;
        $female = $this->female;
        if ($this->male && $this->female) {
            $male = false;
            $female = false;
        }
        $users = User::whereNotIn('id', $this->getMyContactIds())
            ->when($male, function ($query) {
                return $query->where('gender', '=', User::MALE);
            })
            ->when($female, function ($query) {
                return $query->where('gender', '=', User::FEMALE);
            })
            ->when($this->searchTerm, function ($query) {
                return $query->where(function ($q) {
                    $q->whereRaw('name LIKE ?', ['%'.strtolower($this->searchTerm).'%'])
                    ->orWhereRaw('email LIKE ?', ['%'.strtolower($this->searchTerm).'%']);
                });
            })
            ->orderBy('name', 'asc')
            ->select(['id', 'is_online', 'gender', 'photo_url', 'name', 'email'])
            ->limit(20)
            ->get()
            ->except(getLoggedInUserId());

        $this->users = $users;
    }
}
