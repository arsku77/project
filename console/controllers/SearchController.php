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

        $this->stdout('Clearing indexes ES' . PHP_EOL);

        $this->indexer->clear();

        $this->stdout('Indexing of products' . PHP_EOL);

        foreach ($query->each() as $product) {//each dirba paketais po 100irasu
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
                'index' => 'shop',//index pvd - analogas su MySQL - tai bazes pavadinimas
                'body' => [//index turinys
                    'settings' => [
                        'analysis'=> [
                            'index_analyzer'=> [
                                'my_index_analyzer'=> [
                                    'type'=> 'custom',
                                    'tokenizer'=> 'standard',
                                    'filter'=> [
                                        'lowercase',
                                        'asciifolding',
                                    ]
                                ]
                            ],
                            'analyzer'=> [
                                'my_search_analyzer'=> [
                                    'type'=> 'custom',
                                    'tokenizer'=> 'my_ngram_tokenizer',
                                    'filter'=> [
                                        'asciifolding',//paieskoje galima vietoje š vesti s
                                        'lowercase',//mazosios raides
                                    ]
                                ]
                            ],
                            'tokenizer' => [//tokenizer geria uz filter
                                'my_ngram_tokenizer' => [
                                    'type' => 'nGram',
                                    'min_gram' => 3,//paieskos simboliu min skaicius
                                    'max_gram' => 15,//max
                                    'token_chars' => ['letter', 'digit']
                                ]
                            ],
                        ],

                    ],
                    'mappings' => [
                        'products' => [//ES lentele
                            //'filter' => 'asciifolding',
                            '_source' => [//turinys - true, jie bus false,
                                'enabled' => true,//tai ES grazins tik id, o turinio - pvd nerodys
                            ],
                            'properties' => [//parametrai - nurodomi ES lenteles products lauku tipai
                                'id' => [
                                    'type' => 'integer',
                                ],
                                'name' => [
                                    'type' => 'text',
                                    'analyzer'=> 'my_search_analyzer',
                                ],
                                'code' => [
                                    'type' => 'text',
                                    'analyzer'=> 'my_search_analyzer',
                                ],
                                'description' => [
                                    'type' => 'text',
                                    'analyzer'=> 'my_search_analyzer',
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
                                'values' => [//charakteristikoms uzduodame tipa nested - tuom pasakome, kad ten bus masyvas
                                    'type' => 'nested',//kurio raktai tures tokius tipus
                                    'properties' => [
                                        'characteristic' => [
                                            'type' => 'integer'
                                        ],
                                        'value_string' => [
                                            'type' => 'keyword',//vietoje text naudojame keyword, nes sis tipas reiskia tikslia paieska - turi atitikti grieztai
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