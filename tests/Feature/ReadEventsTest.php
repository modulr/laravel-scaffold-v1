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
    function a_user_can_view_their_events_reserved()
    {
        $this->withoutExceptionHandling();

        $comensal = $this->makeUser();
        $events = factory(Event::class, 5)->create(['owner_id' => $this->makeUser()->id]);
        
        $events[0]->reserveFor($comensal);
        $events[1]->reserveFor($comensal);

        $response = $this->actingAs($comensal)->json('get', 'events/reserved');

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 2
        ]);
    }

    /**
     * @test
     */
    function a_user_can_read_a_event()
    {
        $this->withoutExceptionHandling();

        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);

        $response = $this->json('get', "events/{$event->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'name' => $event->name
        ]);
    }
}
