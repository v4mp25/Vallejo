<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_smoke(): void
    {
        $this->assertTrue(true);
    }
}
