<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Events\Event;

class UploadTempImageTest extends TestCase
{

    /**
     * @test
     */
    function a_guest_cannot_upload_a_temp_image()
    {
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);
        $response = $this->json('post', 'kitchen/events/' . $event->id . '/images/temp', [
            'file' => UploadedFile::fake()->image('temp_image.jpg')
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    function a_chef_can_upload_temp_image()
    {
        $this->withoutExceptionHandling();
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $chef->id]);

        $response = $this->actingAs($chef)->json('post', "kitchen/events/{$event->id}/images/temp", [
            'file' => UploadedFile::fake()->image('temp_image.jpg')
        ]);

        $response->assertStatus(201);
        $response->assertJson(['name' => 'temp_image.jpg']);
    }

    /**
     * @test
     */
    function a_chef_cannot_upload_image_to_event_that_doesnot_owns()
    {
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);

        $response = $this->actingAs($chef)->json('post', "kitchen/events/{$event->id}/images/temp", [
            'file' => UploadedFile::fake()->image('temp_image.jpg')
        ]);

        $response->assertStatus(404);
    }
}
