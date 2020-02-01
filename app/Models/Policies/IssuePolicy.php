<?php

namespace App\Models\Policies;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class IssuePolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Issue.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isHallAdmin();
    }

    /**
     * Determine whether the user can view the Issue.
     *
     * @param User $user
     * @param Issue $issue
     * @return mixed
     */
    public function view(User $user, Issue $issue)
    {
        return $this->own($user, $issue) || $user->isOwner();
    }

    /**
     * Determine whether the user can view the collection of Issue.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isHallAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the Issue.
     *
     * @param User $user
     * @param Issue $issue
     * @return mixed
     */
    public function update(User $user, Issue $issue)
    {
        return $this->own($user, $issue);
    }

    /**
     * Determine whether the user can delete the Issue.
     *
     * @param User $user
     * @param Issue $issue
     * @return mixed
     */
    public function delete(User $user, Issue $issue)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user owns the Issue.
     *
     * @param User $user
     * @param Issue $issue
     * @return mixed
     */
    public function own(User $user, Issue $issue)
    {
        return $user->isHallAdmin()
            && !empty($user->associatedEmployee)
            && $user->associatedEmployee->hall_id == $issue->hall_id;
    }

    /**
     * This function can be used to add conditions to the query builder,
     * which will specify the user's ownership of the model for the get collection query of this model
     *
     * @param User $user A user object against which to construct the query.
     *                   By default, the currently logged in user is used.
     * @param Builder $query
     * @return Builder|null
     */
    public function qualifyCollectionQueryWithUser(User $user, $query)
    {
        return $query->when(
            $user->isHallAdmin() && !empty($user->associatedEmployee),
            function (Builder $query) use ($user) {
                return $query->where('hall_id', $user->associatedEmployee->hall_id);
            }
        );
    }
}
