<?php
declare(strict_types=1);

namespace Tests\AfterRefactoring\Mocks;

use App\AfterRefactoring\BusinessLogic\CommissionCalculatorFactory;
use App\AfterRefactoring\BusinessLogic\CommissionCalculatorService;
use App\AfterRefactoring\BusinessLogic\Parsers\InputDataParserInterface;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\BinListApiFactoryInterface;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiFactoryInterface;
use GuzzleHttp\Handler\MockHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Tests\AfterRefactoring\Mocks\BusinessLogic\Parsers\InputDataParserMock;
use Tests\AfterRefactoring\Mocks\Infrastructure\ExternalApis\BinListApi\BinListApiFactoryMock;
use Tests\AfterRefactoring\Mocks\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiFactoryMock;

class CommissionCalculatorFactoryMock extends CommissionCalculatorFactory
{
    protected array $parserResponse = [];
    protected MockHandler $binListApiMockResponses;
    protected MockHandler $exchangeRatesApiMockResponses;
    
    public function createCommissionCalculatorService()
    {
        return new CommissionCalculatorService(
            $this->getParser(),
            $this->getCommissionCalculator(),
            $this->getLogger()
        );
    }
    
    public function getParser(): InputDataParserInterface
    {
        $mock = new InputDataParserMock(
            $this->getMoneyBuilder(),
            $this->getLogger()
        );
        
        $mock->setData($this->parserResponse);
        
        return $mock;
    }
    
    public function getBinListApiFactory(): BinListApiFactoryInterface
    {
        $factory = new BinListApiFactoryMock();
        $factory->setMockResponses($this->binListApiMockResponses);
        
        return $factory;
    }
    
    public function getExchangeRatesApiFactory(): ExchangeRatesApiFactoryInterface
    {
        $factory =  new ExchangeRatesApiFactoryMock();
        $factory->setMockResponses($this->exchangeRatesApiMockResponses);
        
        return $factory;
    }
    
    public function setParserResponse(array $response): void
    {
        $this->parserResponse = $response;
    }
    
    public function setBinListApiMockResponses(MockHandler $binListApiMockResponses): void
    {
        $this->binListApiMockResponses = $binListApiMockResponses;
    }
    
    public function setExchangeRatesApiMockResponses(MockHandler $exchangeRatesApiMockResponses): void
    {
        $this->exchangeRatesApiMockResponses = $exchangeRatesApiMockResponses;
    }
    
    public function getLogger(): LoggerInterface
    {
        $log = new Logger('name');
        $log->pushHandler(
            new StreamHandler(
                __DIR__ . '/../../../var/logs/app.tests.log',
                $this->logLevel
            )
        );
        
        return $log;
    }
}