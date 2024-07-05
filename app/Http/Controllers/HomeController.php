<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $tasks = Task::all()->groupBy('status')->toJson();
        $statuses = json_encode(TaskStatus::toObject());

        return view('home', compact('tasks', 'statuses'));
    }
}
