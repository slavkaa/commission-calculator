<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos;

class ExchangeRatesDto
{
    // @phpstan-ignore-next-line
    public function __construct(
        protected array $ratesData
    )
    {}
    
    public function getRate(string $currencyAlpha3code): ?float
    {
        if ($currencyAlpha3code === $this->getBaseCurrency()) {
            return 1;
        }
        
        return $this->ratesData['rates'][$currencyAlpha3code] ?? null;
    }
    
    protected function getBaseCurrency(): string
    {
        return $this->ratesData['base'];
    }
}