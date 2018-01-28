<?php

namespace shop\entities\Shop;

use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use shop\entities\Shop\Country;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $country_id
 * @property Meta $meta
 */
class Brand extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $countryId, Meta $meta): self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $countryId ? $brand->country_id = $countryId : $brand->country_id = 1;
        $brand->meta = $meta;
        return $brand;
    }

    public function edit($name, $slug, $countryId, Meta $meta): void
    {
        $this->name = $name;
        $this->country_id = $countryId;
        $this->slug = $slug;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->name;
    }

    ##########################
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }


    ##########################

    public static function tableName(): string
    {
        return '{{%shop_brands}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::className(),
        ];
    }
}