<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;

class UnpublishEventTest extends TestCase
{
    /**
     * @test
     */
    function a_guest_cannot_unpublish_events()
    {
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()]);

        $response = $this->json('delete', 'kitchen/events/published-events', [
            'event_id' => $event->id
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    function a_chef_can_unpublish_events()
    {
        $this->withoutExceptionHandling();

        $chef = $this->makeUser();
        $event = factory(Event::class)->create([
            'owner_id' => $chef->id,
            'enabled' => true
        ]);
        
        $response = $this->actingAs($chef)->json('post', 'kitchen/events/unpublished-events', [
            'event_id' => $event->id
        ]);

        $response->assertStatus(200);
        $this->assertFalse($event->fresh()->enabled);
    }
}
