<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function test_user_can_signout()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

       $response = $this->actingAs($user, 'api')
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
}
