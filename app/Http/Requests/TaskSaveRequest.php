<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Http\Requests\Traits\AjaxFailResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskSaveRequest extends FormRequest
{
    use AjaxFailResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(TaskStatus::names())],
        ];
    }
}
