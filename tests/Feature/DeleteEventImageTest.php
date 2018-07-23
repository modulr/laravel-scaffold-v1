<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;

class DeleteEventImageTest extends TestCase
{
    /**
     * @test
     */
    function a_guest_cannot_delete_a_event_image()
    {
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()]);
        $image = $event->images()->create([
            'basename' => 'foo.jpg',
            'order' => 1
        ]);
        
        $response = $this->json('delete', "kitchen/events/{$event->id}/images/{$image->id}");
        
        $response->assertStatus(401);
    }
    
    /**
     * @test
     */
    function a_chef_can_delete_event_images()
    {
        $this->withoutExceptionHandling();
        
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $chef->id]);
        $image = $event->images()->create([
            'basename' => 'foo.jpg',
            'order' => 1
        ]);

        $response = $this->actingAs($chef)->json('delete', "kitchen/events/{$event->id}/images/{$image->id}");

        $response->assertStatus(204);
        $this->assertCount(0, $event->fresh()->images);
    }

    /**
     * @test
     */
    function must_own_the_event()
    {
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $chef->id]);
        $anotherEvent = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);

        $image = $anotherEvent->images()->create([
            'basename' => 'foo.jpg',
            'order' => 1
        ]);

        $response = $this->actingAs($chef)->json('delete', "kitchen/events/{$anotherEvent->id}/images/{$image->id}");

        $response->assertStatus(404);
    }
    
    /**
     * @test
     */
    function images_must_belong_to_the_event()
    {
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $chef->id]);
        $event->images()->create([
            'basename' => 'foo.jpg',
            'order' => 1
        ]);


        $anotherEvent = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);    
        $image = $anotherEvent->images()->create([
            'basename' => 'bar.jpg',
            'order' => 1
        ]);
    
        $response = $this->actingAs($chef)->json('delete', "kitchen/events/{$event->id}/images/{$image->id}");
    
        $response->assertStatus(404);
        
    }
}
