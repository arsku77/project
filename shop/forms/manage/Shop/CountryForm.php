<?php

namespace shop\forms\manage\Shop;

use shop\entities\Shop\Country;
use shop\validators\IsoCodeValidator;
use shop\validators\IsoNumberValidator;
use yii\base\Model;

class CountryForm extends Model
{
    public $name;
    public $iso_code_2;
    public $iso_code_3;
    public $iso_number_3;
    public $active;
    public $sort;

    private $_country;

    public function __construct(Country $country = null, $config = [])
    {
        if ($country) {
            $this->name = $country->name;
            $this->iso_code_2 = $country->iso_code_2;
            $this->iso_code_3 = $country->iso_code_3;
            $this->iso_number_3 = $country->iso_number_3;
            $this->active = $country->active;
            $this->sort = $country->sort;

            $this->_country = $country;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [


            [['active', 'name', 'iso_code_2', 'iso_code_3', 'iso_number_3' ], 'required'],
            [['active'], 'boolean'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name', 'iso_code_2', 'iso_code_3'], IsoCodeValidator::class],
            ['iso_number_3', IsoNumberValidator::class],
            [['name', 'iso_code_2', 'iso_code_3', 'iso_number_3'], 'unique', 'targetClass' => Country::class, 'filter' => $this->_country ? ['<>', 'id', $this->_country->id] : null]


        ];
    }
}