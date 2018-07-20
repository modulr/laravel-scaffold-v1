<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;

class CancelReservationTest extends TestCase
{
    /**
     * @test
     */
    function user_can_cancel_reservation()
    {
        $this->withoutExceptionHandling();

        $user = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $user->id]);

        $comensal = $this->makeUser();
        $reservation = $event->reservations()->create([
            'user_id' => $comensal->id,
        ]);

        $response = $this->actingAs($comensal)->json('delete', "reservations/{$reservation->id}");

        $response->assertStatus(200);
        $response->assertJson([]);

        $this->assertCount(0, $event->fresh()->reservations);
    }
}
