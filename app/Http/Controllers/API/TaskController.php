<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskSaveRequest;
use App\Http\Requests\TaskSortRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function getTasks(TaskSortRequest $request): JsonResponse
    {
        $tasksQuery = Task::query();

        if ($request->get('dateFrom')) {
            $tasksQuery = $tasksQuery->whereDate('created_at', '>=', $request->get('dateFrom'));
        }

        if ($request->get('dateTo')) {
            $tasksQuery = $tasksQuery->whereDate('created_at', '<=', $request->get('dateTo'));
        }

        $tasks = $tasksQuery->get()->groupBy('status');

        return $this->responseJson(true, $tasks->toArray());
    }

    public function create(TaskSaveRequest $request): JsonResponse
    {
        $task = Task::query()->create($request->validated());

        return $this->responseJson(true, $task->toArray(), 'Задача добавлена.');
    }

    public function update(Task $task, TaskSaveRequest $request): JsonResponse
    {
        $task->update($request->validated());

        return $this->responseJson(true, $task->toArray(), 'Задача обновлена.');
    }

    public function delete(Task $task): JsonResponse
    {
        $task->delete();

        return $this->responseJson(true, message: 'Задача удалена.');
    }
}
