<?php

use yii\db\Migration;

class m180109_190203_create_shop_brand_assignments_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_brand_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'brand_name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-shop_brand_assignments}}', '{{%shop_brand_assignments}}', ['product_id', 'brand_id']);
        
        $this->createIndex('{{%idx-shop_brand_assignments-product_id}}', '{{%shop_brand_assignments}}', 'product_id');
        $this->createIndex('{{%idx-shop_brand_assignments-brand_id}}', '{{%shop_brand_assignments}}', 'brand_id');

        $this->addForeignKey('{{%fk-shop_brand_assignments-product_id}}', '{{%shop_brand_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_brand_assignments-brand_id}}', '{{%shop_brand_assignments}}', 'brand_id', '{{%shop_brands}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%shop_brand_assignments}}');
    }
}
