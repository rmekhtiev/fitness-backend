<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;

class PaymentPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Payment.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //@todo
        return false;
    }

    /**
     * Determine whether the user can view the Payment.
     *
     * @param User $user
     * @param Payment $payment
     * @return mixed
     */
    public function view(User $user, Payment $payment)
    {
        return $this->own($user, $payment);
    }

    /**
     * Determine whether the user can view the collection of Payment.
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
     * Determine whether the user can update the Payment.
     *
     * @param User $user
     * @param Payment $payment
     * @return mixed
     */
    public function update(User $user, Payment $payment)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Payment.
     *
     * @param User $user
     * @param Payment $payment
     * @return mixed
     */
    public function delete(User $user, Payment $payment)
    {
        return $this->own($user, $payment);
    }

    /**
     * Determine whether the user owns the Payment.
     *
     * @param User $user
     * @param Payment $payment
     * @return mixed
     */
    public function own(User $user, Payment $payment)
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
