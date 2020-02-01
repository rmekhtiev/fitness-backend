<?php

namespace App\Models\Policies;

use App\Models\Locker;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class LockerPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Locker.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can view the Locker.
     *
     * @param User $user
     * @param Locker $locker
     * @return mixed
     */
    public function view(User $user, Locker $locker)
    {
        return true;
    }

    /**
     * Determine whether the user can view the collection of Locker.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can update the Locker.
     *
     * @param User $user
     * @param Locker $locker
     * @return mixed
     */
    public function update(User $user, Locker $locker)
    {
        return $this->own($user, $locker);
    }

    /**
     * Determine whether the user can delete the Locker.
     *
     * @param User $user
     * @param Locker $locker
     * @return mixed
     */
    public function delete(User $user, Locker $locker)
    {
        return $this->own($user, $locker);
    }

    /**
     * Determine whether the user owns the Locker.
     *
     * @param User $user
     * @param Locker $locker
     * @return mixed
     */
    public function own(User $user, Locker $locker)
    {
        return $user->isOwner() || ($user->isHallAdmin()
                && !empty($user->associatedEmployee)
                && $locker->hall_id == $user->associatedEmployee->hall_id);
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
