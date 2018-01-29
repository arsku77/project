<?php

namespace shop\validators;

use yii\validators\RegularExpressionValidator;

class IsoNumberValidator extends RegularExpressionValidator
{
    public $pattern = '#^[0-9_-]*$#s';
    public $message = 'Only [0-9] symbols are allowed.';
}