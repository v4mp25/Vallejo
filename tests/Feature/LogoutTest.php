<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_logout_redirects_to_login_and_clears_auth(): void
    {
        $user = new User([
            'nombres' => 'Ana',
            'apellidos' => 'Torres',
            'rol' => 'profesor',
            'password' => bcrypt('secret123'),
        ]);
        $user->id = 1;

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
