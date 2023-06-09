<?php

namespace Dainsys\Evaluate\Tests\Feature\Models;

use Dainsys\Evaluate\Models\Client;
use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function model_interacts_with_db_table()
    {
        $data = Client::factory()->make();

        Client::create($data->toArray());

        $this->assertDatabaseHas(Client::class, $data->only([
            'name', 'description', 'status'
        ]));
    }

    /** @test */
    // public function model_belongs_to_one_user()
    // {
    //     $department_role = Client::factory()->create();

    //     $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $department_role->user());
    // }

    // /** @test */
    // public function model_belongs_to_one_department()
    // {
    //     $subject = Client::factory()->create();

    //     $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $subject->department());
    // }
}
