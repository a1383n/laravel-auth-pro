<?php

namespace LaravelAuthPro\Model\Builder;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Support\CodeGenerator;
use LaravelAuthPro\Infrastructure\OneTimePassword\Support\TokenGenerator;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Model\OneTimePasswordEntity;

class OneTimePasswordEntityBuilder
{
    protected AuthIdentifierInterface $identifier;
    protected ?string $token = null;
    protected bool $withToken = true;
    protected ?string $code = null;
    protected ?CarbonInterval $interval = null;

    public function __construct(private readonly TokenGenerator $tokenGenerator, private readonly CodeGenerator $codeGenerator)
    {
        //
    }

    /**
     * @param AuthIdentifierInterface $identifier
     * @param string $key
     * @param array<string, string> $array
     * @return OneTimePasswordEntityInterface
     */
    public static function fromArray(AuthIdentifierInterface $identifier, string $key, array $array): OneTimePasswordEntityInterface
    {
        /**
         * @var string $token
         */
        $token = Arr::last(explode(':', $key, 3));

        return new OneTimePasswordEntity(
            $identifier,
            $token,
            $array['c'],
            CarbonInterval::second($array['i']),
            Carbon::createFromTimestamp($array['t'])
        );
    }

    public function as(AuthIdentifierInterface $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function withToken(): self
    {
        $this->withToken = true;

        return $this;
    }

    public function withoutToken(): self
    {
        $this->withToken = false;

        return $this;
    }

    public function validFor(CarbonInterval $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function build(): OneTimePasswordEntityInterface
    {
        if ($this->token === null && $this->withToken) {
            $this->token = $this->tokenGenerator->generate();
        }

        if ($this->code === null) {
            $this->code = $this->codeGenerator->generate();
        }

        if ($this->interval === null) {
            $this->interval = CarbonInterval::seconds(config('auth_pro.one_time_password.expiry', 120));
        }

        return new OneTimePasswordEntity($this->identifier, $this->token, $this->code, $this->interval, Date::now());
    }
}
