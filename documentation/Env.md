# ENV variables

ENV variables are used to configure the application. 
They stored classically: in the `.env` file in the root of the project.
To set custom ENVs for tests use `.env.test` file.

| Parameter                            | Explanation                                                               |
|--------------------------------------|---------------------------------------------------------------------------|
| LOG_LEVEL                            | https://www.php-fig.org/psr/psr-3/                                        |
| BIN_LIST_API_BASE_URL                | API base URL                                                              |
| BIN_LIST_API_CACHE_KEY_PREFIX        | Used to combine cache key aka 'BinListApiResponse.'{binId}                |
| BIN_LIST_API_IS_USE_LOCAL_CACHE      | 0 - not use, 1 - use                                                      |
| BIN_LIST_API_LOG_LEVEL               | https://www.php-fig.org/psr/psr-3/                                        |
| EXCHANGE_RATE_API_BASE_URL           | API base URL                                                              |
| EXCHANGE_RATE_API_KEY                | Please replace with your value                                            |
| EXCHANGE_RATE_API_CACHE_KEY          | 'ExchangeRatesApiResponse' - only one responce can be in cache by default |
| EXCHANGE_RATE_API_IS_USE_LOCAL_CACHE | 0 - not use, 1 - use                                                      |
| EXCHANGE_RATE_API_LEVEL              | https://www.php-fig.org/psr/psr-3/                                        |