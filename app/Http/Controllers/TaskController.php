<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatus;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

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

    public function store(TaskRequest $request)
    {
        $task = new Task;

        $this->setData($request, $task);
        $task->save();

        return $task->toResource();
    }

    public function update(TaskRequest $request, Task $task)
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

    private function setData(Request $request, Task $task)
    {
        $data = $request->json();
        $task->status = $data->getEnum('status', TaskStatus::class);
        $task->title = $data->get('title');
        $task->description = $data->get('description');
    }
}
