<?php

namespace Tests\Feature;

use App\Models\Key;
use App\Models\OauthClient;
use App\Models\Order;
use App\Models\Technician;
use App\Models\Vehicle;
use Tests\TestCase;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test validation response for required fields
     */
    public function testOrderRequiredFields()
    {
        $this->json('POST', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'vehicle_id'    => ['The vehicle id field is required.'],
                    'key_id'        => ['The key id field is required.'],
                    'technician_id' => ['The technician id field is required.'],
                    'status'        => ['The status field is required.']
                ]
            ]);

        $order = [
            'vehicle_id'    => 1,
            'key_id'        => 1,
            'technician_id' => 1,
            'status'        => Order::STATUSES['PENDING'],
            'note'          => 'this order is not valid'
        ];

        $this->json('POST', 'api/orders', $order, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'vehicle_id'    => ['The selected vehicle id is invalid.'],
                    'key_id'        => ['The selected key id is invalid.'],
                    'technician_id' => ['The selected technician id is invalid.'],
                ]
            ]);
    }

    /**
     * Test a order was created successfully
     */
    public function testCreateOrder()
    {
        $technicians = Technician::factory(2)->create();

        $vehicles = Vehicle::factory(2)->create();
        $key0 = Key::factory()->create(['vehicle_id' => $vehicles[0]->id]);
        $key1 = Key::factory()->create(['vehicle_id' => $vehicles[1]->id]);

        $order = [
            'vehicle_id'    => $vehicles[0]->id,
            'key_id'        => $key0->id,
            'technician_id' => $technicians[0]->id,
            'status'        => Order::STATUSES['PENDING']
        ];

        $this->json('POST', 'api/orders', $order, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Order created successfully.',
                'data'    => array(
                    'vehicle_id'    => $vehicles[0]->id,
                    'key_id'        => $key0->id,
                    'technician_id' => $technicians[0]->id,
                    'status'        => Order::STATUSES['PENDING']
                )
            ]);
    }

    /**
     * Test orders get listed
     */
    public function testGetAll()
    {
        $technicians = Technician::factory(2)->create();

        $vehicles = Vehicle::factory(2)->create();
        $key0 = Key::factory()->create(['vehicle_id' => $vehicles[0]->id]);
        $key1 = Key::factory()->create(['vehicle_id' => $vehicles[1]->id]);
        // Create two orders using the order factory
        $order0 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[0]->id,
                'key_id'        => $key0->id,
                'technician_id' => $technicians[0]->id
            ]
        );
        $order1 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[1]->id,
                'key_id'        => $key1->id,
                'technician_id' => $technicians[1]->id
            ]
        );

        $this->json('GET', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'    => $order0->vehicle_id,
                        'key_id'        => $order0->key_id,
                        'technician_id' => $order0->technician_id
                    ],
                    [
                        'vehicle_id'    => $order1->vehicle_id,
                        'key_id'        => $order1->key_id,
                        'technician_id' => $order1->technician_id
                    ]
                ]
            ]);
    }

    /**
     * Test reading a particular order
     */
    public function testFindOne()
    {
        $technicians = Technician::factory(2)->create();

        $vehicles = Vehicle::factory(2)->create();
        $key0 = Key::factory()->create(['vehicle_id' => $vehicles[0]->id]);
        $key1 = Key::factory()->create(['vehicle_id' => $vehicles[1]->id]);
        // Create two orders using the order factory
        $order0 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[0]->id,
                'key_id'        => $key0->id,
                'technician_id' => $technicians[0]->id
            ]
        );
        $order1 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[1]->id,
                'key_id'        => $key1->id,
                'technician_id' => $technicians[1]->id
            ]
        );

        $this->json('GET', 'api/orders/'. $order1->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Order retrieved successfully.',
                'data' => [
                    'vehicle_id'    => $order1->vehicle_id,
                    'key_id'        => $order1->key_id,
                    'technician_id' => $order1->technician_id
                ]
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'vehicle_id',
                    'key_id',
                    'technician_id',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;
    }

    /**
     * Test updating a particular order
     */
    public function testUpdateOrder()
    {
        $technicians = Technician::factory(2)->create();

        $vehicles = Vehicle::factory(3)->create();
        $key0 = Key::factory()->create(['vehicle_id' => $vehicles[0]->id]);
        $key1 = Key::factory()->create(['vehicle_id' => $vehicles[1]->id]);
        $key2 = Key::factory()->create(['vehicle_id' => $vehicles[2]->id]);
        // Create two orders using the order factory
        $order0 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[0]->id,
                'key_id'        => $key0->id,
                'technician_id' => $technicians[0]->id
            ]
        );
        $order1 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[1]->id,
                'key_id'        => $key1->id,
                'technician_id' => $technicians[1]->id
            ]
        );

        $this->json('GET', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'    => $order0->vehicle_id,
                        'key_id'        => $order0->key_id,
                        'technician_id' => $order0->technician_id
                    ],
                    [
                        'vehicle_id'    => $order1->vehicle_id,
                        'key_id'        => $order1->key_id,
                        'technician_id' => $order1->technician_id
                    ]
                ]
            ]);

        // Update the order
        $update = [
            'vehicle_id'    => $vehicles[2]->id,
            'key_id'        => $key2->id,
            'status'        => Order::STATUSES['APPROVED']
        ];

        $this->json('PUT', 'api/orders/' . $order1->id, $update, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Order updated successfully.',
                'data'    => array(
                    'vehicle_id'    => $vehicles[2]->id,
                    'key_id'        => $key2->id,
                    'status'        => Order::STATUSES['APPROVED']
                )
            ])
        ;
    }

    /**
     * Test can delete a order
     */
    public function testDeleteOrder()
    {
        $technicians = Technician::factory(2)->create();

        $vehicles = Vehicle::factory(2)->create();
        $key0 = Key::factory()->create(['vehicle_id' => $vehicles[0]->id]);
        $key1 = Key::factory()->create(['vehicle_id' => $vehicles[1]->id]);
        // Create two orders using the order factory
        $order0 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[0]->id,
                'key_id'        => $key0->id,
                'technician_id' => $technicians[0]->id
            ]
        );
        $order1 = Order::factory()->create(
            [
                'vehicle_id'    => $vehicles[1]->id,
                'key_id'        => $key1->id,
                'technician_id' => $technicians[1]->id
            ]
        );

        $this->json('GET', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'    => $order0->vehicle_id,
                        'key_id'        => $order0->key_id,
                        'technician_id' => $order0->technician_id
                    ],
                    [
                        'vehicle_id'    => $order1->vehicle_id,
                        'key_id'        => $order1->key_id,
                        'technician_id' => $order1->technician_id
                    ]
                ]
            ]);

        $this->json('DELETE', 'api/orders/' . $order1->id, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Order deleted successfully.',
                'data'    => [
                    'vehicle_id'    => $order1->vehicle_id,
                    'key_id'        => $order1->key_id,
                    'technician_id' => $order1->technician_id
                    ]
                ]);

        // Make sure the order was deleted
        $this->json('GET', 'api/orders/'. $order1->id, ['Accept' => 'application/json'])
            ->assertStatus(404);

        $this->json('GET', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'    => $order0->vehicle_id,
                        'key_id'        => $order0->key_id,
                        'technician_id' => $order0->technician_id
                    ]
                ]
            ]);
    }
}
