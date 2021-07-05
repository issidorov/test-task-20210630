<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 */
class Browser extends ActiveRecord
{
    use InstanceByNameTrait;

    /**
     * @var array|null
     */
    private static $_instanceById;

    /**
     * @param $id
     * @return static
     */
    public static function getById($id)
    {
        if (static::$_instanceById === null) {
            static::$_instanceById = static::find()
                ->indexBy('id')
                ->all();
        }
        return static::$_instanceById[$id];
    }
}