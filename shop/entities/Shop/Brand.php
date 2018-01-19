<?php

namespace shop\entities\Shop;

use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $country_name
 * @property Meta $meta
 */
class Brand extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $countryName, Meta $meta): self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->country_name = $countryName;
        $brand->meta = $meta;
        return $brand;
    }

    public function edit($name, $slug, $countryName, Meta $meta): void
    {
        $this->name = $name;
        $this->country_name = $countryName;
        $this->slug = $slug;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->name;
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