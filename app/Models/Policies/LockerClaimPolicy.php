<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\LockerClaim;
use Illuminate\Database\Eloquent\Builder;

class LockerClaimPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create LockerClaim.
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
     * Determine whether the user can view the LockerClaim.
     *
     * @param User $user
     * @param LockerClaim $lockerBooking
     * @return mixed
     */
    public function view(User $user, LockerClaim $lockerBooking)
    {
        return $this->own($user, $lockerBooking);
    }

    /**
     * Determine whether the user can view the collection of LockerClaim.
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
     * Determine whether the user can update the LockerClaim.
     *
     * @param User $user
     * @param LockerClaim $lockerBooking
     * @return mixed
     */
    public function update(User $user, LockerClaim $lockerBooking)
    {
        return $this->own($user, $lockerBooking);
    }

    /**
     * Determine whether the user can delete the LockerClaim.
     *
     * @param User $user
     * @param LockerClaim $lockerBooking
     * @return mixed
     */
    // phpcs:ignore PHPCS_SecurityAudit.BadFunctions.FilesystemFunctions
    public function delete(User $user, LockerClaim $lockerBooking)
    {
        return $this->own($user, $lockerBooking);
    }

    /**
     * Determine whether the user owns the LockerClaim.
     *
     * @param User $user
     * @param LockerClaim $lockerBooking
     * @return mixed
     */
    public function own(User $user, LockerClaim $lockerBooking)
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
