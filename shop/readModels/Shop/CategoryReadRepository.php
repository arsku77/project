<?php

namespace shop\readModels\Shop;

use Elasticsearch\Client;
use shop\entities\Shop\Category;
use shop\readModels\Shop\views\CategoryView;
use yii\helpers\ArrayHelper;

class CategoryReadRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRoot(): Category
    {
        return Category::find()->roots()->one();
    }

    /**
     * @return Category[]
     */
    public function getAll(): array
    {
        return Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->all();
    }

    public function find($id): ?Category
    {
        return Category::find()->andWhere(['id' => $id])->andWhere(['>', 'depth', 0])->one();
    }

    public function findBySlug($slug): ?Category
    {
        return Category::find()->andWhere(['slug' => $slug])->andWhere(['>', 'depth', 0])->one();
    }

    public function getTreeWithSubsOf(Category $category = null): array
    {//gaunam visas kategorijas
        $query = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft');

        if ($category) {//jei yra kategorija
            $criteria = ['or', ['depth' => 1]];
            foreach (ArrayHelper::merge([$category], $category->parents) as $item) {
                $criteria[] = ['and', ['>', 'lft', $item->lft], ['<', 'rgt', $item->rgt], ['depth' => $item->depth + 1]];
            }
            $query->andWhere($criteria);
        } else {
            $query->andWhere(['depth' => 1]);
        }

        $aggs = $this->client->search([
            'index' => 'shop',
            'type' => 'products',
            'body' => [
                'size' => 0,//reiskia - neisvesk, nerodyk
                'aggs' => [// ES paieskos agregatas
                    'group_by_category' => [//grupavimas
                        'terms' => [//reiskia, kad atitikimas butu tikslus
                            'field' => 'categories',//paieskos laukas
                        ]//si paieska ismeta kategoriju masyva: prekiu lentele sugrupuoja pagal
                    ]//kategorijos id ir duoda skaicius - kiek kategorija (jos id) turi tu paciu prekiu
                ],
            ],
        ]);
//gauname ES bucket poru smasyva: 'key' => 2 (kategorijos id) ir 'doc_count' => 2 (kiek prekiu yra tos kategorijos)
        $counts = ArrayHelper::map($aggs['aggregations']['group_by_category']['buckets'], 'key', 'doc_count');

        return array_map(function (Category $category) use ($counts) {
            return new CategoryView($category, ArrayHelper::getValue($counts, $category->id, 0));
        }, $query->all());
    }
}