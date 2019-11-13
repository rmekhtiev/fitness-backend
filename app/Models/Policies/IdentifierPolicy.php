<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Identifier;

class IdentifierPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Identifier.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can view the Identifier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Identifier  $identifier
     * @return mixed
     */
    public function view(User $user, Identifier $identifier)
    {
        return true;
    }

    /**
     * Determine whether the user can view the collection of Identifier.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can update the Identifier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Identifier  $identifier
     * @return mixed
     */
    public function update(User $user, Identifier $identifier)
    {
        return $this->own($user, $identifier);
    }

    /**
     * Determine whether the user can delete the Identifier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Identifier  $identifier
     * @return mixed
     */
    public function delete(User $user, Identifier $identifier)
    {
        return $this->own($user, $identifier);
    }

    /**
     * Determine whether the user owns the Identifier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Identifier  $identifier
     * @return mixed
     */
    public function own(User $user, Identifier $identifier) {
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
