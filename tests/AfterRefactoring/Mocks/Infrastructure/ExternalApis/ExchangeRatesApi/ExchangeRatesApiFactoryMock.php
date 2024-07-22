<?php
declare(strict_types=1);

namespace Tests\AfterRefactoring\Mocks\Infrastructure\ExternalApis\ExchangeRatesApi;

use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class ExchangeRatesApiFactoryMock extends ExchangeRatesApiFactory
{
    protected MockHandler $mockResponses;
    
    public function getClient(): Client
    {
        $handlerStack = HandlerStack::create($this->mockResponses);
        return new Client(['handler' => $handlerStack]);
    }
    
    public function setMockResponses(MockHandler $mockResponses): void
    {
        $this->mockResponses = $mockResponses;
    }
    
    public function getLogger(): LoggerInterface
    {
        $log = new Logger('name');
        $log->pushHandler(
            new StreamHandler(
                __DIR__ . '/../../../../../../var/logs/app.tests.log',
                $this->logLevel
            )
        );
        
        return $log;
    }
}