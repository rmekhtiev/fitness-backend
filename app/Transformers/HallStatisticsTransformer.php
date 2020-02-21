<?php


namespace App\Transformers;

use App\Enums\PaymentMethod;
use App\Models\Hall;
use App\Models\Payment;
use Illuminate\Support\Collection;

class HallStatisticsTransformer extends BaseTransformer
{
    /**
     * @param Hall $object
     * @return array|mixed
     * @throws \Exception
     */
    public function transform($object)
    {
        $response = new \stdClass();

        $response->payments = collect($object->payments)
            ->groupBy('sellable_type')
            ->map(function (Collection $byTypeCollection) {
                return $byTypeCollection
                    ->groupBy('method')
                    ->map(function (Collection $byMethodCollection) {
                        return $byMethodCollection->reduce(function ($carry, Payment $payment) {
                            return $carry + $payment->total;
                        });
                    });
            })
            ->transform(function (Collection $collection) {
                return $collection->put('total', $collection->sum());
            });

        $response->payments->put('total', collect(PaymentMethod::getValues())->push('total')->mapWithKeys(function($type) use ($response) {
            return [$type => $response->payments->sum($type)];
        }));

        return parent::transform($response);
    }
}
