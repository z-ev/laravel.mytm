<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\ElasticNoWork;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Traits\ElasticTrait;
use Elasticsearch;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class SearchController
 * @package App\Http\Controllers\Api\v1
 */
class SearchController extends Controller
{
    use ElasticTrait;

    /**
     * Поиск в Elasticsearch по ключевому слову
     *
     * (get) /search/?ser=word
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\ElasticNoWork
     */
    public function find(Request $request)
    {
        $word = request('ser');
        $paginate = request('paginate');
        $page = request('page');
        $by = request('order_by');
        $order = request('order_dir');

        if (!$by) {
            $sort['col']  = '_id';
        } else {
            $sort['col'] = $by;
        }

        if (!$order) {
            $sort['type']  = 'asc';
        } else {
            $sort['type'] = $order;
        }

        if (!$paginate) {
            $paginate = 5;
        }

        isset($page) ?: $page = 1;

        if (isset($word)) {
            $result = $this->paginate($this->search($word, $paginate, $page, $sort), $paginate, $page, route('search'));

            return $result;
        }
    }

    /**
     * Пагинация для ответов от Elasticsearch
     *
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param null $baseUrl
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 5, $page = null, $baseUrl = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        if ($baseUrl) {
            $lap->setPath($baseUrl);
        }

        return $lap;
    }

    /**
     * Удаляем документы из Elasticsearch
     *
     * (delete) /search/index
     *
     * @return mixed
     * @throws \App\Exceptions\ElasticNoWork
     */
    public function deleteIndex()
    {
        $this->indexDelete('project');
        return $this->indexDelete('task');
    }

    /**
     * Создаем индексы документов в Elasticsearch
     *
     * (post) /search/index
     *
     * @return mixed
     * @throws ElasticNoWork
     */
    public function createIndex()
    {
        $projects = Project::where('user_id', '>', 0)->get();

        foreach ($projects as $project) {
            $params = [
                'index' => 'project',
                'type' => 'text',
                'id' => $project['id'],
                'body'  => ['title' => $project['title'],
                            'body'=>$project['body'],
                            'user_id'=>$project['user_id'],
                            'status' => $project['deadline'],
                            'deadline' => $project['deadline'],
                            'created_at' => $project['created_at'],
                            'updated_at' => $project['updated_at'],
                            'links' => [
                                'self' => route('projects.show', $project['id']),
                                ]
                            ],
                ];

            try {
                $response = Elasticsearch::index($params);
            } catch (NoNodesAvailableException $exception) {
                throw new ElasticNoWork();
            }
        }

        $tasks = Task::where('user_id', '>', 0)->get();

        foreach ($tasks as $task) {
            $params = [
                'index' => 'task',
                'type' => 'text',
                'id' => $task['id'],
                'body' => ['title' => $task['title'],
                    'body'=>$task['body'],
                    'project_id'=>$task['project_id'],
                    'user_id'=>$task['user_id'],
                    'status' => $task['deadline'],
                    'deadline' => $task['deadline'],
                    'created_at' => $task['created_at'],
                    'updated_at' => $task['updated_at'],
                    'links' => [
                                'self' => route('tasks.show', $task['id']),
                            ]
                ],
            ];

            try {
                $response = Elasticsearch::index($params);
            } catch (NoNodesAvailableException $exception) {
                throw new ElasticNoWork();
            }
        }
        return $response;
    }
}
