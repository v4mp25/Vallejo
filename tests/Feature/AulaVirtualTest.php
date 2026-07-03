<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AulaVirtualTest extends TestCase
{
    use RefreshDatabase;

    public function test_profesor_can_view_aula_virtual_page(): void
    {
        $user = new User([
            'nombres' => 'Marco',
            'apellidos' => 'Pérez',
            'rol' => 'profesor',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($user);

        $response = $this->get('/profesor/aula-virtual');

        $response->assertStatus(200);
    }

    public function test_alumno_can_view_aula_virtual_page(): void
    {
        $user = new User([
            'nombres' => 'Carmen',
            'apellidos' => 'Lopez',
            'rol' => 'alumno',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($user);

        $response = $this->get('/aula-virtual');

        $response->assertStatus(200);
    }
}
