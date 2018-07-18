<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;

class ReadEventsTest extends TestCase
{
    /**
     * @test
     */
    function a_guest_can_view_events()
    {
        $this->withoutExceptionHandling();

        factory(Event::class, 5)->create(['owner_id' => $this->makeUser()->id]);

        $response = $this->json('get', 'events');

        $response
            ->assertStatus(200)
            ->assertJson(['total' => 5]);
    }

    /**
     * @test
     */
    function a_guest_have_not_events()
    {
        $response = $this->json('get', 'user/events');

        $response
            ->assertStatus(401);
    }
}
