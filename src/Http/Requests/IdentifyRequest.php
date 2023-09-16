<?php

namespace LaravelAuthPro\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LaravelAuthPro\AuthIdentifier;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;

class IdentifyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string[]|array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'sign_in_method' => ['sometimes', 'nullable', 'string', Rule::enum(AuthProviderSignInMethod::class)]
        ];
    }

    public function getAuthIdentifier(): AuthIdentifierInterface
    {
        return AuthIdentifier::getBuilder()
            ->fromPlainIdentifier($this->input('identifier'))
            ->build();
    }
}
