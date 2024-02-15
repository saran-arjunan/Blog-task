<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidateUserWithValidCredentials()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'name' => 'saran',
            'password' => md5('1234567890')
        ]);

        $response = $this->postJson('/validate-user', [
            'user_name' => 'saran',
            'password' => '1234567890'
        ]);


        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Login Successfully'
                 ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }


    public function testValidateUserWithInvalidCredentials()
    {
        $response = $this->postJson('/validate-user', [
            'user_name' => 'invalid_user',
            'password' => 'invalid_password'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Invalid credentials'
                 ]);

        $this->assertFalse(Auth::check());
    }
}
