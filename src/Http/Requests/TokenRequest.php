<?php

namespace LaravelAuthPro\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use LaravelAuthPro\AuthIdentifier;
use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Credentials\AuthCredential;
use LaravelAuthPro\Providers\AuthProvider;

class TokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string[]| mixed>>
     */
    public function rules(): array
    {
        /**
         * @var array<string, array<string[]| mixed>> $rules
         */
        $rules = Collection::make([
            'credential' => ['required', 'array'],
            'credential.identifier' => ['required', 'string'],
            'credential.provider_id' => ['required', 'string', Rule::in($this->getProviderIds())],
            'credential.sign_in_method' => ['required', 'string', Rule::in($this->getProviderSignInMethod())],
            'credential.payload' => ['required', 'array']
        ])
            ->merge($this->getCredentialPayloadRules())
            ->toArray();

        return $rules;
    }

    /**
     * @return Collection<int, string>
     */
    private function getProviderIds(): Collection
    {
        return Collection::make(AuthPro::getAuthProvidersMapper())
            ->values()
            ->map(fn($provider) => $provider::ID);
    }

    /**
     * @return Collection<int, string>
     */
    private function getProviderSignInMethod(): Collection
    {
        return Collection::make(AuthProvider::createFromProviderId($this->input('credential.provider_id'))::SUPPORTED_SIGN_IN_METHODS)
            ->map(fn($method) => $method->value);
    }

    /**
     * @return Collection<string, array<string, string[]|mixed>>
     */
    private function getCredentialPayloadRules(): Collection
    {
        return Collection::make(AuthCredential::getBuilder()::getClassFromProviderId($this->input('credential.provider_id'))::getPayloadRules())
            ->mapWithKeys(fn($item, $key) => ["credential.payload.$key" => $item]);
    }

    public function getAuthCredential(): AuthCredentialInterface
    {
        return AuthCredential::getBuilder()
            ->with($this->input('credential.provider_id'))
            ->as(AuthIdentifier::getBuilder()->fromPlainIdentifier($this->input('credential.identifier'))->build())
            ->by($this->enum('credential.sign_in_method', AuthSignInMethodInterface::class))
            ->withPayload($this->input('credential.payload'))
            ->build();
    }
}
