<?php

namespace Tests\Feature\Kitchen;

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
    function a_guest_can_not_view_events()
    {
        $response = $this->json('get', 'kitchen/events');

        $response
            ->assertStatus(401);
    }

    /**
     * @test
     */
    function a_chef_can_view_their_own_events()
    {
        $this->withoutExceptionHandling();

        $chef = $this->makeUser();
        factory(Event::class, 5)->create(['owner_id' => $chef->id]);
        factory(Event::class, 3)->create(['owner_id' => $this->makeUser()->id]);

        $response = $this->actingAs($chef)->json('get', 'kitchen/events');

        $response
            ->assertStatus(200)
            ->assertJson(['total' => 5]);   
    }

    /**
     * @test
     */
    function a_chef_can_view_their_event_reserved_by_clients()
    {
        $this->withoutExceptionHandling();

        $client = $this->makeUser();
        $chef = $this->makeUser();
        $events = factory(Event::class, 3)->create(['owner_id' => $chef->id]);

        $events[0]->reserveFor($client);
        $events[2]->reserveFor($client);


        $response = $this->actingAs($chef)->json('get', 'kitchen/events/reserved');

        $response->assertJson([
            'total' => 2
        ]);
    }
}
