<?php

namespace Tests\Feature;

use App\Enum\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    #[dataProvider('create_data_success')]
    public function test_create_success(array $expected, array $input)
    {
        $response = $this->postJson('/api/tasks', $input);

        $response->assertStatus(201);

        $json = $response->json();
        $actualTaskData = $json['data'];

        $this->assertIsInt($actualTaskData['id']);
        $this->assertSame($expected['status'], $actualTaskData['status']);
        $this->assertSame($expected['title'], $actualTaskData['title']);
        $this->assertSame($expected['description'], $actualTaskData['description']);

        $this->assertDatabaseHas('tasks', [
            'id' => $actualTaskData['id'],
            'status' => TaskStatus::from($expected['status']),
            'title' => $expected['title'],
            'description' => $expected['description'],
        ]);
    }

    public static function create_data_success(): array
    {
        return [
            'all fields are present' => [
                'expected' => [
                    'status' => 'in_progress',
                    'title' => 'test title',
                    'description' => 'test description',
                ],
                'input' => [
                    'status' => 'in_progress',
                    'title' => 'test title',
                    'description' => 'test description',
                ],
            ],
            'with only required fields' => [
                'expected' => [
                    'status' => 'new',
                    'title' => 'test title',
                    'description' => null,
                ],
                'input' => [
                    'title' => 'test title',
                ],
            ],
        ];
    }

    #[dataProvider('create_data_invalid_values')]
    public function test_create_invalid_values(string $message, array $invalidValue): void
    {
        $input = array_merge([
            'status' => 'in_progress',
            'title' => 'test title',
            'description' => 'test description',
        ], $invalidValue);
        $response = $this->postJson('/api/tasks', $input);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => $message,
            ]);

        $this->assertDatabaseEmpty('tasks');
    }

    public static function create_data_invalid_values(): array
    {
        return [
            'invalid status' => [
                'message' => 'The selected status is invalid.',
                'invalidValue' => ['status' => 'invalid'],
            ],
            'empty title' => [
                'message' => 'The title field is required.',
                'invalidValue' => ['title' => ''],
            ],
            'null title' => [
                'message' => 'The title field is required.',
                'invalidValue' => ['title' => null],
            ],
        ];
    }

    #[dataProvider('update_data_success')]
    public function test_update_success(array $expected, array $input): void
    {
        $task = Task::factory()->make([
            'status' => TaskStatus::InProgress,
            'title' => 'old title',
            'description' => 'old description',
        ]);
        $task->save();

        $input['id'] = $task->id;
        $url = '/api/tasks/'.$task->id;

        $response = $this->putJson($url, $input);

        $response->assertStatus(200);

        $json = $response->json();
        $actualTaskData = $json['data'];

        $this->assertSame($task->id, $actualTaskData['id']);
        $this->assertSame($expected['status'], $actualTaskData['status']);
        $this->assertSame($expected['title'], $actualTaskData['title']);
        $this->assertSame($expected['description'], $actualTaskData['description']);

        $task->refresh();

        $this->assertSame(TaskStatus::from($expected['status']), $task->status);
        $this->assertSame($expected['title'], $task->title);
        $this->assertSame($expected['description'], $task->description);
    }

    public static function update_data_success(): array
    {
        return [
            'all fields are present' => [
                'expected' => [
                    'status' => 'done',
                    'title' => 'new title',
                    'description' => 'new description',
                ],
                'input' => [
                    'status' => 'done',
                    'title' => 'new title',
                    'description' => 'new description',
                ],
            ],
            'with only required fields' => [
                'expected' => [
                    'status' => 'done',
                    'title' => 'new title',
                    'description' => null,
                ],
                'input' => [
                    'status' => 'done',
                    'title' => 'new title',
                ],
            ],
        ];
    }

    #[dataProvider('update_data_invalid_values')]
    public function test_update_invalid_values(string $message, array $invalidValue): void
    {
        $task = Task::factory()->make([
            'status' => TaskStatus::InProgress,
            'title' => 'old title',
            'description' => 'old description',
        ]);
        $task->save();
        $input = array_merge([
            'status' => 'done',
            'title' => 'new title',
            'description' => 'new description',
        ], $invalidValue);
        $url = '/api/tasks/'.$task->id;

        $response = $this->putJson($url, $input);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => $message,
            ]);

        $task->refresh();

        $this->assertSame(TaskStatus::InProgress, $task->status);
        $this->assertSame('old title', $task->title);
        $this->assertSame('old description', $task->description);
    }

    public static function update_data_invalid_values(): array
    {
        return [
            'invalid status' => [
                'message' => 'The selected status is invalid.',
                'invalidValue' => ['status' => 'invalid'],
            ],
            'empty title' => [
                'message' => 'The title field is required.',
                'invalidValue' => ['title' => ''],
            ],
            'null title' => [
                'message' => 'The title field is required.',
                'invalidValue' => ['title' => null],
            ],
        ];
    }

    public function test_update_not_found(): void
    {
        $response = $this->putJson('/api/tasks/1', [
            'status' => 'done',
            'title' => 'new title',
            'description' => 'new description',
        ]);
        $response
            ->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found.',
            ]);
    }

    public function test_list()
    {
        Task::factory()->create([
            'status' => TaskStatus::InProgress,
            'title' => 'test title',
            'description' => 'test description',
        ]);
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);

        $json = $response->json();
        $taskData = array_first($json['data']);

        $this->assertIsInt($taskData['id']);
        $this->assertSame('in_progress', $taskData['status']);
        $this->assertSame('test title', $taskData['title']);
        $this->assertSame('test description', $taskData['description']);
    }

    public function test_delete_success(): void
    {
        $task = Task::factory()->make([
            'status' => TaskStatus::InProgress,
            'title' => 'old title',
            'description' => 'old description',
        ]);
        $task->save();

        $url = '/api/tasks/'.$task->id;

        $response = $this->deleteJson($url);

        $response->assertStatus(204);
        $this->assertDatabaseEmpty('tasks');
    }

    public function test_delete_not_found(): void
    {
        $response = $this->deleteJson('/api/tasks/1');
        $response
            ->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found.',
            ]);
    }
}
