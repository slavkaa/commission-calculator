<?php
declare(strict_types=1);

namespace App\AfterRefactoring;

use App\AfterRefactoring\BusinessLogic\CommissionCalculatorFactory;

class Bootstrap
{
    protected CommissionCalculatorFactory $commissionCalculatorFactory;
    
    public function init(): void
    {
        $this->loadEnv();
        $this->initMainFactories();
        $this->doSelfTest();
    }
    
    /**
     * @return string[]
     */
    protected function getListOfEnvFilesToLoad(): array
    {
        return ['.env'];
    }
    
    protected function loadEnv(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(
            __DIR__. '/../../',
            $this->getListOfEnvFilesToLoad()
        );
        $dotenv->load();
        
        $dotenv->ifPresent('LOG_LEVEL');
        
        $dotenv->ifPresent('BIN_LIST_API_BASE_URL');
        $dotenv->ifPresent('BIN_LIST_API_CACHE_KEY_PREFIX');
        $dotenv->ifPresent('BIN_LIST_API_IS_USE_LOCAL_CACHE')->isBoolean();
        $dotenv->ifPresent('BIN_LIST_API_LOG_LEVEL');
        
        $dotenv->ifPresent('EXCHANGE_RATE_API_BASE_URL');
        $dotenv->ifPresent('EXCHANGE_RATE_API_KEY');
        $dotenv->ifPresent('EXCHANGE_RATE_API_CACHE_KEY');
        $dotenv->ifPresent('EXCHANGE_RATE_API_IS_USE_LOCAL_CACHE')->isBoolean();
        $dotenv->ifPresent('EXCHANGE_RATE_API_CACHE_KEY');
    }
    
    protected function initMainFactories(): void
    {
        $this->initCommissionCalculatorFactory();
    }
    
    protected function initCommissionCalculatorFactory(): void
    {
        $this->commissionCalculatorFactory = new CommissionCalculatorFactory();
    }
    
    protected function doSelfTest(): void
    {
        $this->checkEnv();
    }
    
    protected function checkEnv(): void
    {
        $logger = $this->commissionCalculatorFactory->getLogger();
        
        $logger->info('*** INIT APP **************************************************');
        $logger->debug('BIN_LIST_API_BASE_URL is ' . $_ENV['BIN_LIST_API_BASE_URL']);
        $logger->debug('BIN_LIST_API_CACHE_KEY_PREFIX is ' . $_ENV['BIN_LIST_API_CACHE_KEY_PREFIX']);
        $logger->debug('BIN_LIST_API_IS_USE_LOCAL_CACHE is ' . $_ENV['BIN_LIST_API_IS_USE_LOCAL_CACHE']);
        $logger->debug('EXCHANGE_RATE_API_BASE_URL is ' . $_ENV['EXCHANGE_RATE_API_BASE_URL']);
        $logger->debug('EXCHANGE_RATE_API_KEY is ' . $_ENV['EXCHANGE_RATE_API_KEY']);
        $logger->debug('EXCHANGE_RATE_API_CACHE_KEY is ' . $_ENV['EXCHANGE_RATE_API_CACHE_KEY']);
        $logger->debug('EXCHANGE_RATE_API_IS_USE_LOCAL_CACHE is ' . $_ENV['EXCHANGE_RATE_API_IS_USE_LOCAL_CACHE']);
    }
    
    public function getCommissionCalculatorFactory(): CommissionCalculatorFactory
    {
        return $this->commissionCalculatorFactory;
    }
}