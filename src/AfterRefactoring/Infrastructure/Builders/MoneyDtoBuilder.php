<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\Builders;

use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;
use App\AfterRefactoring\BusinessLogic\Repositories\BinRepository;
use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\BinDto;
use Psr\Log\LoggerInterface;

class MoneyDtoBuilder implements MoneyDtoBuilderInterface
{
    public function  __construct(
        protected BinRepository $repository,
        protected LoggerInterface $logger
    )
    {}
    
    public function build(float $amount, string $currency, string $bin): TransactionDto
    {
        try {
            $binDto = $this->repository->getBinById($bin);
        } catch (\Throwable $e) {
            throw new \LogicException('Invalid BIN: ' . $bin . '. ' . $e->getMessage());
        }
        
        $this->logger->info(
            sprintf(
                'BIN %s is valid. It from country %s',
                $bin,
                $binDto->countryAlpha2Code
            )
        );
        
        return new TransactionDto(
            $amount, 
            $currency, 
            $bin,
            $binDto->countryAlpha2Code
        );
    }
}