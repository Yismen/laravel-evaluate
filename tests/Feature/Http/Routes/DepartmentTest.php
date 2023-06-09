<?php

namespace Dainsys\Evaluate\Feature\Http\Routes;

use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function departments_index_route_requires_authentication()
    {
        $response = $this->get(route('evaluate.admin.departments.index'));

        $response->assertRedirect(route('login'));
    }
}
