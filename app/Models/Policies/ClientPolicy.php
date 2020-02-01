<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;

class ClientPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Client.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can view the Client.
     *
     * @param User $user
     * @param Client $client
     * @return mixed
     */
    public function view(User $user, Client $client)
    {
        return true;
    }

    /**
     * Determine whether the user can view the collection of Client.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can update the Client.
     *
     * @param User $user
     * @param Client $client
     * @return mixed
     */
    public function update(User $user, Client $client)
    {
        return $this->own($user, $client);
    }

    /**
     * Determine whether the user can delete the Client.
     *
     * @param User $user
     * @param Client $client
     * @return mixed
     */
    public function delete(User $user, Client $client)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user owns the Client.
     *
     * @param User $user
     * @param Client $client
     * @return mixed
     */
    public function own(User $user, Client $client)
    {
        return $user->isOwner() || $user->isHallAdmin();
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
//        return $query->orderByJoin('activeSubscription.valid_till');
    }
}
