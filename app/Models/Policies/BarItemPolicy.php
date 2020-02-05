<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\BarItem;
use Illuminate\Database\Eloquent\Builder;

class BarItemPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create BarItem.
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
     * Determine whether the user can view the BarItem.
     *
     * @param User $user
     * @param BarItem $barItem
     * @return mixed
     */
    public function view(User $user, BarItem $barItem)
    {
        return $this->own($user, $barItem);
    }

    /**
     * Determine whether the user can view the collection of BarItem.
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
     * Determine whether the user can update the BarItem.
     *
     * @param User $user
     * @param BarItem $barItem
     * @return mixed
     */
    public function update(User $user, BarItem $barItem)
    {
        return $this->own($user, $barItem);
    }

    /**
     * Determine whether the user can delete the BarItem.
     *
     * @param User $user
     * @param BarItem $barItem
     * @return mixed
     */
    // phpcs:ignore PHPCS_SecurityAudit.BadFunctions.FilesystemFunctions
    public function delete(User $user, BarItem $barItem)
    {
        return $this->own($user, $barItem);
    }

    /**
     * Determine whether the user owns the BarItem.
     *
     * @param User $user
     * @param BarItem $barItem
     * @return mixed
     */
    public function own(User $user, BarItem $barItem)
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
