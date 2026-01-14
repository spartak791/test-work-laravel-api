<?php

namespace App\Http\Requests;

use App\Enum\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class TaskRequest extends FormRequest
{
    public function validationData()
    {
        return $this->json()->all();
    }

    public function rules(): array
    {
        return array_merge_recursive(
            [
                'status' => [
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
            ],
            $this->additionalRules(),
        );
    }

    public function getStatus(): ?TaskStatus
    {
        return $this->json()->getEnum('status', TaskStatus::class);
    }

    public function getTitle(): string
    {
        return $this->json('title');
    }

    public function getDescription(): ?string
    {
        return $this->json('description');
    }

    abstract protected function additionalRules(): array;
}
