<?php


namespace app\models;


use yii\db\ActiveQuery;

class LogFilterForm extends \yii\base\Model
{
    /** @var string - begin date */
    public $begin;
    /** @var string - end date */
    public $end;
    /** @var int */
    public $system;
    /** @var int */
    public $browser;

    public function rules()
    {
        return [
            [['begin', 'end'], 'date', 'format' => 'php:Y-m-d'],
            [['system', 'browser'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'begin' => 'Начальная дата',
            'end' => 'Конечная дата',
            'system' => 'Операционная система',
            'browser' => 'Браузер',
        ];
    }

    public function afterValidate()
    {
        $this->validateDateRange();
    }

    public function validateDateRange()
    {
        if ($this->begin && !$this->end) {
            $this->end = date('Y-m-d', strtotime($this->begin . ' + 1 year'));
        }
        if (!$this->begin && $this->end) {
            $this->begin = date('Y-m-d', strtotime($this->end . ' - 1 year'));
        }
        if ($this->begin && $this->end) {
            if (strtotime($this->begin . ' + 1 year') < strtotime($this->end)) {
                $this->addError('begin', 'Допустимый промежуток не более 1 года');
                $this->addError('end', 'Допустимый промежуток не более 1 года');
            }
            if (strtotime($this->begin) > strtotime($this->end)) {
                $this->addError('begin', 'Начальная дата больше конечной');
                $this->addError('end', 'Конечная дата меньше начальной');
            }
        }
    }

    public function apply(ActiveQuery $query)
    {
        $query->andFilterWhere(['>=', 'log.time', strtotime($this->begin)]);
        $query->andFilterWhere(['<', 'log.time', strtotime($this->end . ' +1 day')]);
        $query->andFilterWhere(['log.system_id' => $this->system]);
        $query->andFilterWhere(['log.browser_id' => $this->browser]);
    }
}