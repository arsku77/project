<?php

namespace shop\services\search;

use Elasticsearch\Client;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Value;
use yii\helpers\ArrayHelper;

class ProductIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'shop',
            'type' => 'products',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(Product $product): void
    {
        $this->client->index([
            'index' => 'shop',//ES indeksas (analogas su MySQL indeksu)
            'type' => 'products',//ES tipas - analogas su MySQL lentele
            'id' => $product->id,//id - analogas su musu id
            'body' => [ //kunas -kuris yra vienas ES irasas
                'id' => $product->id,//reiks rusiavimui
                'name' => $product->name,//prekes pvd
                'code' => $product->code,//prekes pvd
                'description' => strip_tags($product->description),//prekes aprasymas, atmetus tagus
                'price' => $product->price_new,//kad galetume rusiuoti paieskos rezultata
                'rating' => $product->rating,//kad galetume rusiuoti paieskos rezultata
                'brand' => $product->brand_id,//kad galetume rusiuoti paieskos rezultata
                'categories' => ArrayHelper::merge(//kaip viena skiltele, i ES ikeliame prekes kategoriju
                    [$product->category->id],//kad is paieskos pagal prekes pavadinima - gautume visu tos prekes kaegoriju id
                    ArrayHelper::getColumn($product->category->parents, 'id'),//tai pagrindine kategorija
                    ArrayHelper::getColumn($product->categories, 'id'),//tai papildomos kategorijos
                    array_reduce(array_map(function (Category $category) {
                        return ArrayHelper::getColumn($category->parents, 'id');
                    }, $product->categories), 'array_merge', [])
                ),
                'tags' => ArrayHelper::getColumn($product->tagAssignments, 'tag_id'),
                'values' => array_map(function (Value $value) {//charakteristikos - kad galetume pagal jas ieskoti - pagal
                    return [                                // filtrus - juose - sarase isrenkame charakteristika ir pagal saraso reiksme paieskome su ES sioje lenteleje
                        'characteristic' => $value->characteristic_id,
                        'value_string' => (string)$value->value,
                        'value_int' => (int)$value->value,
                    ];
                }, $product->values),
            ],
        ]);
    }

    public function remove(Product $product): void
    {
        $this->client->delete([
            'index' => 'shop',
            'type' => 'products',
            'id' => $product->id,
        ]);
    }
}