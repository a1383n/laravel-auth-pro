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
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Providers\AuthProvider;

class TokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
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
            'credential.payload' => ['required', 'array'],
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
        return Collection::make(AuthPro::getAuthProvidersConfiguration())
            ->values()
            ->map(fn ($provider) => $provider['class']::ID);
    }

    /**
     * @return Collection<int, string>
     */
    private function getProviderSignInMethod(): Collection
    {
        /**
         * @phpstan-ignore-next-line
         */
        return Collection::make(AuthProvider::getBuilder()->fromId($this->input('credential.provider_id'))::SUPPORTED_SIGN_IN_METHODS)
            /**
             * @phpstan-ignore-next-line
             */
            ->map(fn (AuthProviderSignInMethod $method) => $method->value);
    }

    /**
     * @return Collection<string, array<string, string[]|mixed>>
     */
    private function getCredentialPayloadRules(): Collection
    {
        /**
         * @phpstan-ignore-next-line
         */
        return Collection::make(AuthCredential::getBuilder()::getClassFromProviderId($this->input('credential.provider_id'))::getPayloadRules())
            ->mapWithKeys(fn ($item, $key) => ["credential.payload.$key" => $item]);
    }

    public function getAuthCredential(): AuthCredentialInterface
    {
        return AuthCredential::getBuilder()
            /**
             * @phpstan-ignore-next-line
             */
            ->with($this->input('credential.provider_id'))
            /**
             * @phpstan-ignore-next-line
             */
            ->as(AuthIdentifier::getBuilder()->fromPlainIdentifier($this->input('credential.identifier'))->build())
            /**
             * @phpstan-ignore-next-line
             */
            ->by($this->enum('credential.sign_in_method', AuthSignInMethodInterface::class))
            /**
             * @phpstan-ignore-next-line
             */
            ->withPayload($this->input('credential.payload'))
            ->build();
    }
}
