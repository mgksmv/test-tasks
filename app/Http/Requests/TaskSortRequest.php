<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\AjaxFailResponse;
use Illuminate\Foundation\Http\FormRequest;

class TaskSortRequest extends FormRequest
{
    use AjaxFailResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dateFrom' => ['nullable', 'date'],
            'dateTo' => ['nullable', 'date'],
        ];
    }
}
