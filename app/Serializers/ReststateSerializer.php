<?php


namespace App\Serializers;


use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\Serializer;

class ReststateSerializer extends DataArraySerializer implements Serializer
{

    /**
     * Serialize the meta.
     *
     * @param array $meta
     *
     * @return array
     */
    public function meta(array $meta)
    {
        if (empty($meta)) {
            return [];
        }

        $result['meta'] = $meta;

        if (array_key_exists('pagination', $result['meta'])) {
            $result['links'] = $result['meta']['pagination']['links'];
            unset($result['meta']['pagination']['links']);
        }

        return $result;
    }
}
