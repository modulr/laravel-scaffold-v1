<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;
use Carbon\Carbon;

class UpdateEventTest extends TestCase
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
    function a_guest_cannot_update_a_event()
    {
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()]);

        $response = $this->json('put', 'kitchen/events/' . $event->id, $this->validEvent());

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    function a_cheff_can_update_their_own_event()
    {
        $this->withoutExceptionHandling();

        $cheff = $this->makeUser();
        $eventParams = $this->validEvent(['owner_id' => $cheff->id]);
        $event = factory(Event::class)->create($eventParams);

        $response = $this->actingAs($cheff)->json('put', 'kitchen/events/' . $event->id, array_merge($eventParams,[
            'name' => 'nuevo nombre',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'nuevo nombre',
            'id' => $event->id,
        ]);
    }

    /**
     * @test
     */
    function a_cheff_cannot_update_events_that_does_not_owns()
    {
        $eventParams = $this->validEvent(['owner_id' => $this->makeUser()]);
        $event = factory(Event::class)->create($eventParams);

        $anotherCheff = $this->makeUser();

        $response = $this->actingAs($anotherCheff)->json('put', 'kitchen/events/' . $event->id, array_merge($eventParams,[
            'name' => 'nuevo nombre',
        ]));

        $response->assertStatus(404);
    }
}
