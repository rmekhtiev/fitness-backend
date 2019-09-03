<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Issue;

class IssuePolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Issue.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isHallAdmin();
    }

    /**
     * Determine whether the user can view the Issue.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Issue  $issue
     * @return mixed
     */
    public function view(User $user, Issue $issue)
    {
        return $this->own($user, $issue) || $user->isOwner();
    }

    /**
     * Determine whether the user can view the collection of Issue.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isHallAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the Issue.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Issue  $issue
     * @return mixed
     */
    public function update(User $user, Issue $issue)
    {
        return $this->own($user, $issue);
    }

    /**
     * Determine whether the user can delete the Issue.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Issue  $issue
     * @return mixed
     */
    public function delete(User $user, Issue $issue)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user owns the Issue.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Issue  $issue
     * @return mixed
     */
    public function own(User $user, Issue $issue) {
        //todo
        return true;
    }

    /**
     * This function can be used to add conditions to the query builder,
     * which will specify the user's ownership of the model for the get collection query of this model
     *
     * @param \App\Models\User $user A user object against which to construct the query. By default, the currently logged in user is used.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function qualifyCollectionQueryWithUser(User $user, $query)
    {
        return $query;
    }
}
