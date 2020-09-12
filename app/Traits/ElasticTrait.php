<?php

namespace App\Traits;

use App\Exceptions\ElasticNoWork;
use Elasticsearch;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;

/**
 * Trait ElasticTrait
 * @package App\Traits
 */
trait ElasticTrait
{
    /**
     * Поиск в elasticsearch
     *
     * @param $body
     * @param $paginate
     * @param $page
     * @param $sort
     * @return mixed
     * @throws ElasticNoWork
     */
    protected function search($body, $paginate, $page, $sort)
    {
        $query = [
            'body'  => [
                "sort" => [ $sort['col'] => ["order" => $sort['type']]],
                'query'=> [
                    'multi_match' => [
                        'query' => $body,
                        'fields' => [
                            'title',
                            'body',
                            'deadline',
                            'created_at',
                            'updated_at',
                        ],
                        "type" => "cross_fields",
                        "analyzer" => "russian"
                    ]]
            ],
        ];

        try {
            $result = Elasticsearch::search($query);
        } catch (NoNodesAvailableException $exception) {
            throw new ElasticNoWork();
        } catch (BadRequest400Exception $exception) {
            throw new ElasticNoWork();
        }

        $result = $result['hits']['hits'];

        return $result;
    }

    /**
     * Удаляем индексацию
     *
     * (delete) /search/index
     *
     * @param $index
     * @return mixed
     * @throws ElasticNoWork
     */
    protected function indexDelete($index)
    {
        try {
            $response = Elasticsearch::indices()->delete(['index' => $index]);
        } catch (NoNodesAvailableException $exception) {
            throw new ElasticNoWork();
        }

        return $response;
    }
}
