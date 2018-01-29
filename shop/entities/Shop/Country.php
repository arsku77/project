<?php

namespace shop\entities\Shop;

//use shop\entities\Shop\Brand;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property string $name
 * @property string $iso_code_2
 * @property string $iso_code_3
 * @property string $iso_number_3
 * @property boolean $active
 * @property integer $sort
 */
class Country extends ActiveRecord
{

    public static function create($name, $iso_code_2, $iso_code_3 , $iso_number_3 , $active , $sort): self
    {
        $country = new static();
        $country->name = $name;
        $country->iso_code_2 = $iso_code_2;
        $country->iso_code_3 = $iso_code_3;
        $country->iso_number_3 = $iso_number_3;
        $country->active = $active;
        $country->sort = $sort;
        return $country;
    }

    /**
     * @param $name
     * @param $iso_code_2
     * @param $iso_code_3
     * @param $iso_number_3
     * @param $active
     * @param $sort
     */
    public function edit($name, $iso_code_2, $iso_code_3 , $iso_number_3 , $active , $sort): void
    {
        $this->name = $name;
        $this->iso_code_2 = $iso_code_2;
        $this->iso_code_3 = $iso_code_3;
        $this->iso_number_3 = $iso_number_3;
        $this->active = $active;
        $this->sort = $sort;
    }

    public function getBrand(): ActiveQuery
    {
        return $this->hasMany(Brand::class, ['country_id' => 'id']);
    }


    ##########################

    public static function tableName(): string
    {
        return '{{%countries}}';
    }

}