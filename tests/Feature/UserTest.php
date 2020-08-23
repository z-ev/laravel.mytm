<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use http\Message\Body;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\HasApiTokens;
use Tests\TestCase;


class UserTest extends TestCase
{
    use WithFaker;

     /**
     * Регистрация пользователя
     */
    public function test_user_can_signup()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/api/v1/signup',[
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '12345678',
            'password_c' => '12345678'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'user_id',
                    'status',
                    'attributes' => [
                        'name',
                        'token',
                    ],
                ],
                'links' => [
                    'self',
                ]
            ]);
    }

    /**
     * Авторизация
     */
    public function test_user_can_signin()
    {
        $this->withoutExceptionHandling();


        $user = factory(User::class)->create();
        $response = $this->post('/api/v1/signin',[
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'user_id',
                    'status',
                    'attributes' => [
                        'token',
                    ],
                ],
                'links' => [
                    'self',
                ]
            ]);
    }
    /**
     * Выход
     */
    public function test_user_can_signout()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = factory(User::class)->create(), 'api')
            ->json('get', '/api/v1/signout');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        'message',
                    ],
                    'links' => [
                        'self',
                    ]
                ]
                ]);
    }

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


}
