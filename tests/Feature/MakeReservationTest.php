<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MakeReservationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Mail::fake();
    }

    /**
     * @test
     */
    function user_can_make_a_reservation()
    {
        $this->withoutExceptionHandling();

        $user = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $user->id]);

        $comensal = $this->makeUser();

        $response = $this->actingAs($comensal)->json('post', 'reservations', [
            'event_id' => $event->id
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'user_id' => $comensal->id,
                'event_id' => $event->id,
                'paid' => false
            ]);
    }
}
