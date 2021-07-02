<?php


namespace app\models;


use Error;

trait InstanceByNameTrait
{
    /**
     * @var static[]
     */
    private static $_instanceByName;

    /**
     * @param string $name
     * @param bool $createIfNotExists
     * @return static|null
     */
    public static function instanceByName($name, $createIfNotExists = false)
    {
        if (self::$_instanceByName === null) {
            self::$_instanceByName = static::find()->indexBy('name')->all();
        }
        if (!array_key_exists($name, self::$_instanceByName) && $createIfNotExists) {
            $newInstance = new static();
            $newInstance->name = $name;
            if (!$newInstance->save()) {
                throw new Error('Save is invalid');
            }
            self::$_instanceByName[$name] = $newInstance;
        }
        return self::$_instanceByName[$name] ?? null;
    }
}