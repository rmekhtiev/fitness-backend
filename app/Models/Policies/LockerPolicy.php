<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Locker;

class LockerPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Locker.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //@todo
        return true;
    }

    /**
     * Determine whether the user can view the Locker.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Locker  $locker
     * @return mixed
     */
    public function view(User $user, Locker $locker)
    {
        return $this->own($user, $locker);
    }

    /**
     * Determine whether the user can view the collection of Locker.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        // @todo
        return true;
    }

    /**
     * Determine whether the user can update the Locker.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Locker  $locker
     * @return mixed
     */
    public function update(User $user, Locker $locker)
    {
        return $this->own($user, $locker);
    }

    /**
     * Determine whether the user can delete the Locker.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Locker  $locker
     * @return mixed
     */
    public function delete(User $user, Locker $locker)
    {
        return $this->own($user, $locker);
    }

    /**
     * Determine whether the user owns the Locker.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Locker  $locker
     * @return mixed
     */
    public function own(User $user, Locker $locker) {
        // @todo
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
