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
}