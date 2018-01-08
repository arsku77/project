<?php

namespace console\controllers;

use shop\entities\Shop\Product\Product;
use shop\services\search\ProductIndexer;
use yii\console\Controller;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Yii;

class SearchController extends Controller
{
    private $indexer;

    public function __construct($id, $module, ProductIndexer $indexer, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->indexer = $indexer;
    }

    public function actionReindex(): void
    {
        $query = Product::find()
            ->active()
            ->with(['category', 'categoryAssignments', 'tagAssignments', 'values'])
            ->orderBy('id');

        $this->stdout('Clearing' . PHP_EOL);

        $this->indexer->clear();

        $this->stdout('Indexing of products' . PHP_EOL);

        foreach ($query->each() as $product) {
            /** @var Product $product */
            $this->stdout('Product #' . $product->id . PHP_EOL);
            $this->indexer->index($product);
        }

        $this->stdout('Done!' . PHP_EOL);
    }

    /**
     * create index for elastic search
     */
    public function actionCreateIndex(): void
    {
        $client = $this->getClient();
        try {

//            $client->indices()->delete([
//                'index' => 'shop'
//            ]);
//
//            $this->stdout('Deleted all settings elasticsearch!' . PHP_EOL);

            $client->indices()->create([
//                'index'=> [
//                    'index' => 'my_idx',
//                    'type' => 'my_type',
//                    'analysis' => [
//                        'index_analyzer'=> [
//                            'my_index_analyzer' => [
//                                'type' => 'custom',
//                                'tokenizer'=> 'standard',
//                                'filter' => [
//                                    'lowercase',
//                                    'mynGram'
//                                ]
//                            ]
//                        ],
//                        'search_analyzer' => [
//                            'my_search_analyzer' => [
//                                'type' => 'custom',
//                                'tokenizer' => 'standard',
//                                'filter' => [
//                                    'standard',
//                                    'lowercase',
//                                    'mynGram'
//                                ]
//                            ]
//                        ],
//                        'filter' => [
//                            'mynGram' => [
//                                'type' => 'nGram',
//                                'min_gram' => 2,
//                                'max_gram' => 50
//                            ]
//                        ]
//                    ]
//                ]
//                ////////////////////////////////////////

                'index' => 'shop',
                'body' => [
                    'mappings' => [
                        'products' => [
                            '_source' => [
                                'enabled' => true,
                            ],
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                ],
                                'name' => [
                                    'type' => 'text',
                                ],
                                'description' => [
                                    'type' => 'text',
                                ],
                                'price' => [
                                    'type' => 'integer',
                                ],
                                'rating' => [
                                    'type' => 'float',
                                ],
                                'brand' => [
                                    'type' => 'integer',
                                ],
                                'categories' => [
                                    'type' => 'integer',
                                ],
                                'tags' => [
                                    'type' => 'integer',
                                ],
                                'values' => [
                                    'type' => 'nested',
                                    'properties' => [
                                        'characteristic' => [
                                            'type' => 'integer'
                                        ],
                                        'value_string' => [
                                            'type' => 'keyword',
                                        ],
                                        'value_int' => [
                                            'type' => 'integer',
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
            $this->stdout('Done creating all indexes!' . PHP_EOL);

        } catch (Missing404Exception $e) {
            $this->stdout('error creating all indexes!' . PHP_EOL . $e);

        }
    }

    public function actionDeleteIndex(): void
    {
        try {
            $this->getClient()->indices()->delete([
                'index' => 'shop'
            ]);
            $this->stdout('Deleted all settings elasticsearch!' . PHP_EOL);

        } catch (Missing404Exception $e) {
            $this->stdout('error Deleting all settings elasticsearch!' . PHP_EOL . $e);

        }
    }




    private function getClient(): Client
    {
        return Yii::$container->get(Client::class);
    }

}