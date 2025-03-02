<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;

class SearchGroupMembersForCreateGroup extends Component
{
    public $searchTerm;

    public $contacts;

    public $members;

    protected $listeners = [
        'resetGroupMemberSearch' => 'resetSearch',
        'searchMembers' => 'searchMembers',
    ];

    public function mount()
    {
        $this->resetSearch();
    }

    /**
     * reset search
     */
    public function resetSearch()
    {
        $this->searchTerm = '';
        $this->contacts = [];
        $this->members = [];
        $this->searchMembers();
    }

    /**
     * search contacts
     *
     * @param  string  $searchTerm
     * @param  array  $members
     */
    public function searchMembers($searchTerm = '', $members = [])
    {
        $this->searchTerm = $searchTerm;
        $this->members = array_merge($members, [getLoggedInUserId()]);

        $users = User::limit(20)
            ->when($this->searchTerm, function ($query) {
                $query->where(function (Builder $query) {
                    return $query->whereRaw('name LIKE ?', ['%'.strtolower($this->searchTerm).'%'])
                        ->orWhereRaw('email LIKE ?', ['%'.strtolower($this->searchTerm).'%']);
                });
            })
            ->whereNotIn('id', $this->members)
            ->select(['id', 'name', 'photo_url', 'email'])
            ->orderBy('name')
            ->get()
            ->toArray();

        $users = array_map(function ($object) {
            return (array) $object;
        }, $users);
        $this->contacts = $users;
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('livewire.search-group-members-for-create-group');
    }
}
