<?php

namespace shop\forms\manage\Shop;

use shop\entities\Shop\Brand;
use shop\entities\Shop\Country;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\validators\SlugValidator;
use yii\helpers\ArrayHelper;

/**
 * @property MetaForm $meta;
 */
class BrandForm extends CompositeForm
{
    public $name;
    public $slug;
    public $countryId;

    private $_brand;

    public function __construct(Brand $brand = null, $config = [])
    {
        if ($brand) {
            $this->name = $brand->name;
            $this->slug = $brand->slug;
            $this->countryId = $brand->country_id;
            $this->meta = new MetaForm($brand->meta);
            $this->_brand = $brand;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug', 'countryId'], 'required'],
            [['countryId'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Brand::class, 'filter' => $this->_brand ? ['<>', 'id', $this->_brand->id] : null]
        ];
    }

    public function countriesList(): array
    {
        return ArrayHelper::map(Country::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
    }


    public function internalForms(): array
    {
        return ['meta'];
    }
}