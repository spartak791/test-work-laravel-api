<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatus;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

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

    public function store(CreateTaskRequest $request)
    {
        $task = new Task;

        $this->setData($request, $task);
        $task->save();

        return $task->toResource();
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->setData($request, $task);
        $task->save();

        return $task->toResource();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }

    private function setData(TaskRequest $request, Task $task)
    {
        $task->status = $request->getStatus() ?? TaskStatus::New;
        $task->title = $request->getTitle();
        $task->description = $request->getDescription();
    }
}
