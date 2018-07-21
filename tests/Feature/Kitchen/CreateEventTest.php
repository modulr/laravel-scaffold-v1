<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class CreateEventTest extends TestCase
{

    public function validEvent($overrides = [])
    {
        return array_merge([
            'name' => 'Plato 1',
            'description' => 'My plato 1',
            'place' => 'Mexico city',
            'date' => Carbon::now()->toDateString(),
            'start_time' => '18:00:00',
            'end_time' => '21:00:00',
            'content' => 'My content',
            'price' => 44.50,
            'attending_limit' => 6
        ], $overrides);
    }

    /**
     * @test
     */
    function a_guest_cannot_create_a_event()
    {
        $response = $this->json('post', 'kitchen/events', []);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    function a_cheff_can_create_a_event()
    {
        $this->withoutExceptionHandling();

        $cheff = $this->makeUser();
        $eventParams = $this->validEvent();

        $response = $this->actingAs($cheff)->json('post', 'kitchen/events', $eventParams);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => $eventParams['name']
        ]);
    }
}
