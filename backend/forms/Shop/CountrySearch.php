<?php

namespace backend\forms\Shop;

use shop\helpers\CountryHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Country;

class CountrySearch extends Model
{
    public $id;
    public $name;
    public $slug;

    public $iso_code_2;
    public $iso_code_3;
    public $iso_number_3;
    public $active;
    public $sort;


    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'iso_code_2', 'iso_code_3', 'iso_number_3', 'active'], 'safe'],//listed search field
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Country::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['sort' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'iso_code_2', $this->iso_code_2])
            ->andFilterWhere(['like', 'iso_code_3', $this->iso_code_3])
            ->andFilterWhere(['like', 'iso_number_3', $this->iso_number_3]);

        return $dataProvider;
    }

    public function statusList(): array
    {
        return CountryHelper::statusList();
    }

}
