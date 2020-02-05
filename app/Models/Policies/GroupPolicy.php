<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;

class GroupPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Group.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //@todo
        return true;
    }

    /**
     * Determine whether the user can view the Group.
     *
     * @param User $user
     * @param Group $group
     * @return mixed
     */
    public function view(User $user, Group $group)
    {
        return $this->own($user, $group);
    }

    /**
     * Determine whether the user can view the collection of Group.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        // @todo
        return true;
    }

    /**
     * Determine whether the user can update the Group.
     *
     * @param User $user
     * @param Group $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return $this->own($user, $group);
    }

    /**
     * Determine whether the user can delete the Group.
     *
     * @param User $user
     * @param Group $group
     * @return mixed
     */
    // phpcs:ignore PHPCS_SecurityAudit.BadFunctions.FilesystemFunctions
    public function delete(User $user, Group $group)
    {
        return $this->own($user, $group);
    }

    /**
     * Determine whether the user owns the Group.
     *
     * @param User $user
     * @param Group $group
     * @return mixed
     */
    public function own(User $user, Group $group)
    {
        // @todo
        return true;
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
        return $query;
    }
}
