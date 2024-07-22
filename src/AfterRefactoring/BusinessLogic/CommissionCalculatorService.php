<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic;

use App\AfterRefactoring\BusinessLogic\CommissionCalculator\CommissionCalculatorInterface;
use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;
use App\AfterRefactoring\BusinessLogic\Parsers\InputDataParserInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class CommissionCalculatorService implements CommissionCalculatorServiceInterface
{
    public function __construct(
        private InputDataParserInterface      $parser,
        private CommissionCalculatorInterface $calculator,
        private LoggerInterface               $logger
    )
    {}
    
    /**
     * @return array<TransactionDto>
     * @throws Exception
     */
    public function calculate(string $dataFilePath): array
    {
        $this->logger->info('*** Starting calculation **************************************************');
        
        $inputData = $this->parseInput($dataFilePath);
        $result = $this->calculateTransactionsCommission($inputData);
        
        $this->logger->info('Calculation done.');
        
        return $result;
    }
    
    /**
     * @return array<TransactionDto>
     * @throws Exception
     */
    protected function parseInput(string $dataFilePath): array
    {
        try {
            $inputDataArray = $this->parser->parse($dataFilePath);
        } catch (Throwable $e) {
            $message = 'Error while parsing input data: ' . $e->getMessage();
            $this->logger->error($message);
            throw new Exception($message);
        }
        
        return $inputDataArray;
    }
    
    /**
     * @param array<TransactionDto> $inputDataArray
     * @return array<TransactionDto>
     */
    protected function calculateTransactionsCommission(array $inputDataArray): array
    {
        $results = [];
        foreach ($inputDataArray as $transactionDto) {
            try {
                $dto = $this->buildOutputDto(
                    $transactionDto,
                    $this->calculator->calculate($transactionDto)
                );
                $results[] = $dto;
                
                $this->logger->info('Transaction processed: ' . json_encode($dto));
            } catch (Throwable $e) {
                $this->logger->error('Error while processing transaction: ', ['exception' => $e]);
            }

        }
        
        $this->logger->info(count($results) . ' commissions calculated.');
        
        return $results;
    }
    
    protected function buildOutputDto(TransactionDto $dto, float $commission): TransactionDto
    {
        return new TransactionDto(
            $dto->amount,
            $dto->currencyAlpha3Code,
            $dto->bin,
            $dto->countryAlpha2Code,
            $commission
        );
    }
}