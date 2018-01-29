<?php

namespace backend\forms\Shop;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Country;

class CountrySearch extends Model
{
    public $id;
    public $name;
    public $slug;

    public $isoCode2;
    public $isoCode3;
    public $isoNumber3;
    public $active;
    public $sort;


    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'isoCode2', 'isoCode3', 'isoNumber3', 'sort'], 'safe'],
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
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'iso_code_2', $this->isoCode2])
            ->andFilterWhere(['like', 'iso_code_3', $this->isoCode3])
            ->andFilterWhere(['like', 'iso_number_3', $this->isoNumber3]);

        return $dataProvider;
    }
}
