<?php

describe('test auth exception', function () {
    it('create new instance', function () {
        $e = new \LaravelAuthPro\Contracts\Exceptions\AuthException('my_error', 500, ['foo' => 'bar']);

        $translatorMock = Mockery::mock(\Illuminate\Translation\Translator::class)
            ->shouldReceive('get')
            ->andReturn('My Error')
            ->getMock();

        $reposeFactory = Mockery::mock($rf = \Illuminate\Contracts\Routing\ResponseFactory::class)
            ->shouldReceive('make')
            ->andReturn(new \Illuminate\Http\Response())
            ->getMock();

        app()['translator'] = $translatorMock;
        app()[$rf] = $reposeFactory;

        expect($e->getErrorMessage())
            ->toEqual('my_error')
            ->and($e->getCode())
            ->toEqual(500)
            ->and($e->report())
            ->toBeFalse()
            ->and($e->render(new \Illuminate\Http\Request()))
            ->toBeInstanceOf(\Illuminate\Http\Response::class);
    });

    it('custom render function', function () {
        $e = new \LaravelAuthPro\Contracts\Exceptions\AuthException('my_error', 500, ['foo' => 'bar']);

        \LaravelAuthPro\Contracts\Exceptions\AuthException::setRenderClosure(function (\LaravelAuthPro\Contracts\Exceptions\AuthException $exception) use ($e) {
            expect($exception)->toBe($e);

            return ['error' => $e->getErrorMessage(), 'code' => $e->getCode()];
        });

        expect($e->render(new \Illuminate\Http\Request()))
            ->toBe(['error' => 'my_error', 'code' => 500]);
    });
});
