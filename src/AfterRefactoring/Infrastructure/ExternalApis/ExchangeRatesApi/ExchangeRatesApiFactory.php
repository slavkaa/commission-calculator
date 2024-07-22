<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi;

use App\AfterRefactoring\Infrastructure\Cache\StaticCache;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\ExchangeRatesDto;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\RequestDtos\GetLatestRatesRequestDto;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class ExchangeRatesApiFactory implements ExchangeRatesApiFactoryInterface
{
    protected Level $logLevel;
    
    public function __construct()
    {
        $this->logLevel = Level::{$_ENV['EXCHANGE_RATE_API_LEVEL']};
    }
    
    public function getClient(): Client
    {
        return new Client();
    }
    
    public function getLatestRatesRequest(): GetLatestRatesRequestDto
    {
        return new GetLatestRatesRequestDto();
    }
    
    // @phpstan-ignore-next-line
    public function buildExchangeRatesDto(array $ratesData): ExchangeRatesDto
    {
        return new ExchangeRatesDto($ratesData);
    }
    
    public function getStaticCache(): StaticCache
    {
        return new StaticCache(
            $this->getLogger()
        );
    }
    
    // @todo: depend on architecture and business logic Logger can be reused from CommissionCalculationFactory.
    public function getLogger(): LoggerInterface
    {
        $log = new Logger('name');
        $log->pushHandler(
            new StreamHandler(
                __DIR__ . '/../../../../../var/logs/app.log',
                $this->logLevel
            )
        );
        
        return $log;
    }
}