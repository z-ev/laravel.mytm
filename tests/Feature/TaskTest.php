<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{

    use WithFaker;


    /**
     * Добавление задачи
     */
    public function test_user_can_add_task()
    {

        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id' => $user->id]);

        $response = $this->postJson('api/v1/tasks',[

            'project_id' => $project->id,
            'title' => 'New Title '.$this->faker->title,
            'body' => 'New bady '.$this->faker->paragraph,

            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                                      'data' => [
                                          'type',
                                          'id',
                                          'attributes' => [
                                              'status',
                                              'title',
                                              'body',
                                              'deadline',
                                              'created_at',
                                              'updated_at',
                                          ],
                                          'relationships' => [
                                              'user' => [
                                                  'data' => [
                                                      'user_id',
                                                  ],
                                                  'links' => [
                                                      'self',
                                                  ]
                                              ],
                                              'project' => [
                                                  'data' => [
                                                      'project_id',
                                                  ],
                                                  'links' => [
                                                      'self',
                                                  ]

                                              ]
                                          ]
                                      ],
                                      'links' => [
                                          'self',
                                      ]
                                  ]);

    }

    /**
     * Изменяем задачу
     */
    public function test_user_can_update_task()
    {

        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $task = factory(Task::class)->create(['user_id'=>$user->id]);

        $response = $this->patchJson('api/v1/tasks/'.$task->id,[
            'title' => 'New Title '.$this->faker->title,
            'body' => 'New bady '.$this->faker->paragraph,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                                      'data' => [
                                          'type',
                                          'id',
                                          'attributes' => [
                                              'status',
                                              'title',
                                              'body',
                                              'deadline',
                                              'created_at',
                                              'updated_at',
                                          ],
                                          'relationships' => [
                                              'user' => [
                                                  'data' => [
                                                      'user_id',
                                                  ],
                                                  'links' => [
                                                      'self',
                                                  ]
                                              ],
                                              'project' => [
                                                  'data' => [
                                                      'project_id',
                                                  ],
                                                  'links' => [
                                                      'self',
                                                  ]

                                              ]
                                          ]
                                      ],
                                      'links' => [
                                          'self',
                                      ]
                                  ]);

    }

    /**
     * Удаляем задачу
     */
    public function test_user_can_destroy_task()
    {

        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $task = factory(Task::class)->create(['user_id'=>$user->id]);

        $response = $this->deleteJson('api/v1/tasks/'.$task->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                'message',
                 ]
                                  ]);

    }

    /**
     * Фильтуем задачи
     */
    public function test_user_can_tasks_with_filters()
    {

        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id' => $user->id]);

        $task = factory(Task::class)->create(['user_id' => $user->id, 'project_id' => $project->id]);


        $response = $this->getJson(
            '/api/v1/tasks',
            [
                'projects' => 1,
                'tasks' => 1,
                'paginate' => 1,
                'order_by' => 'id',
                'order_dir' => 'asc',
            ]
        );
        $response->assertStatus(200);
    }

    /**
     * Смотрим все задачи
     */
    public function test_user_can_view_all_tasks()
    {

        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id'=>$user->id]);
        $task = factory(task::class)->create(['user_id'=>$user->id , 'project_id' => $project->id]);

        $response = $this->getJson('api/v1/tasks/'.$task->id);

        $response->assertStatus(200);

    }


    /**
     * Смотрим конкретную задачу
     */
    public function test_user_can_view_task()
    {

        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id'=>$user->id]);

        $task = factory(task::class)->create(['user_id'=>$user->id , 'project_id' => $project->id]);

        $response = $this->getJson('api/v1/tasks');

        $response->assertStatus(200);

    }


}
