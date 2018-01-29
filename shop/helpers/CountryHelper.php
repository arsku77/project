<?php

namespace shop\helpers;

use shop\entities\Shop\Country;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CountryHelper
{
    public static function statusList(): array
    {
        return [
            Country::ACTIVE_NO => 'Draft',
            Country::ACTIVE_YES => 'Active',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Country::ACTIVE_NO:
                $class = 'label label-default';
                break;
            case Country::ACTIVE_YES:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}