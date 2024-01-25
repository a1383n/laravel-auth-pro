<?php

describe('test abstract base service', function () {
    it('throw if repository not provided', function () {
        $repository = Mockery::mock(\LaravelAuthPro\Base\BaseRepository::class);

        $class = new class extends \LaravelAuthPro\Base\BaseService {
            //
        };

        $class = new $class($repository);
        expect($class->hasRepository())->toBeTrue();

        $class = new $class();
        expect($class->hasRepository())
            ->toBeFalse()
            ->and(fn() => $class->throwIfRepositoryNotProvided())
            ->toThrow(\InvalidArgumentException::class);
    });
});
