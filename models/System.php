<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 */
class System extends ActiveRecord
{
    use InstanceByNameTrait;
}