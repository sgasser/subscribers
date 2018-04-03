<?php

namespace Tests\Feature\Controllers;

use App\Field;
use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreMissingRequiredField()
    {
        $this->postJson('api/subscribers')
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email'
                ]
            ]);
    }

    public function testStoreNotValidEmail()
    {
        $data = [
            'email' => 'novalidmail'
        ];

        $this->postJson('api/subscribers', $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email'
                ]
            ]);
    }

    public function testStoreNotActiveHostDomain()
    {
        $data = [
            'email' => 'novalidemail@gmaillite.xyz'
        ];

        $this->postJson('api/subscribers', $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email'
                ]
            ]);
    }

    public function testStoreOnlyEmail()
    {
        $data = [
            'email' => 'valid@gmail.com'
        ];

        $this->postJson('api/subscribers', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('subscribers', $data);
    }

    public function testStoreWithState()
    {
        $data = [
            'email' => 'valid@gmail.com',
            'state' => 'active'
        ];

        $this->postJson('api/subscribers', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('subscribers', $data);
    }

    public function testStoreWithExistingEmail()
    {
        $data = [
            'email' => 'valid@gmail.com',
            'state' => 'active'
        ];

        factory(Subscriber::class)->create($data);

        $this->postJson('api/subscribers', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('subscribers', $data);
    }

    public function testStoreWithExistingEmailAndNotUpdateState()
    {
        $oldData = [
            'email' => 'valid@gmail.com',
            'state' => 'unconfirmed'
        ];

        factory(Subscriber::class)->create($oldData);

        $data = [
            'email' => 'valid@gmail.com',
            'state' => 'active'
        ];

        $this->postJson('api/subscribers', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('subscribers', $oldData);
    }

    public function testStoreNotExistingField()
    {
        $data = [
            'email' => 'valid@gmail.com',
            'state' => 'active',
            'fields' => [
                'gender' => 'm'
            ]
        ];

        $this->postJson('api/subscribers', $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'fields.title'
                ]
            ]);
    }

    public function testStoreFieldValidation()
    {
        $fieldData = [
            'title' => 'company',
            'type' => 'boolean'
        ];

        factory(Field::class)->create($fieldData);

        $data = [
            'email' => 'valid@gmail.com',
            'state' => 'active',
            'fields' => [
                'company' => 'yes'
            ]
        ];

        $this->postJson('api/subscribers', $data)
            ->assertStatus(422);
    }

    public function testStoreWithField()
    {
        $fieldData = [
            'title' => 'gender',
            'type' => 'string'
        ];

        $field = factory(Field::class)->create($fieldData);

        $data = [
            'email' => 'valid@gmail.com',
            'state' => 'active',
            'fields' => [
                'gender' => 'm'
            ]
        ];

        $subsciberId = $this->postJson('api/subscribers', $data)
            ->assertStatus(201)
            ->json('id');

        $this->assertDatabaseHas('subscribers', [
            'email' => 'valid@gmail.com',
            'state' => 'active',
        ]);

        $this->assertDatabaseHas('field_subscriber', [
            'field_id' => $field->id,
            'subscriber_id' => $subsciberId,
            'value' => 'm'
        ]);
    }
}
