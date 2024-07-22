<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi;

use App\AfterRefactoring\Infrastructure\Enums\CurrencyEnum;
use App\AfterRefactoring\Infrastructure\ExternalApis\ExchangeRatesApi\Dtos\ExchangeRatesDto;

class ExchangeRatesApiClient implements ExchangeRatesApiClientInterface
{
    public function __construct(
        protected ExchangeRatesApiFactoryInterface $factory
    )
    {}
    
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function getRatesForEuro(): ExchangeRatesDto
    {
        try {
            if ($_ENV['EXCHANGE_RATE_API_IS_USE_LOCAL_CACHE']) {
                $cacheKey = 'ExchangeRatesApiResponse';
                $this->factory->getLogger()->debug('ExchangeRatesApiClient use local cache');
                
                if ($this->factory->getStaticCache()->isHas($cacheKey)) {
                    $cachedValue = $this->factory->getStaticCache()->get($cacheKey);
                    return $this->factory->buildExchangeRatesDto($cachedValue);
                }
            } else {
                $this->factory->getLogger()->debug('ExchangeRatesApiClient ignore local cache');
            }
            
            $ratesFromApiResponse = $this->getRatesFromApi();
            
            $this->factory->getLogger()->debug(
                sprintf('ExchangeRatesApiClient response value is %s', serialize($ratesFromApiResponse))
            );
            
            return $this->factory->buildExchangeRatesDto($ratesFromApiResponse);
        } catch (\Throwable $e) {
            $this->factory->getLogger()->error('Error while getting exchange rates', ['exception' => $e]);
            throw $e;
        }
    }
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @phpstan-ignore-next-line
     */
    protected function getRatesFromApi(): array
    {
        $response = $this->factory->getClient()->request(
            $this->factory->getLatestRatesRequest()->getMethod(),
            $this->factory->getLatestRatesRequest()->getUrl()
        )->getBody()->getContents();
        
        $this->factory->getLogger()->debug('ExchangeRatesApiClient response is ' . serialize($response));
        
        return json_decode($response, true);
    }
}