<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_route_returns_success(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
