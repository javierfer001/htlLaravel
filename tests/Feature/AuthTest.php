<?php

namespace Tests\Feature;

use App\Models\OauthClient;
use Tests\TestCase;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test validation response for required fields
     */
    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/auth/register', ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson([
                "success" =>  false,
                "message" => [
                    "name"=> [
                        "The name field is required."
                    ],
                    "email"=> [
                        "The email field is required."
                    ],
                    "password"=> [
                        "The password field is required."
                    ],
                    "confirmed" => [
                        "The confirmed field is required."
                    ]
                ]
            ]);
    }

    /*
     * Test the password and confirmed matching validation
     */
    public function testConfirmedPassword()
    {
        $registration = [
            'name'      => 'John Doe',
            'email'     => 'doe@email.com',
            'password'  => '123456',
            'confirmed' => 'asdfg'
        ];

        $this->json('POST', 'api/auth/register', $registration, ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => [
                        'confirmed' => ['The confirmed and password must match.']
                    ]
            ]);
    }

    /*
     * Test creating a successful registration
     */
    public function testSuccessfulRegistration()
    {
        $registration = [
            'name'      => 'John Doe',
            'email'     => 'doe@email.com',
            'password'  => '123456',
            'confirmed' => '123456'
        ];

        $this->json('POST', 'api/auth/register', $registration, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'name',
                    'email'
                ]
            ]);
    }

    /**
     * Test login validations
     */
    public function testLoginMustEnterRequiredFields()
    {
        $this->json('POST', 'oauth/token')
            ->assertStatus(400)
            ->assertJson([
                'error'             => 'unsupported_grant_type',
                'error_description' => 'The authorization grant type is not ' .
                    'supported by the authorization server.',
                'hint'              => 'Check that all required parameters ' .
                    'have been provided',
                'message'           => 'The authorization grant type is not ' .
                    'supported by the authorization server.'
            ]);
    }

    /**
     * Test successful Oauth login
     */
    public function testSuccessfulOauthLogin()
    {
        // Register the user
        $registration = [
            'name'      => 'John Do1',
            'email'     => 'doe1@email.com',
            'password'  => '123456Qas',
            'confirmed' => '123456Qas'
        ];
        // Test user was successfully registered
        $this->json('POST', 'api/auth/register', $registration, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson(['success' => true]);
    }
}
