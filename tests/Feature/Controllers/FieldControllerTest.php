<?php

namespace Tests\Feature\Controllers;

use App\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreMissingRequiredFields()
    {
        $this->postJson('api/fields')
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'type',
                ]
            ]);
    }

    public function testStoreWrongType()
    {
        $data = [
            'title' => 'phonenumber',
            'type' => 'phonenumber'
        ];

        $this->postJson('api/fields', $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'type',
                ]
            ]);
    }

    public function testStoreDuplicate()
    {
        $data = [
            'title' => 'name',
            'type' => 'string'
        ];

        factory(Field::class)->create($data);

        $this->postJson('api/fields', $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                ]
            ]);
    }

    public function testStore()
    {
        $data = [
            'title' => 'name',
            'type' => 'string'
        ];

        $this->postJson('api/fields', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('fields', $data);
    }
}
