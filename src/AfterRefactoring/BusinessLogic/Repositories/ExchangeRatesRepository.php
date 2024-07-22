<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic\Repositories;

use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\ExchangeRatesDto;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\ExchangeRatesApiClientInterface;

class ExchangeRatesRepository
{
    public function __construct(
        protected ExchangeRatesApiClientInterface $api
    )
    {}
    
    public function getRatesForEuro(): ExchangeRatesDto
    {
        return $this->api->getRatesForEuro();
    }
}