<?php

namespace App\Http\Requests;

use App\Enum\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function validationData()
    {
        return $this->json()->all();
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
