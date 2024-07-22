<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic\CommissionCalculator;

use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;
use App\AfterRefactoring\BusinessLogic\Repositories\ExchangeRatesRepository;
use Psr\Log\LoggerInterface;

class CommissionCalculator implements CommissionCalculatorInterface
{
    const float COMMISSION_FOR_EU_ZONE = 0.01;
    const float COMMISSION_FOR_NON_EU_ZONE = 0.02;
    
    public function __construct(
        protected ExchangeRatesRepository $repository,
        protected LoggerInterface $logger
    )
    {}
    
    public function calculate(TransactionDto $dto): float
    {
        $exchangeCoefficient = $this->getExchangeCoefficient($dto);
        $euCoefficient = $this->getEuCoefficient($dto);
        
        $this->logger->debug(sprintf(
            'Calculating commission %s * %s * %s',
            $dto->amount,
            $exchangeCoefficient,
            $euCoefficient
        ));
        
        return ceil($dto->amount * $exchangeCoefficient * $euCoefficient * 100) / 100;
    }
    
    protected function getExchangeCoefficient(TransactionDto $dto): float
    {
        $ratesDto = $this->repository->getRatesForEuro();
        $currentRate = $ratesDto->getRate($dto->currencyAlpha3Code);
        
        $this->logger->info(
            sprintf(
                'Current rate for %s is %s',
                $dto->currencyAlpha3Code,
                $currentRate
            )
        );
        
        if (empty($currentRate) || $currentRate == 0) {
            return 1;
        }
        
        return (1 / $currentRate);
    }
    
    protected function getEuCoefficient(TransactionDto $dto): float
    {
        return $this->isEu($dto->countryAlpha2Code)
            ? static::COMMISSION_FOR_EU_ZONE 
            : static::COMMISSION_FOR_NON_EU_ZONE;
    }
    
    protected function isEu(string $countryAlpha2Code): bool
    {
        return match ($countryAlpha2Code) {
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE',
            'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK' => true,
            default => false,
        };
    }
}