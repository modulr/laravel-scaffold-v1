<?php

namespace Tests\Feature\Kitchen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Events\Event;

class SortImageTest extends TestCase
{
    /**
     * @test
     */
    function a_guest_cannot_order_event_images()
    {
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()->id]);
        $event->images()->createMany([
            ['basename' => 'foo.jpg', 'order' => 1 ],
            ['basename' => 'bar.jpg', 'order' => 2 ],
        ]);

        $response = $this->json('post', 'kitchen/events/' . $event->id . '/images/sort', [
            'images' => [
                ['id' => 2],
                ['id' => 1],
            ]
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    function a_chef_can_reorder_event_images()
    {
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $chef->id]);
        $event->images()->createMany([
            ['basename' => 'foo.jpg', 'order' => 1 ],
            ['basename' => 'bar.jpg', 'order' => 2 ],
        ]);

        $response = $this->actingAs($chef)->json('post', 'kitchen/events/' . $event->id . '/images/sort', [
            'images' => [
                ['id' => 2],
                ['id' => 1],
            ]
        ]);

        $response->assertStatus(200);

        $imagesResponse = $response->json();
        $this->assertCount(2, $imagesResponse);
    }

    // Testear que el cheff no pueda ordenar imagenes de un evento que no posea
    /**
     * @test
     */
    function a_chef_cannot_reorder_images_of_a_event_that_doesnot_owns()
    {
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $this->makeUser()]);
        $event->images()->createMany([
            ['basename' => 'foo.jpg', 'order' => 1 ],
            ['basename' => 'bar.jpg', 'order' => 2 ],
        ]);

        $response = $this->actingAs($chef)->json('post', 'kitchen/events/' . $event->id . '/images/sort', [
            'images' => [
                ['id' => 2],
                ['id' => 1],
            ]
        ]);

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    function a_chef_cannot_reorder_images_that_doesnot_owns()
    {
        $chef = $this->makeUser();
        $event = factory(Event::class)->create(['owner_id' => $chef->id]);
        $event->images()->createMany([
            ['basename' => 'foo.jpg', 'order' => 1 ],
            ['basename' => 'bar.jpg', 'order' => 2 ],
        ]);

        $response = $this->actingAs($chef)->json('post', 'kitchen/events/' . $event->id . '/images/sort', [
            'images' => [
                ['id' => 2],
                ['id' => 36],
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }
}
