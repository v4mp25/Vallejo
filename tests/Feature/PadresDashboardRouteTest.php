<?php

namespace Tests\Feature;

use Tests\TestCase;

class PadresDashboardRouteTest extends TestCase
{
    public function test_padres_dashboard_route_is_registered(): void
    {
        $route = $this->app['router']->getRoutes()->getByName('padres.dashboard');

        $this->assertNotNull($route);
        $this->assertSame('/padres/dashboard', $route->uri());
    }
}
