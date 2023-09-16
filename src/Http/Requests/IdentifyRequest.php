<?php

namespace LaravelAuthPro\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'sign_in_method' => ['sometimes', 'nullable', 'string', Rule::enum(AuthProviderSignInMethod::class)]
        ];
    }

    private function getIdentifierValue(): string
    {
        /**
         * @var string $identifier
         */
        $identifier = $this->input('identifier');

        return $identifier;
    }

    public function getAuthIdentifier(): AuthIdentifierInterface
    {
        return AuthIdentifier::getBuilder()
            ->fromPlainIdentifier($this->getIdentifierValue())
            ->build();
    }
}
