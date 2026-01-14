<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatus;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private const int PER_PAGE = 4;

    public function index()
    {
        return Task::query()
            ->orderBy('tasks.id', 'ASC')
            ->paginate(self::PER_PAGE)
            ->toResourceCollection();
    }

    public function show(Task $task)
    {
        return $task->toResource();
    }

    public function store(Request $request)
    {
        $this->validate($request);

        $task = new Task;

        $this->setData($request, $task);
        $task->save();

        return $task->toResource();
    }

    public function update(Request $request, Task $task)
    {
        $this->validate($request);

        $this->setData($request, $task);
        $task->save();

        return $task->toResource();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }

    private function setData(Request $request, Task $task)
    {
        $data = $request->json();
        $task->status = $data->getEnum('status', TaskStatus::class);
        $task->title = $data->get('title');
        $task->description = $data->get('description');
    }

    private function validate(Request $request)
    {
        Validator::make($request->json()->all(), [
            'status' => [
                'required',
                Rule::enum(TaskStatus::class),
            ],
            'title' => [
                'required',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ])->validate();
    }
}
