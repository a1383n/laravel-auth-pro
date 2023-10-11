<?php

namespace LaravelAuthPro\Contracts;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;
use Stringable;

/**
 * @extends HasBuilderInterface<AuthSignatureInterface>
 * @extends Arrayable<string, string>
 */
interface AuthSignatureInterface extends HasBuilderInterface, Arrayable, Stringable
{
    public function getId(): string;
    public function getRequestedIp(): string;
    public function getUserId(): string;
    public function getTimestamp(): CarbonInterface;
}
