<?php
declare(strict_types=1);

namespace App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi;

use App\AfterRefactoring\Infrastructure\ExternalApis\BinListApi\Dtos\BinDto;

class BinListApiClient implements BinListApiClientInterface
{
    public function __construct(
        protected BinListApiFactoryInterface $factory
    )
    {}
    
    public function getBinById(string $id): BinDto
    {
        if ($_ENV['BIN_LIST_API_IS_USE_LOCAL_CACHE']) {
            $cacheKeyId = $_ENV['BIN_LIST_API_CACHE_KEY_PREFIX'] . $id;
            $this->factory->getLogger()->debug('BinListApiClient use local cache');
            if ($this->factory->getStaticCache()->isHas($cacheKeyId)) {
                $cachedValue = $this->factory->getStaticCache()->get($cacheKeyId);
                return $this->factory->buildBinDto($cachedValue);
            }
        } else {
            $this->factory->getLogger()->debug('BinListApiClient ignore local cache');
        }
        
        return $this->factory->buildBinDto(
            $this->getCountryCodeFromResponseData($id)
        );
    }
    
    protected function getCountryCodeFromResponseData(string $id): string
    {
        $client = $this->factory->getClient();
        $request = $this->factory->getBinByIdRequest($id);
        
        $result = $client->request(
            $request->getMethod(),
            $request->getUrl(),
        )->getBody()->getContents();
        
        $this->factory->getLogger()->debug('BinListApiClient response value is ' . serialize($result));
            
        $data = json_decode($result, true);
        
        if (empty($data['country']['alpha2'])) {
            throw new \LogicException('Country code not found');
        }
        
        return $data['country']['alpha2'];
    }
}