<?php namespace Zwmedia\EloquentHashids;

use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

/**
 * Class EloquentHashids
 *
 * @package Zwmedia\EloquentHashids
 */
trait EloquentHashids
{

    /**
     * Boot Eloquent Hashids trait for the model.
     *
     * @return void
     */
    public static function bootEloquentHashids()
    {
        static::created(function (Model $model) {
            $hashids = new Hashids(static::getHashidSalt($model), static::getHashidLength($model), static::getHashidAlphabet($model));
            $model->{static::getHashidColumn($model)} = $hashids->encode(static::getHashidEncodingValue($model));
            $model->save();
        });
    }

    /**
     * @param Model $model
     * @return string
     */
    public static function getHashidSalt(Model $model)
    {
        return 'table.' . $model->getTable() . '.' . $model->getKey();
    }

    /**
     * @param Model $model
     * @return integer
     */
    public static function getHashidLength(Model $model)
    {
        return 5;
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public static function getHashidAlphabet(Model $model)
    {
        return 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    }

    /**
     * @param Model $model
     * @return string
     */
    public static function getHashidColumn(Model $model)
    {
        return 'uid';
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public static function getHashidEncodingValue(Model $model)
    {
        return $model->getKey();
    }
}