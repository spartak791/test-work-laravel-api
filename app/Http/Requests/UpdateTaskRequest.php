<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends TaskRequest
{
    public function additionalRules(): array
    {
        return [
            'status' => [
                'required',
            ],
        ];
    }
}
