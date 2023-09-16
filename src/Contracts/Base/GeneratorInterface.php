<?php

namespace LaravelAuthPro\Contracts\Base;

interface GeneratorInterface
{
    public function generate(int $length = null): mixed;
}
