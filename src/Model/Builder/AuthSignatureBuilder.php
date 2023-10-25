<?php

namespace LaravelAuthPro\Model\Builder;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use LaravelAuthPro\AuthSignature;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;

/**
 * @implements EntityBuilderInterface<AuthSignatureInterface>
 */
class AuthSignatureBuilder implements EntityBuilderInterface
{
    private ?string $plainSignature = null;

    private ?string $ip = null;
    private ?string $userId = null;

    public function fromEncryptedPlainSignature(string $signature): AuthSignatureInterface
    {
        $this->plainSignature = $signature;

        return $this->build();
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function build(): AuthSignatureInterface
    {
        if ($this->plainSignature !== null) {
            try {
                /**
                 * @var array<string, string> $array
                 */
                $array = Crypt::decrypt($this->plainSignature);

                return AuthSignature::fromArray($array);
            } catch (DecryptException $e) {
                throw new AuthException('invalid_signature', 422, ['e' => $e]);
            }
        } else {
            if ($this->ip === null || $this->userId === null) {
                throw new \Exception('ip or userId cannot be null');
            }

            return new AuthSignature(Str::random(), $this->ip, $this->userId, now());
        }
    }
}
