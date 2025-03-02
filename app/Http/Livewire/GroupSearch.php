<?php

namespace App\Http\Livewire;

use App\Models\Group;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\View\View;
use Livewire\Component;

class GroupSearch extends Component
{
    public $groups = [];

    public $searchTerm;

    public $groupsCount;

    protected $listeners = [
        'clearSearchGroup' => 'clearSearchGroup',
        'newGroupCreated' => 'newGroupCreated',
        'searchGroup' => 'searchGroup',
    ];

    public function mount()
    {
        $this->getGroupsCount();
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $this->searchGroup();

        return view('livewire.group-search');
    }

    public function clearSearchGroup()
    {
        $this->searchTerm = '';

        $this->searchGroup();
    }

    /**
     * search users and apply filters
     */
    public function searchGroup()
    {
        $groups = $this->getGroupQuery()
            ->when($this->searchTerm, function ($query) {
                return $query->whereRaw('name LIKE ?', ['%'.strtolower($this->searchTerm).'%']);
            })
            ->select(['id', 'photo_url', 'name'])
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get();

        $this->groups = $groups;
    }

    public function newGroupCreated()
    {
        if ($this->groupsCount == 0) {
            $this->getGroupsCount();
        }
    }

    public function getGroupsCount()
    {
        $this->groupsCount = $this->getGroupQuery()->count();
    }

    /**
     * @return Builder
     */
    public function getGroupQuery()
    {
        return Group::with([
            'users' => function (BelongsToMany $query) {
                $query->toBase()->select('id');
            }, 'usersWithTrashed',
        ])->whereHas('users', function (Builder $query) {
            $query->where('user_id', getLoggedInUserId());
        });
    }
}
