<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property integer $product_id;
 * @property integer $brand_id;
 */
class BrandAssignment extends ActiveRecord
{
    public static function create($brandId): self
    {
        $assignment = new static();
        $assignment->brand_id = $brandId;
        return $assignment;
    }

    public function isForBrand($id): bool
    {
        return $this->brand_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%shop_brand_assignments}}';
    }
}