<?php

namespace App\Models;

use Specialtactics\L5Api\Models\RestfulModel;

class BaseModel extends RestfulModel
{
    /**
     * Acts like $withCount (eager loads relations), however only for immediate controller requests for that object
     * This is useful if you want to use "with" for immediate resource routes, however don't want these relations
     *  always loaded in various service functions, for performance reasons
     *
     * @deprecated Use  getItemWithCount() and getCollectionWith()
     * @var array Relations to load implicitly by Restful controllers
     */
    public static $localWithCount = null;

    /**
     * What count of relations should one model of this entity be returned with, from a relevant controller
     *
     * @var null|array
     */
    public static $itemWithCount = [];

    /**
     * What count of relations should a collection of models of this entity be returned with, from a relevant controller
     * If left null, then $itemWithCount will be used
     *
     * @var null|array
     */
    public static $collectionWithCount = null;

    /**
     * If using deprecated $localWith then use that
     * Otherwise, use $itemWith
     *
     * @return array
     */
    public static function getItemWithCount()
    {
        if (is_null(static::$localWithCount)) {
            return static::$itemWithCount;
        } else {
            return static::$localWithCount;
        }
    }

    /**
     * If using deprecated $localWith then use that
     * Otherwise, if collectionWith hasn't been set, use $itemWith by default
     * Otherwise, use collectionWith
     *
     * @return array
     */
    public static function getCollectionWithCount()
    {
        if (is_null(static::$localWithCount)) {
            if (! is_null(static::$collectionWithCount)) {
                return static::$collectionWithCount;
            } else {
                return static::$itemWithCount;
            }
        } else {
            return static::$localWithCount;
        }
    }
}
