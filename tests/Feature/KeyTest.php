<?php

namespace Tests\Feature;

use App\Models\OauthClient;
use App\Models\Key;
use App\Models\Vehicle;
use Tests\TestCase;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class KeyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test validation response for required fields
     */
    public function testKeyRequiredFields()
    {
        $this->json('POST', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'vehicle_id'  => ['The vehicle id field is required.'],
                    'name'        => ['The name field is required.'],
                    'description' => ['The description field is required.'],
                    'price'       => ['The price field is required.']
                ]
            ]);
    }

    /**
     * Test a key was created successfully
     */
    public function testCreateVehicle()
    {
        $vehicle = Vehicle::factory()->create();
        $key = [
            'vehicle_id'  => $vehicle->id,
            'name'        => 'Key Name',
            'description' => 'Test Key',
            'price'       => '5.99'
        ];

        $this->json('POST', 'api/keys', $key, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Key created successfully.',
                'data'    => array(
                    'vehicle_id'  => $vehicle->id,
                    'name'        => $key['name'],
                    'description' => $key['description'],
                    'price'       => $key['price']
                )
            ])
        ;
    }

    /**
     * Test keys get listed
     */
    public function testGetAll()
    {
        $vehicle = Vehicle::factory()->create();
        // Create two keys using the key factory
        $keys = Key::factory(2)->create(['vehicle_id' => $vehicle->id]);

        $this->json('GET', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Keys retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'  => $vehicle->id,
                        'name'        => $keys[0]->name,
                        'description' => $keys[0]->description,
                        'price'       => $keys[0]->price
                    ],
                    [
                        'vehicle_id'  => $vehicle->id,
                        'name'        => $keys[1]->name,
                        'description' => $keys[1]->description,
                        'price'       => $keys[1]->price
                    ]
                ]
            ]);
    }

    /**
     * Test reading a particular key
     */
    public function testFindOne()
    {
        $vehicle = Vehicle::factory()->create();
        // Create two keys using the key factory
        $keys = Key::factory(2)->create(['vehicle_id' => $vehicle->id]);

        $this->json('GET', 'api/keys/'. $keys[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Key retrieved successfully.',
                'data' => [
                    'vehicle_id'  => $vehicle->id,
                    'name'        => $keys[1]->name,
                    'description' => $keys[1]->description,
                    'price'       => $keys[1]->price
                ]
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'vehicle_id',
                    'name',
                    'description',
                    'price',
                ]
            ]);
    }

    /**
     * Test updating a particular key
     */
    public function testUpdateKey()
    {
        // Create a Vehicle using the Vehicle factory
        $vehicle = Vehicle::factory()->create();
        // Create two keys using the key factory
        $keys = Key::factory(2)->create(['vehicle_id' => $vehicle->id]);

        $this->json('GET', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Keys retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'  => $vehicle->id,
                        'name'        => $keys[0]->name,
                        'description' => $keys[0]->description,
                        'price'       => $keys[0]->price
                    ],
                    [
                        'vehicle_id'  => $vehicle->id,
                        'name'        => $keys[1]->name,
                        'description' => $keys[1]->description,
                        'price'       => $keys[1]->price
                    ]
                ]
            ]);

        // Update the key
        $key = [
            'name'        => 'Updated Key',
            'description' => 'Updated Key',
            'price'       => '2.50'
        ];

        $this->json('PUT', 'api/keys/' . $keys[1]->id, $key, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Key updated successfully.',
                'data'    => array(
                    'vehicle_id'  => $vehicle->id,
                    'name'        => 'Updated Key',
                    'description' => 'Updated Key',
                    'price'       => '2.50'
                )
            ]);
    }

    /**
     * Test can delete a key
     */
    public function testDeleteKey()
    {
        $vehicle = Vehicle::factory()->create();
        // Create two keys using the key factory
        $keys = Key::factory(2)->create(['vehicle_id' => $vehicle->id]);

        $this->json('GET', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Keys retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'  => $vehicle->id,
                        'name'        => $keys[0]->name,
                        'description' => $keys[0]->description,
                        'price'       => $keys[0]->price
                    ],
                    [
                        'vehicle_id'  => $vehicle->id,
                        'name'        => $keys[1]->name,
                        'description' => $keys[1]->description,
                        'price'       => $keys[1]->price
                    ]
                ]
            ]);

        $this->json('DELETE', 'api/keys/' . $keys[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Key deleted successfully.',
                'data'    => [
                    'vehicle_id'  => $vehicle->id,
                    'name'        => $keys[1]->name,
                    'description' => $keys[1]->description,
                    'price'       => $keys[1]->price
                    ]
                ]);

        // Make sure the key was deleted
        $this->json('GET', 'api/keys/'. $keys[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(404);

        $this->json('GET', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Keys retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'  => $vehicle->id,
                        'name'        => $keys[0]->name,
                        'description' => $keys[0]->description,
                        'price'       => $keys[0]->price
                    ]
                ]
            ]);
    }
}
