<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
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


    public function test_user_can_get_users_with_filters()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['user_id' => $user->id]);
        $task = factory(Task::class)->create(['user_id' => $user->id, 'project_id' => $project->id]);


        $response = $this->getJson(
            '/api/v1/users',
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

    public function test_user_can_get_info()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->getJson(
            '/api/v1/info',

        );
        $response->assertStatus(200)
        ->assertJsonStructure([
                                  "data" => [
        "type",
        "id",
        "attributes" => [
            "name",
            "email",
        ]
    ],
    "links" => [
        "self",
    ]
                              ]);

    }


    public function test_user_can_destroy()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->deleteJson(
            '/api/v1/users/'.$user->id);
        $response->assertStatus(200);


    }

    public function test_user_can_update()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->deleteJson(
            '/api/v1/users/'.$user->id, [
                'name' => 'MyNewUserName',
                'email' => 'myNewEmail@thetest.ru',
                'password' => 'newpassword',
                'password_c' => 'newpassword',
                'old_password' => '12345678'
                ]);
        $response->assertStatus(200);


    }




}
