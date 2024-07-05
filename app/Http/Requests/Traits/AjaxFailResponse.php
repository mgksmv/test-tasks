<?php

namespace App\Http\Requests\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

trait AjaxFailResponse
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->ajax()) {
            $undotedMessageBag = Arr::undot($validator->errors()->toArray());

            $response = response()->json([
                'success' => false,
                'data' => ['errors' => $undotedMessageBag]
            ]);

            throw new ValidationException($validator, $response);
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
