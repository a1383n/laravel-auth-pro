<?php

namespace LaravelAuthPro\Contracts\Exceptions;

use Closure;
use Exception;
use LaravelAuthPro\Contracts\AuthExceptionInterface;

class AuthException extends Exception implements AuthExceptionInterface
{
    private const ERROR_MESSAGE_TEMPLE = '[%s]';
    private const ERROR_MESSAGE_TEMPLE_WITH_EXCEPTION = '[%s] - %s';

    private static ?Closure $renderClosure = null;

    /**
     * @param string|null $error
     * @param int $code
     * @param array<string, mixed> $payload
     */
    public function __construct(protected ?string $error, protected $code = 400, protected array $payload = [])
    {
        $key = 'auth.error.' . ($this->error ?? 'unknown');
        $this->code = $this->error === null ? 500 : $this->code;

        /**
         * @var \Throwable|null $e
         */
        $e = $payload['e'] ?? null;

        parent::__construct(
            sprintf(
                $e === null ?
                    self::ERROR_MESSAGE_TEMPLE :
                    self::ERROR_MESSAGE_TEMPLE_WITH_EXCEPTION,
                $key,
                $e?->getMessage(),
            ),
            $code,
            $e
        );
    }

    public static function setRenderClosure(?Closure $renderClosure): void
    {
        self::$renderClosure = $renderClosure;
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report(): ?bool
    {
        return $this->code < 500;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if (self::$renderClosure !== null) {
            /**
             * @phpstan-ignore-next-line
             */
            return self::$renderClosure->call($this, $this);
        } else {
            return response([
                'is_successful' => false,
                'error' => $this->error,
                /**
                 * @phpstan-ignore-next-line
                 */
                'message' => __($this->error, $this->payload ?? []),
            ], $this->code);
        }
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(): string
    {
        return $this->error ?? 'unknown';
    }

    /**
     * @inheritDoc
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
