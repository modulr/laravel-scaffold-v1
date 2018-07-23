<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use App\Models\Events\Event;

class UploadImageTest extends TestCase
{
    /**
     * @test
     */
    function a_guest_cannot_upload_a_image_to_events()
    {
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);

        $response = $this->json('post', 'kitchen/events/' . $event->id . '/images', [
            'file' => UploadedFile::fake()->image('mi_image.jpg')
        ]);

        $response->assertStatus(401);
    }
    
    /**
     * @test
     */
    function a_chef_can_upload_images()
    {
        $this->withoutExceptionHandling();
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $chef->id]);
        
        $response = $this->actingAs($chef)->json('post', 'kitchen/events/' . $event->id . '/images', [
            'file' => UploadedFile::fake()->image('mi_image.jpg')
        ]);
    
        $response->assertStatus(201);
    }

    /**
     * @test
     */
    function a_chef_cannot_upload_images_to_events_that_doesnot_owns()
    {
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);
        $chef = $this->makeUser();
        
        $response = $this->actingAs($chef)->json('post', 'kitchen/events/' . $event->id . '/images', [
            'file' => UploadedFile::fake()->image('mi_image.jpg')
        ]);
    
        $response->assertStatus(404);
    }
}
