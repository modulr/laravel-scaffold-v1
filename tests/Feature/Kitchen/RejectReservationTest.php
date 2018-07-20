<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use App\Models\Events\Event;

class RejectReservationTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        Mail::fake();
    }

    /**
     * @test
     */
    function cocinero_can_reject_reservation()
    {
        $this->withoutExceptionHandling();

        $cocinero = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $cocinero->id]);

        $comensal = $this->makeUser();
        $reservation = $event->reservations()->create([
            'user_id' => $comensal->id,
        ]);

        // Cancelo la orden como cocinero
        $response = $this->actingAs($cocinero)->json('delete', "kitchen/reservations/{$reservation->id}");

        $response->assertStatus(200);
        $response->assertJson([]);

        $this->assertCount(0, $event->fresh()->reservations);
    }
}
