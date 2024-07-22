<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic;

use App\AfterRefactoring\BusinessLogic\CommissionCalculator\CommissionCalculatorInterface;
use App\AfterRefactoring\BusinessLogic\Parsers\InputDataParserInterface;
use App\AfterRefactoring\BusinessLogic\Repositories\BinRepository;
use App\AfterRefactoring\BusinessLogic\Repositories\ExchangeRatesRepository;
use App\AfterRefactoring\Infrastructure\Builders\MoneyDtoBuilderInterface;
use App\AfterRefactoring\Infrastructure\Cache\StaticCache;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\BinListApiClientInterface;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiClientInterface;
use Psr\Log\LoggerInterface;

interface CommissionCalculatorFactoryInterface
{
    public function getParser(): InputDataParserInterface;
    
    public function getCommissionCalculator(): CommissionCalculatorInterface;
    
    public function getMoneyBuilder(): MoneyDtoBuilderInterface;
    
    public function getBinListApiClient(): BinListApiClientInterface;
    
    public function getExchangeRatesApiClient(): ExchangeRatesApiClientInterface;
    
    public function getStaticCache(): StaticCache;
    
    public function getLogger(): LoggerInterface;
    
    public function getBinRepository(): BinRepository;
    
    public function getExchangeRatesRepository(): ExchangeRatesRepository;
}