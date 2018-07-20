<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Mail;

class ApproveReservationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Mail::fake();
    }

    /**
     * @test
     */
    function cocinero_can_approve_reservation()
    {
        $this->withoutExceptionHandling();

        $cocinero = $this->makeUser();
        $event = factory(Event::class)->create([
            'owner_id' => $cocinero->id
        ]);

        // hacemos la reservacion
        $comensal = $this->makeUser();
        $reservation = $event->reservations()->create([
            'user_id' => $comensal->id,
            'approved' => false
        ]);

        // confirmamos
        $response = $this->actingAs($cocinero)->json('post', 'kitchen/approved-reservations', [
            'reservation_id' => $reservation->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'user_id' => $comensal->id,
            'event_id' => $event->id,
            'paid' => false,
            'approved' => true
        ]);
    }
}
