<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic;

use App\AfterRefactoring\BusinessLogic\CommissionCalculator\CommissionCalculator;
use App\AfterRefactoring\BusinessLogic\CommissionCalculator\CommissionCalculatorInterface;
use App\AfterRefactoring\BusinessLogic\Parsers\InputDataParser;
use App\AfterRefactoring\BusinessLogic\Parsers\InputDataParserInterface;
use App\AfterRefactoring\BusinessLogic\Repositories\BinRepository;
use App\AfterRefactoring\BusinessLogic\Repositories\ExchangeRatesRepository;
use App\AfterRefactoring\Infrastructure\Builders\MoneyDtoBuilder;
use App\AfterRefactoring\Infrastructure\Builders\MoneyDtoBuilderInterface;
use App\AfterRefactoring\Infrastructure\Cache\StaticCache;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\BinListApiClient;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\BinListApiClientInterface;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\BinListApiFactory;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\BinListApiFactoryInterface;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiClient;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiClientInterface;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiFactory;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiFactoryInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CommissionCalculatorFactory implements CommissionCalculatorFactoryInterface
{
    protected Level $logLevel;
    
    public function __construct()
    {
        $this->logLevel = Level::{$_ENV['LOG_LEVEL']};
    }
    
    public function getCommissionCalculatorService(): CommissionCalculatorServiceInterface
    {
        return new CommissionCalculatorService(
            $this->getParser(),
            $this->getCommissionCalculator(),
            $this->getLogger()
        );
    }
    
    public function getParser(): InputDataParserInterface
    {
        return new InputDataParser(
            $this->getMoneyBuilder(),
            $this->getLogger()
        );
    }
    
    public function getCommissionCalculator(): CommissionCalculatorInterface
    {
        return new CommissionCalculator(
            $this->getExchangeRatesRepository(),
            $this->getLogger()
        );
    }
    
    public function getMoneyBuilder(): MoneyDtoBuilderInterface
    {
        return new MoneyDtoBuilder(
            $this->getBinRepository(),
            $this->getLogger()
        );
    }
    
    public function getBinListApiClient(): BinListApiClientInterface
    {
        return new BinListApiClient(
            $this->getBinListApiFactory()
        );
    }
    
    public function getExchangeRatesApiClient(): ExchangeRatesApiClientInterface
    {
        return new ExchangeRatesApiClient(
            $this->getExchangeRatesApiFactory()
        );
    }
    
    public function getStaticCache(): StaticCache
    {
        return new StaticCache(
            $this->getLogger()
        );
    }
    
    public function getBinRepository(): BinRepository
    {
        return new BinRepository(
            $this->getBinListApiClient()
        );
    }
    
    public function getExchangeRatesRepository(): ExchangeRatesRepository
    {
        return new ExchangeRatesRepository(
            $this->getExchangeRatesApiClient()
        );
    }
    
    public function getLogger(): LoggerInterface
    {
        $log = new Logger('name');
        $log->pushHandler(
            new StreamHandler(
                __DIR__ . '/../../../var/logs/app.log',
                $this->logLevel
            )
        );
        
        return $log;
    }
    
    public function getBinListApiFactory(): BinListApiFactoryInterface
    {
        return new BinListApiFactory();
    }
    
    public function getExchangeRatesApiFactory(): ExchangeRatesApiFactoryInterface
    {
        return new ExchangeRatesApiFactory();
    }
}