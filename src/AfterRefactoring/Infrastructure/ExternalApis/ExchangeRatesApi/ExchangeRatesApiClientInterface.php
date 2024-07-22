<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi;

use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\ExchangeRatesDto;

interface ExchangeRatesApiClientInterface
{
    public function getRatesForEuro(): ExchangeRatesDto;
}