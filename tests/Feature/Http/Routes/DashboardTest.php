<?php

namespace Dainsys\Evaluate\Feature\Http\Routes;

use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_route_requires_authentication()
    {
        $response = $this->get(route('evaluate.home'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_route_requires_authentication()
    {
        $response = $this->get(route('evaluate.admin'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function dashboard_route_requires_authentication()
    {
        $response = $this->get(route('evaluate.my_tickets'));

        $response->assertRedirect(route('login'));
    }
}
