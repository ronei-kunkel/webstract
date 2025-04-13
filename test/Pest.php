<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RoneiKunkel\Webstract\Controller\ApiController;
use Test\RoneiKunkel\Webstract\TestCase;
use Test\Support\RoneiKunkel\Webstract\Session\FakeSessionManager;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()
    ->extend(TestCase::class)
    ->beforeEach(function () {
        $this->psr17Factory = new Psr17Factory();
        $this->response = $this->psr17Factory->createResponse();
        $this->stream = $this->psr17Factory->createStream();
        $this->serverRequest = (new ServerRequestCreator(
            $this->psr17Factory,
            $this->psr17Factory,
            $this->psr17Factory,
            $this->psr17Factory,
        ))->fromGlobals();
    });

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getExpectedBodyFromFilePath(string $filepath): string
{
    $stream = fopen($filepath, 'r');
    $expectedBody = stream_get_contents($stream);
    fclose($stream);
    return $expectedBody;
}
