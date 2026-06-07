<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:clients,email,' . $this->client->id . ',id,user_id,' . auth()->id(),
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];
    }
}
