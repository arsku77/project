<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_brands`.
 */
class m170518_083939_create_shop_brands_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_brands}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'country_id' => $this->integer()->notNull(),
            'slug' => $this->string()->notNull(),
            'meta_json' => 'JSON NOT NULL',
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_brands-slug}}', '{{%shop_brands}}', 'slug', true);

        $this->createIndex('{{%idx-shop_brands-country_id}}', '{{%shop_brands}}', 'country_id');
        $this->addForeignKey('{{%fk-shop_brands-country_id}}', '{{%shop_brands}}', 'country_id', '{{%countries}}', 'id');


    }

    public function down()
    {
        $this->dropTable('{{%shop_brands}}');
    }
}
