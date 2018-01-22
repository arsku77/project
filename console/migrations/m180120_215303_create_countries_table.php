<?php

use yii\db\Migration;

class m180120_215303_create_countries_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%countries}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'iso_code_2' => $this->string(2)->notNull(),
            'iso_code_3' => $this->string(3)->notNull(),
            'iso_number_3' => $this->string(3)->notNull(),
            'active' =>  $this->boolean()->notNull(),
            'sort' => $this->smallInteger()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-countries-iso_code_3}}', '{{%countries}}', 'iso_code_3', true);
        $this->createIndex('{{%idx-countries-iso_code_2}}', '{{%countries}}', 'iso_code_2', true);
        $this->createIndex('{{%idx-countries-iso_number_3}}', '{{%countries}}', 'iso_number_3', true);

        $this->batchInsert('{{%countries}}',
            ['id', 'name', 'iso_code_2', 'iso_code_3', 'iso_number_3', 'active', 'sort'], [
                [1, 'UNITED STATES', 'US', 'USA', '840', true, 4],
                [2, 'UNITED KINGDOM', 'GB', 'GBR', '826', true, 3],
                [3, 'UKRAINE', 'UA', 'UKR', '804', true, 2],
                [4, 'LITHUANIA', 'LT', 'LTU', '440', true, 1],
                [5, 'LATVIA', 'LV', 'LVA', '428', true, 5],
                [6, 'ESTONIA', 'EE', 'EST', '233', true, 6],
                [7, 'POLAND', 'PL', 'POL', '616', true, 7],
                [8, 'GERMANY', 'DE', 'DEU', '276', true, 8],
            ]);


    }

    public function down()
    {
        $this->dropTable('{{%countries}}');
    }
}
