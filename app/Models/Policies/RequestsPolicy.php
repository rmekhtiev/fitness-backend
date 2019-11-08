<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Requests;

class RequestsPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Requests.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can view the Requests.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requests  $requests
     * @return mixed
     */
    public function view(User $user, Requests $requests)
    {
        return $this->own($user, $requests);
    }

    /**
     * Determine whether the user can view the collection of Requests.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can update the Requests.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requests  $requests
     * @return mixed
     */
    public function update(User $user, Requests $requests)
    {
        return $this->own($user, $requests);
    }

    /**
     * Determine whether the user can delete the Requests.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requests  $requests
     * @return mixed
     */
    public function delete(User $user, Requests $requests)
    {
        return $this->own($user, $requests);
    }

    /**
     * Determine whether the user owns the Requests.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requests  $requests
     * @return mixed
     */
    public function own(User $user, Requests $requests) {
        return $user->isOwner() || $user->isHallAdmin();
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
