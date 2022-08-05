<?php

namespace Tests\Feature;

use App\Models\OauthClient;
use App\Models\Technician;
use Tests\TestCase;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class TechnicianTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test validation response for required fields
     */
    public function testTechnicianRequiredFields()
    {
        $this->json('POST', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'first_name'   => ['The first name field is required.'],
                    'last_name'    => ['The last name field is required.'],
                    'truck_number' => ['The truck number field is required.'],
                ]
            ]);

        $technician = [
            'first_name'   => 'First Name 1',
            'last_name'    => 'Last Name @',
        ];
        $this->json('POST', 'api/technicians', $technician, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'first_name'   => ['The first name format is invalid.'],
                    'last_name'    => ['The last name format is invalid.'],
                ]
            ]);
    }

    /**
     * Test a technician was created successfully
     */
    public function testCreateTechnician()
    {
        $technician = [
            'first_name'   => 'First Name',
            'last_name'    => 'Last Name',
            'truck_number' => '1234'
        ];

        $this->json('POST', 'api/technicians', $technician, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technician created successfully.',
                'data'    => array(
                    'first_name'   => 'First Name',
                    'last_name'    => 'Last Name',
                    'truck_number' => '1234'
                )
            ])
        ;
    }

    /**
     * Test technicians get listed
     */
    public function testGetAll()
    {
        // Create two technicians using the technician factory
        $technicians = Technician::factory(2)->create();

        $this->json('GET', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technicians retrieved successfully.',
                'data'    => [
                    [
                        'id'           => $technicians[0]->id,
                        'first_name'   => $technicians[0]->first_name,
                        'last_name'    => $technicians[0]->last_name,
                        'truck_number' => $technicians[0]->truck_number
                    ],
                    [
                        'id'           => $technicians[1]->id,
                        'first_name'   => $technicians[1]->first_name,
                        'last_name'    => $technicians[1]->last_name,
                        'truck_number' => $technicians[1]->truck_number
                    ]
                ]
            ]);
    }

    /**
     * Test reading a particular technician
     */
    public function testFindOne()
    {
        // Create a technician using the technician factory
        $technicians = Technician::factory(2)->create();

        $this->json('GET', 'api/technicians/'. $technicians[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technician retrieved successfully.',
                'data' => [
                    'id'           => $technicians[1]->id,
                    'first_name'   => $technicians[1]->first_name,
                    'last_name'    => $technicians[1]->last_name,
                    'truck_number' => $technicians[1]->truck_number
                ]
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'truck_number',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;
    }

    /**
     * Test updating a particular technician
     */
    public function testUpdateTechnician()
    {
        // Create a technician using the technician factory
        $technicians = Technician::factory(2)->create();

        $this->json('GET', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technicians retrieved successfully.',
                'data'    => [
                    [
                        'id'           => $technicians[0]->id,
                        'first_name'   => $technicians[0]->first_name,
                        'last_name'    => $technicians[0]->last_name,
                        'truck_number' => $technicians[0]->truck_number
                    ],
                    [
                        'id'           => $technicians[1]->id,
                        'first_name'   => $technicians[1]->first_name,
                        'last_name'    => $technicians[1]->last_name,
                        'truck_number' => $technicians[1]->truck_number
                    ]
                ]
            ]);

        // Update the technician
        $technician = [
            'first_name'   => 'First Name',
            'last_name'    => 'last name',
            'truck_number' => 'No 230',
        ];

        $this->json('PUT', 'api/technicians/' . $technicians[1]->id, $technician, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technician updated successfully.',
                'data'    => array(
                    'first_name'   => $technician['first_name'],
                    'last_name'    => $technician['last_name'],
                    'truck_number' => $technician['truck_number']
                )
            ])
        ;
    }

    /**
     * Test can delete a technician
     */
    public function testDeleteTechnician()
    {
        // Create a technicians using the technician factory
        $technicians = Technician::factory(2)->create();

        $this->json('GET', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technicians retrieved successfully.',
                'data'    => [
                    [
                        'id'           => $technicians[0]->id,
                        'first_name'   => $technicians[0]->first_name,
                        'last_name'    => $technicians[0]->last_name,
                        'truck_number' => $technicians[0]->truck_number
                    ],
                    [
                        'id'           => $technicians[1]->id,
                        'first_name'   => $technicians[1]->first_name,
                        'last_name'    => $technicians[1]->last_name,
                        'truck_number' => $technicians[1]->truck_number
                    ]
                ]
            ]);

        $this->json('DELETE', 'api/technicians/' . $technicians[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technician deleted successfully.',
                'data'    => [
                    'id'           => $technicians[1]->id,
                    'first_name'   => $technicians[1]->first_name,
                    'last_name'    => $technicians[1]->last_name,
                    'truck_number' => $technicians[1]->truck_number
                    ]
                ]);

        // Make sure the technician was deleted
        $this->json('GET', 'api/technicians/'. $technicians[1]->id, ['Accept' => 'application/json'])
            ->assertStatus(404);

        $this->json('GET', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Technicians retrieved successfully.',
                'data'    => [
                    [
                        'id'           => $technicians[0]->id,
                        'first_name'   => $technicians[0]->first_name,
                        'last_name'    => $technicians[0]->last_name,
                        'truck_number' => $technicians[0]->truck_number
                    ]
                ]
            ]);
    }
}
