<?php

namespace shop\validators;

use yii\validators\RegularExpressionValidator;

class IsoCodeValidator extends RegularExpressionValidator
{
    public $pattern = '#^[A-Z_-]*$#s';
//    public $pattern = '#[A-Z]+#';
    public $message = 'Only [A-Z] symbols are allowed.';
}