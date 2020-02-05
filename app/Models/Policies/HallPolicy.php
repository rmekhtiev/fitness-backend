<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Hall;
use Illuminate\Database\Eloquent\Builder;

class HallPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Hall.
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
     * Determine whether the user can view the Hall.
     *
     * @param User $user
     * @param Hall $hall
     * @return mixed
     */
    public function view(User $user, Hall $hall)
    {
        return $this->own($user, $hall);
    }

    /**
     * Determine whether the user can view the collection of Hall.
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
     * Determine whether the user can update the Hall.
     *
     * @param User $user
     * @param Hall $hall
     * @return mixed
     */
    public function update(User $user, Hall $hall)
    {
        return $this->own($user, $hall);
    }

    /**
     * Determine whether the user can delete the Hall.
     *
     * @param User $user
     * @param Hall $hall
     * @return mixed
     */
    public function delete(User $user, Hall $hall) // phpcs:ignore PHPCS_SecurityAudit.BadFunctions.FilesystemFunctions
    {
        return $this->own($user, $hall);
    }

    /**
     * Determine whether the user owns the Hall.
     *
     * @param User $user
     * @param Hall $hall
     * @return mixed
     */
    public function own(User $user, Hall $hall)
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
