<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use WithFaker;
    /**
     * Добавление проекта (список задач)
     */
    public function test_user_can_add_project()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($user = factory(User::class)->create(), 'api')
            ->json('post', '/api/v1/projects',[
                'title' => 'The Title '.$this->faker->title,
                'body' => 'The bady '.$this->faker->paragraph,
                'deadline' => Carbon::parse('2020-08-23 16:53:23'),
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
                                'type',
                                'user_id',
                            ],
                            'links' => [
                                'self',
                            ],
                        ],
                    ],
                    'links' => [
                        'self',
                    ],
                ]
            ]);
    }

    public function test_user_can_update_project()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id'=>$user->id]);

        $response = $this->patchJson('api/v1/projects/' . $project->id, [
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
                                'type',
                                'user_id',
                            ],
                            'links' => [
                                'self',
                            ],
                        ],
                    ],
                    'links' => [
                        'self',
                    ],
                ]
            ]);

    }

    public function  test_user_can_destroy_project()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id'=>$user->id]);

        $response = $this->deleteJson('api/v1/projects/' . $project->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'message',
                ],
                'links' => [
                    'self',
                ],
            ]);
    }

    public function  test_user_can_destroy_project_with_tasks()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id'=>$user->id]);

        $response = $this->deleteJson('api/v1/projects/' . $project->id. '/kill');

        $response->assertStatus(200)
            ->assertJsonStructure([
                                      'data' => [
                                          'message',
                                      ],
                                      'links' => [
                                          'self',
                                      ],
                                  ]);
    }


    public function test_user_can_view_project()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id'=>$user->id]);

        $response = $this->getJson('api/v1/projects/' . $project->id);

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
                                'type',
                                'user_id',
                            ],
                            'links' => [
                                'self',
                            ],
                        ],
                    ],
                    'links' => [
                        'self',
                    ],
                ]
            ]);
    }

    public function test_user_can_projects_with_filters()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id' => $user->id]);
        $task = factory(Task::class)->create(['user_id' => $user->id, 'project_id' => $project->id]);


        $response = $this->getJson(
            '/api/v1/projects',
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
}
