<?php namespace EnvivoLink\EloquentHashids;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;

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
        return env('HASHID_SALT_PREFIX', '') . '.table.' . $model->getTable();
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

    /**
     * @param $uid
     * @return \Illuminate\Support\Collection
     */
    public static function decodeHashid($uid)
    {
        $model = new static();
        $hashids = new Hashids(static::getHashidSalt($model), static::getHashidLength($model), static::getHashidAlphabet($model));
        return collect($hashids->decode($uid));
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uid';
    }
}
