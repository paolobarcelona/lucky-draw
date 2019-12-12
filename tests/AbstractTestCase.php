<?php

namespace Tests;

use App\Repositories\AppRepositoryInterface;
use Closure;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\MockInterface;
use Mockery;

abstract class AbstractTestCase extends BaseTestCase
{
    use CreatesApplication;
    
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var \App\Repositories\AppRepositoryInterface
     */
    private $repository;

    /**
     * Should retrieve the repository.
     *
     * @param string $repositoryInterface
     *
     * @return \App\Repositories\AppRepositoryInterface
     */
    public function getRepository(string $repositoryInterface): AppRepositoryInterface
    {
        if ($this->repository !== null) {
            return $this->repository;
        }

        return $this->repository = $this->app->make($repositoryInterface);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(
            Request::class,
            Request::create(\config('app.url'))
        );
    }    

    /**
     * Assert array to be equal to expected data with only given expected keys from expected data.
     *
     * @param mixed[] $expected
     * @param mixed[] $assertData
     * @param null|string[] $excludedKeys
     *
     * @return void
     */
    protected function assertArrayEquals(
        array $expected, 
        array $assertData, 
        ?array $excludedKeys = null
    ): void {
        /** @var string[] $excludedKeys */
        $excludedKeys = ['created_at', 'updated_at'] + ($excludedKeys ?? []);

        /**
         * @var string $key
         * @var mixed $value
         */
        foreach ($expected as $key => $value) {
            if (\in_array($key, $excludedKeys) === true) {
                continue;
            }

            self::assertArrayHasKey($key, $assertData);
            self::assertEquals($expected[$key], $assertData[$key]);
        }
    }

    /**
     * Assert given array has all given keys.
     *
     * @param string[] $keys
     * @param mixed[] $array
     *
     * @return void
     */
    protected function assertArrayHasKeys(array $keys, array $array): void
    {
        // Sort arrays
        \sort($keys);
        \ksort($array);

        self::assertSame($keys, \array_keys($array));
    }    

    /**
     * Get faker instance.
     *
     * @return \Faker\Generator
     */
    protected function getFaker(): Generator
    {
        if ($this->faker !== null) {
            return $this->faker;
        }

        return $this->faker = FakerFactory::create();
    }

    /**
     * Create mock for given class and set expectations using given closure.
     *
     * @param mixed|string $class
     * @param \Closure|null $setExpectations
     *
     * @return \Mockery\MockInterface
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery)
     */
    protected function mock($class, ?Closure $setExpectations = null): MockInterface
    {
        /** @var \Mockery\MockInterface $mock */
        $mock = Mockery::mock($class);

        // If no expectations, early return
        if ($setExpectations === null) {
            return $mock;
        }

        // Pass mock to closure to set expectations
        $setExpectations($mock);

        return $mock;
    }

    /**
     * Create a response object.
     *
     * @param int $status
     * @param string $contentType
     * @param mixed[] $body
     *
     * @return \EoneoPay\Externals\HttpClient\Response
     */
    protected function response(
        ?int $status = null,
        ?string $contentType = null,
        ?array $body = null
    ): Response {
        $data = '';

        if ($body !== null) {
            $data = \json_encode($body);
        }

        return new Response(
            $data ?: '',
            $status ?? 200,
            ['Content-Type' => $contentType ?? 'application/json']
        );
    }    
}
