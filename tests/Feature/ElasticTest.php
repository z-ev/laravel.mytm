<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class ElasticTest extends TestCase
{

    /**
     * Поиск и фильтр
     */
    public function test_user_can_search_with_filters ()
    {

        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id' => $user->id]);

        $task = factory(Task::class)->create(['user_id' => $user->id, 'project_id' => $project->id, 'title' => 'This is best title']);

        $response = $this->postJson('/api/v1/search');
        $response->assertStatus(200);

        $response = $this->getJson(
            '/api/v1/search',
            [
                'ser' => 'best',
                'paginate' => 1,
                'page' => 1,
                'order_by' => '_id',
                'order_dir' => 'asc',
            ]
        );

        $response->assertStatus(200);

    }


    /**
     * Удаляем документы из elasticsearch
     */
    public function test_user_can_delete_es_index ()
    {

        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id' => $user->id]);

        $task = factory(Task::class)->create(['user_id' => $user->id, 'project_id' => $project->id, 'title' => 'This is best title']);

        $response = $this->deleteJson('/api/v1/search');

        $response->assertStatus(200);

    }


    /**
     * Создаем документы в elasticsearch
     */
    public function test_user_can_set_es_index ()
    {

        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id' => $user->id]);

        $task = factory(Task::class)->create(['user_id' => $user->id, 'project_id' => $project->id, 'title' => 'This is best title']);

        $response = $this->postJson('/api/v1/search');

        $response->assertStatus(200);

    }


}
