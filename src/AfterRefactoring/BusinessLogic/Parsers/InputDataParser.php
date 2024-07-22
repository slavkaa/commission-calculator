<?php
declare(strict_types=1);

namespace App\AfterRefactoring\BusinessLogic\Parsers;

use App\AfterRefactoring\BusinessLogic\Dtos\TransactionDto;
use App\AfterRefactoring\Infrastructure\Builders\MoneyDtoBuilderInterface;
use Psr\Log\LoggerInterface;

class InputDataParser implements InputDataParserInterface
{
    public function __construct(
        protected MoneyDtoBuilderInterface $moneyDtoBuilder,
        protected LoggerInterface $logger
    )
    {}
    
    /**
     * @return array<TransactionDto>
     */
    public function parse(string $dataFilePath): array
    {
        $this->logger->debug('Start parsing input data from <project root dir>/' . $dataFilePath);
        
        $lines = $this->readFile($dataFilePath);
        $result = $this->parseInputData($lines);
        
        return $this->cleanUpResult($result);
    }
    
    /**
     * @return array<string>
     */
    protected function readFile(string $filename): array
    {
        try {
            return explode(
                "\n", 
                (string) file_get_contents($this->getRootDir() . $filename)
            );
        } catch (\Throwable $e) {
            $this->logger->error('Error while reading file: ', ['exception' => $e]);
        }
        
        return [];
    }
    
    /**
     * @param array<string> $lines
     * @return array<TransactionDto>
     */
    protected function parseInputData(array $lines): array
    {
        $this->logger->info(count($lines) . ' item come into parser');
        
        $result = [];
        foreach ($lines as $line) {
            try {
                $result[] = $this->parseLine($line);
            } catch (\Throwable $e) {
                $this->logger->error('Error while parsing input data: ', ['exception' => $e]);
            }
        }
        
        return $result;
    }
    
    /**
     * @param array<TransactionDto> $result
     * @return array<TransactionDto>
     */
    protected function cleanUpResult(array &$result): array
    {
        try {
            // @phpstan-ignore-next-line
            array_filter($result, function($item){ return empty($item); });
        } catch (\Throwable $e) {
            $this->logger->error('Error while cleaning up result: ', ['exception' => $e]);
        }
        
        return $result;
    }
    
    protected function parseLine(string $line): TransactionDto
    {
        if (!empty($line)) {
            $data = json_decode($line, true);
            
            return $this->createMoneyDto(
                (float) $data['amount'],
                $data['currency'],
                $data['bin']
            );
        }
        
        return $this->createMoneyDto(0, '', '');
    }
    
    protected function createMoneyDto(float $amount, string $currency, string $bin): TransactionDto
    {
        return $this->moneyDtoBuilder->build($amount, $currency, $bin);
    }
    
    protected function getRootDir(): string
    {
        return __DIR__ . '/../../../../';
    }
}