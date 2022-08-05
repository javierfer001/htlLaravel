<?php

namespace Tests\Feature;

use App\Models\OauthClient;
use App\Models\Vehicle;
use Tests\TestCase;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class VehicleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test validation response for required fields
     */
    public function testVehicleRequiredFields()
    {
        $this->json('POST', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'year'  => ['The year field is required.'],
                    'make'  => ['The make field is required.'],
                    'model' => ['The model field is required.'],
                    'vin'   => ['The vin field is required.']
                ]
            ])
        ;
    }

    /**
     * Test a vehicle was created successfully
     */
    public function testCreateVehicle()
    {
        $vehicle = [
            'year'  => '2022',
            'make'  => 'Jetta',
            'model' => 'VW',
            'vin'   => 'SCCDC0826XHA15728'
        ];

        $this->json('POST', 'api/vehicles', $vehicle, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle created successfully.',
                'data'    => array(
                    'year'  => '2022',
                    'make'  => 'Jetta',
                    'model' => 'VW',
                    'vin'   => 'SCCDC0826XHA15728'
                )
            ])
        ;
    }

    /**
     * Test vehicles get listed
     */
    public function testGetAll()
    {
        // Create two vehicles using the vehicle factory
        $vehicles = Vehicle::factory(2)->create();

        $this->json('GET', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicles retrieved successfully.',
                'data'    => [
                    [
                        'id'    => $vehicles[0]->id,
                        'year'  => $vehicles[0]->year,
                        'make'  => $vehicles[0]->make,
                        'model' => $vehicles[0]->model,
                        'vin'   => $vehicles[0]->vin
                    ],
                    [
                        'id'    => $vehicles[1]->id,
                        'year'  => $vehicles[1]->year,
                        'make'  => $vehicles[1]->make,
                        'model' => $vehicles[1]->model,
                        'vin'   => $vehicles[1]->vin
                    ]
                ]
            ]);
    }

    /**
     * Test reading a particular vehicle
     */
    public function testFindOne()
    {
        // Create a vehicle using the vehicle factory
        $vehicles = Vehicle::factory(2)->create();

        $this->json('GET', 'api/vehicles/'. $vehicles[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle retrieved successfully.',
                'data' => [
                    'id'    => $vehicles[1]->id,
                    'year'  => $vehicles[1]->year,
                    'make'  => $vehicles[1]->make,
                    'model' => $vehicles[1]->model,
                    'vin'   => $vehicles[1]->vin
                ]
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'year',
                    'make',
                    'model',
                    'vin',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;
    }

    /**
     * Test updating a particular vehicle
     */
    public function testUpdateVehicle()
    {
        // Create a vehicle using the vehicle factory
        $vehicles = Vehicle::factory(2)->create();

        $this->json('GET', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicles retrieved successfully.',
                'data'    => [
                    [
                        'id'    => $vehicles[0]->id,
                        'year'  => $vehicles[0]->year,
                        'make'  => $vehicles[0]->make,
                        'model' => $vehicles[0]->model,
                        'vin'   => $vehicles[0]->vin
                    ],
                    [
                        'id'    => $vehicles[1]->id,
                        'year'  => $vehicles[1]->year,
                        'make'  => $vehicles[1]->make,
                        'model' => $vehicles[1]->model,
                        'vin'   => $vehicles[1]->vin
                    ]
                ]
            ]);

        // Update the vehicle
        $vehicle = [
            'year'  => '2022',
            'make'  => 'Jetta',
            'model' => 'VW',
            'vin'   => 'SCCDC0826XHA15727'
        ];

        $this->json('PUT', 'api/vehicles/' . $vehicles[1]->id, $vehicle, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle updated successfully.',
                'data'    => array(
                    'year'  => '2022',
                    'make'  => 'Jetta',
                    'model' => 'VW',
                    'vin'   => 'SCCDC0826XHA15727'
                )
            ])
        ;
    }

    /**
     * Test can delete a vehicle
     */
    public function testDeleteVehicle()
    {
        // Create a vehicles using the vehicle factory
        $vehicles = Vehicle::factory(2)->create();

        $this->json('GET', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicles retrieved successfully.',
                'data'    => [
                    [
                        'id'    => $vehicles[0]->id,
                        'year'  => $vehicles[0]->year,
                        'make'  => $vehicles[0]->make,
                        'model' => $vehicles[0]->model,
                        'vin'   => $vehicles[0]->vin
                    ],
                    [
                        'id'    => $vehicles[1]->id,
                        'year'  => $vehicles[1]->year,
                        'make'  => $vehicles[1]->make,
                        'model' => $vehicles[1]->model,
                        'vin'   => $vehicles[1]->vin
                    ]
                ]
            ]);

        $this->json('DELETE', 'api/vehicles/' . $vehicles[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle deleted successfully.',
                'data'    => [
                    'id'    => $vehicles[1]->id,
                    'year'  => $vehicles[1]->year,
                    'make'  => $vehicles[1]->make,
                    'model' => $vehicles[1]->model,
                    'vin'   => $vehicles[1]->vin]
                ]);

        // Make sure the vehicle was deleted
        $this->json('GET', 'api/vehicles/'. $vehicles[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(404);

        $this->json('GET', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicles retrieved successfully.',
                'data'    => [
                    [
                        'id'    => $vehicles[0]->id,
                        'year'  => $vehicles[0]->year,
                        'make'  => $vehicles[0]->make,
                        'model' => $vehicles[0]->model,
                        'vin'   => $vehicles[0]->vin
                    ]
                ]
            ]);
    }
}
