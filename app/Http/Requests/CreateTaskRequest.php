<?php

namespace App\Http\Requests;

class CreateTaskRequest extends TaskRequest
{
    public function additionalRules(): array
    {
        return [];
    }
}
