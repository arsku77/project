<?php

namespace shop\validators;

use yii\validators\RegularExpressionValidator;

class UppercaseValidator extends RegularExpressionValidator
{
    public $pattern = '#^[A-Z ]*$#s';
    public $message = 'Only [A-Z] symbols are allowed.';
}