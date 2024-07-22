<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi;

use App\AfterRefactoring\Infrastructure\Cache\StaticCache;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\ExchangeRatesDto;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\RequestDtos\GetLatestRatesRequestDto;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

interface ExchangeRatesApiFactoryInterface
{
    public function getClient(): Client;
    
    public function getLatestRatesRequest(): GetLatestRatesRequestDto;
    
    /**
     * @param array $ratesData
     *    Example of $ratesData: ['base' => 'EUR', 'rates' => ['USD' => 1.2, ...]]
     * @phpstan-ignore-next-line
     */
    public function buildExchangeRatesDto(array $ratesData): ExchangeRatesDto;
    
    public function getStaticCache(): StaticCache;
    
    public function getLogger(): LoggerInterface;
}