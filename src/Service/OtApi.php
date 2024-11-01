<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Service;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\StreamWrapper;
use JsonException;
use Nextstore\SyliusOtcommercePlugin\Model\OrderAddDataXmlParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OtParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OtXmlParameters;
use Nextstore\SyliusOtcommercePlugin\Model\UserUpdateDataXmlParameters;

/**
 * Class OtApi.
 */
class OtApi
{
    private const OTAPI_URL = 'https://otapi.net/service-json/';
    /*** @var string */
    private static string $key;
    /*** @var string|null */
    private static ?string $secret = null;
    /*** @var string */
    private static string $lang;
    /*** @var Client|null */
    private static ?Client $client = null;

    private static $localeCode;

    /**
     * @return resource|string
     *
     * @throws Exception
     */
    public static function request(string $method, OtParameters $params = null, ?OtXmlParameters $xmlParams = null, ?UserUpdateDataXmlParameters $userUpdateDataXmlParameters = null, ?OrderAddDataXmlParameters $orderAddDataXmlParameters = null, bool $returnAsStream = false)
    {
        $requestUrl = self::prepareRequest($method, $params, $xmlParams, $userUpdateDataXmlParameters, $orderAddDataXmlParameters);
        
        // return $requestUrl;
        
        try {
            $response = self::$client->get($requestUrl);
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
        if ($returnAsStream) {
            return StreamWrapper::getResource($response->getBody());
        }
        $answer = (string) $response->getBody();
        // to not decode to prevent memory overuse
        // if (!str_starts_with($answer, '{"ErrorCode":"Ok"') && str_starts_with($answer, '{"ErrorCode":')) {
        //     try {
        //         $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
        //     } catch (JsonException $e) {
        //         throw new Exception('request decode error');
        //     }
        //     // if (self::getLocaleCode() == 'kk') {
        //     //     throw new Exception('Кешіріңіз, бұл өнім жойылған, қоймада жоқ немесе сатылуға қолжетімсіз.');
        //     // } else if (self::getLocaleCode() == 'ru') {
        //     //     throw new Exception('Извините, этот товар удален, отсутствует на складе или не может быть продан.');
        //     // } else {
        //     //     throw new Exception('Sorry, this product has been removed, is out of stock, or cannot be sold.');
        //     // }
        //     return $decoded;
        //     // throw new Exception($decoded['ErrorCode'].': '.$decoded['ErrorDescription']);
        // }

        return $answer;
    }

    /**
     * @return array|null
     */
    private static function prepareRequest(string $method, ?OtParameters $parameters = null, ?OtXmlParameters $xmlParams = null, ?UserUpdateDataXmlParameters $userUpdateDataXmlParameters = null, ?OrderAddDataXmlParameters $orderAddDataXmlParameters = null): string
    {
        $params = $parameters ? $parameters->getData() : [];
        self::createClient();
        if (null !== $xmlParams) {
            $params[$xmlParams->getFieldName()] = $xmlParams->createXmlParameters();
        }

        if (null !== $userUpdateDataXmlParameters) {
            $params[$userUpdateDataXmlParameters->getFieldName()] = $userUpdateDataXmlParameters->createXmlParameters();
        }

        if (null !== $orderAddDataXmlParameters) {
            $params[$orderAddDataXmlParameters->getFieldName()] = $orderAddDataXmlParameters->createXmlParameters();
        }
        $params['instanceKey'] = self::getKey();
        $params['language'] = self::getLang();
        foreach ($params as $k => $v) {
            if (is_bool($v)) {
                $params[$k] = $v ? 'true' : 'false';
            }
        }
        $time = Carbon::now('UTC');
        $params['timestamp'] = $time->format('YmdHis');
        $params['signature'] = self::sign($method, $params);

        return self::OTAPI_URL.$method.'?'.http_build_query($params);
    }

    private static function sign(string $method, array $params): string
    {
        ksort($params);
        $paramString = $method.implode('', $params).self::getSecret();

        return hash('sha256', $paramString);
    }

    private static function createClient(): void
    {
        if (null === self::$client) {
            self::$client = new Client([
                'headers' => ['Accept' => 'application/json'],
                'verify' => false,
            ]);
        }
    }

    /*** @return string */
    private static function getKey(): string
    {
        return self::$key;
    }

    /**
     * @throws Exception
     */
    public static function setKey(string $key): void
    {
        if ('' === $key) {
            throw new Exception('Wrong OTAPI key');
        }
        self::$key = $key;
    }

    /*** @return string */
    private static function getSecret(): ?string
    {
        return self::$secret;
    }

    /**
     * @throws Exception
     */
    public static function setSecret(?string $secret): void
    {
        self::$secret = $secret;
    }

    /*** @return string */
    private static function getLang(): string
    {
        return self::$lang;
    }

    /**
     * @throws Exception
     */
    public static function setLang(string $lang): void
    {
        if (strlen($lang) > 3) {
            throw new Exception('Wrong OTAPI language');
        }
        self::$lang = $lang;
    }

    /*** @return string */
    public static function getLocaleCode(): string
    {
        return self::$localeCode;
    }

    /**
     * @throws Exception
     */
    public static function setLocaleCode(string $localeCode): void
    {
        self::$localeCode = $localeCode;
    }
}
