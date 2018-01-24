<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Country;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Brand;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property array $newBrandNames
 */
class BrandsForm extends Model
{
    public $existing = [];
    public $brandNew;
    public $countryId;
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->existing = ArrayHelper::getColumn($product->brandAssignments, 'brand_id');
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['existing', 'each', 'rule' => ['integer']],
            ['existing', 'default', 'value' => []],
            ['brandNew', 'string'],
            [['countryId'], 'integer'],
            [['metaTitle'], 'string', 'max' => 255],
            [['metaDescription', 'metaKeywords'], 'string'],


        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function countriesList(): array
    {
        return ArrayHelper::map(Country::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
    }

    public function getNewBrandNames(): array
    {
        return array_filter(array_map('trim', preg_split('#\s*,\s*#i', $this->brandNew)));
    }

}