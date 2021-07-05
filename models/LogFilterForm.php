<?php


namespace app\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class LogFilterForm extends Model
{
    const ATTR_DATE = 'date';
    const ATTR_SYSTEM = 'system';

    /** @var string - begin date */
    public $begin;
    /** @var string - end date */
    public $end;
    /** @var int */
    public $system;

    public function __construct($defaultBeginDate, $defaultEndDate)
    {
        parent::__construct([
            'begin' => $defaultBeginDate,
            'end' => $defaultEndDate,
        ]);
    }

    public function rules()
    {
        return [
            [['begin', 'end'], 'required'],
            [['begin', 'end'], 'date', 'format' => 'php:Y-m-d'],
            [['system'], 'integer'],
            ['system', 'default', 'value' => null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'begin' => 'Начальная дата',
            'end' => 'Конечная дата',
            'system' => 'Операционная система',
        ];
    }

    public function afterValidate()
    {
        $this->validateDateRange();
    }

    public function validateDateRange()
    {
        if (strtotime($this->begin . ' + 1 year') < strtotime($this->end)) {
            $this->addError('begin', 'Допустимый промежуток не более 1 года');
            $this->addError('end', 'Допустимый промежуток не более 1 года');
        }
        if (strtotime($this->begin) > strtotime($this->end)) {
            $this->addError('begin', 'Начальная дата больше конечной');
            $this->addError('end', 'Конечная дата меньше начальной');
        }
    }

    public function apply(ActiveQuery $query, array $attributes = [self::ATTR_DATE, self::ATTR_SYSTEM])
    {
        if (in_array(self::ATTR_DATE, $attributes)) {
            $query->andFilterWhere(['>=', 'date', $this->begin]);
            $query->andFilterWhere(['<=', 'date', $this->end]);
        }
        if (in_array(self::ATTR_SYSTEM, $attributes)) {
            $query->andWhere(['system_id' => $this->system]);
        }
    }
}