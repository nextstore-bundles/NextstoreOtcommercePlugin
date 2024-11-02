<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Service;

use Exception;
use JsonException;
use Nextstore\SyliusOtcommercePlugin\Model\CurrencyRateHistoryGetParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OrderAddDataXmlParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OrderCreateDataXmlParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OtParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OtXmlParameters;
use Nextstore\SyliusOtcommercePlugin\Model\RunOrderExportingToProviderXmlParameters;
use Nextstore\SyliusOtcommercePlugin\Model\UserUpdateDataXmlParameters;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OtService
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
        OtApi::setKey($this->parameterBag->get('ot_key'));
        OtApi::setSecret($this->parameterBag->get('ot_secret'));
        OtApi::setLang($this->parameterBag->get('ot_lang'));
    }

    // Called from Admin & Front
    public function getItemFullInfo(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setItemId($params['productId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $answer = Otapi::request('GetItemFullInfo', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);

            if ($decoded['ErrorCode'] != "Ok") {
                if (OtApi::getLocaleCode() == 'kk') {
                    throw new Exception('Кешіріңіз, бұл өнім жойылған, қоймада жоқ немесе сатылуға қолжетімсіз.');
                } else if (OtApi::getLocaleCode() == 'ru') {
                    throw new Exception('Извините, этот товар удален, отсутствует на складе или не может быть продан.');
                } else {
                    throw new Exception('Sorry, this product has been removed, is out of stock, or cannot be sold.');
                }
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from command
    public function getItemPrice(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setQuantity($params['quantity']);
            $otParameters->setItemId($params['itemId']);
            $otParameters->setPromotionId($params['promotionId']);
            $otParameters->setConfigurationId($params['configurationId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('GetItemPrice', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);

            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front, command, and Admin
    public function authenticate(array $params = [])
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setUserLogin($this->parameterBag->get('ot_customer_login'));
            $otParameters->setUserPassword($this->parameterBag->get('ot_customer_password'));
            if (array_key_exists('userLogin', $params) && array_key_exists('userPassword', $params)) {
                $otParameters->setUserLogin($params['userLogin']);
                $otParameters->setUserPassword($params['userPassword']);
            }
            $otParameters->setRememberMe(true);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('Authenticate', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);

            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from command
    public function authenticateAsUser(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setUserLogin($this->parameterBag->get('ot_customer_login'));
            $otParameters->setSessionId($params['sessionId']);
            if (array_key_exists('userLogin', $params)) {
                $otParameters->setUserLogin($params['userLogin']);
            }
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('AuthenticateAsUser', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);

            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Admin & Front
    public function authenticateInstanceOperator()
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setUserLogin($this->parameterBag->get('ot_user_login'));
            $otParameters->setUserPassword($this->parameterBag->get('ot_user_password'));
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('AuthenticateInstanceOperator', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);

            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function getUserProfileInfoList(array $params = [])
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('GetUserProfileInfoList', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function addUser($sessionId, array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($sessionId);
            OtApi::setLang(OtApi::getLocaleCode());

            $userUpdateDataXmlParameters = new UserUpdateDataXmlParameters();
            if (array_key_exists('id', $params) && null !== $params) $userUpdateDataXmlParameters->setId((int) $params['id']);
            if (array_key_exists('login', $params) && null !== $params) $userUpdateDataXmlParameters->setLogin((string) $params['login']);
            if (array_key_exists('email', $params) && null !== $params) $userUpdateDataXmlParameters->setEmail((string) $params['email']);
            if (array_key_exists('password', $params) && null !== $params) $userUpdateDataXmlParameters->setPassword((string) $params['password']);
            if (array_key_exists('additionalInfo', $params) && null !== $params) $userUpdateDataXmlParameters->setAdditionalInfo((string) $params['additionalInfo']);
            if (array_key_exists('firstName', $params) && null !== $params) $userUpdateDataXmlParameters->setFirstName((string) $params['firstName']);
            if (array_key_exists('lastName', $params) && null !== $params) $userUpdateDataXmlParameters->setLastName((string) $params['lastName']);
            if (array_key_exists('middleName', $params) && null !== $params) $userUpdateDataXmlParameters->setMiddleName((string) $params['middleName']);
            if (array_key_exists('sex', $params) && null !== $params) $userUpdateDataXmlParameters->setSex((string) $params['sex']);
            if (array_key_exists('countryCode', $params) && null !== $params) $userUpdateDataXmlParameters->setCountryCode((string) $params['countryCode']);
            if (array_key_exists('country', $params) && null !== $params) $userUpdateDataXmlParameters->setCountry((string) $params['country']);
            if (array_key_exists('city', $params) && null !== $params) $userUpdateDataXmlParameters->setCity((string) $params['city']);
            if (array_key_exists('address', $params) && null !== $params) $userUpdateDataXmlParameters->setAddress((string) $params['address']);
            if (array_key_exists('postalCode', $params) && null !== $params) $userUpdateDataXmlParameters->setPostalCode((string) $params['postalCode']);
            if (array_key_exists('region', $params) && null !== $params) $userUpdateDataXmlParameters->setRegion((string) $params['region']);
            if (array_key_exists('recipientFirstName', $params) && null !== $params) $userUpdateDataXmlParameters->setRecipientFirstName((string) $params['recipientFirstName']);
            if (array_key_exists('recipientLastName', $params) && null !== $params) $userUpdateDataXmlParameters->setRecipientLastName((string) $params['recipientLastName']);
            if (array_key_exists('recipientMiddleName', $params) && null !== $params) $userUpdateDataXmlParameters->setRecipientMiddleName((string) $params['recipientMiddleName']);
            if (array_key_exists('phone', $params) && null !== $params) $userUpdateDataXmlParameters->setPhone((string) $params['phone']);
            if (array_key_exists('skype', $params) && null !== $params) $userUpdateDataXmlParameters->setSkype((string) $params['skype']);

            $answer = Otapi::request('AddUser', $otParameters, null, $userUpdateDataXmlParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function addOrder(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $orderAddDataXmlParameters = new OrderAddDataXmlParameters();
            if (array_key_exists('deliveryModeId', $params) && null !== $params) $orderAddDataXmlParameters->setDeliveryModeId((string) $params['deliveryModeId']);
            if (array_key_exists('comment', $params) && null !== $params) $orderAddDataXmlParameters->setComment((string) $params['comment']);
            if (array_key_exists('userProfileId', $params) && null !== $params) $orderAddDataXmlParameters->setUserProfileId((int) $params['userProfileId']);
            if (array_key_exists('items', $params) && null !== $params) $orderAddDataXmlParameters->setItems($params['items']);

            $answer = Otapi::request('AddOrder', $otParameters, null, null, $orderAddDataXmlParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Deposit funds to customer account
    // Called from Front
    public function postTransaction(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setCustomerId($params['customerId']);
            $otParameters->setAmount($params['amount']);
            $otParameters->setComment($params['comment']);
            $otParameters->setIsDebit($params['isDebit']);
            $otParameters->setTransactionDate($params['transactionDate']);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('PostTransaction', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function paymentPersonalAccount(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setAmount($params['amount']);
            $otParameters->setSalesId($params['salesId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('PaymentPersonalAccount', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Admin Order
    public function getProviderOrdersIntegrationSessionAuthenticationInfo(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);
            $otParameters->setReturnUrl($params['returnUrl']);
            OtApi::setLang('en');

            $answer = Otapi::request('GetProviderOrdersIntegrationSessionAuthenticationInfo', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from command & service
    public function getProviderOrdersIntegrationSessionInfoList(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('GetProviderOrdersIntegrationSessionInfoList', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function runOrderExportingToProvider(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);
            $otParameters->setProviderSessionId($params['providerSessionId']);

            $runOrderXmlParameters = new RunOrderExportingToProviderXmlParameters();
            $runOrderXmlParameters->setOrderId($params['orderId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $xmlParameters = new OtXmlParameters();
            $xmlParameters->setFieldName('xmlParameters');
            $xmlParameters->setType('Parameters');
            $xmlParameters->setXmlData($runOrderXmlParameters->createXmlParameters());

            $answer = Otapi::request('RunOrderExportingToProvider', $otParameters, $xmlParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from command
    public function runOrdersSynchronizingWithProvider(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);
            $otParameters->setProviderSessionId($params['providerSessionId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $runOrderXmlParameters = new RunOrderExportingToProviderXmlParameters();
            $runOrderXmlParameters->setOrderIds($params['orderIds']);

            $xmlParameters = new OtXmlParameters();
            $xmlParameters->setFieldName('xmlParameters');
            $xmlParameters->setType('Parameters');
            $xmlParameters->setXmlData($runOrderXmlParameters->createXmlParameters());

            $answer = Otapi::request('RunOrdersSynchronizingWithProvider', $otParameters, $xmlParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function getVendorInfo(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setVendorId($params['vendorId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('GetVendorInfo', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from command
    public function getSalesOrderDetails(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setSalesId($params['salesId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('GetSalesOrderDetails', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from command
    public function getCurrencyRateHistory(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);

            $currencyRateHistoryGetParameters = new CurrencyRateHistoryGetParameters();
            $currencyRateHistoryGetParameters->setFirstCurrencyCode($params['firstCurrencyCode']);
            $currencyRateHistoryGetParameters->setSecondCurrencyCode($params['secondCurrencyCode']);
            $currencyRateHistoryGetParameters->setDateFrom($params['dateFrom']);
            $currencyRateHistoryGetParameters->setDateTo($params['dateTo']);

            $xmlParameters = new OtXmlParameters();
            $xmlParameters->setFieldName('xmlParameters');
            $xmlParameters->setType('CurrencyRateHistoryGetParameters');
            $xmlParameters->setXmlData($currencyRateHistoryGetParameters->createXmlParameters());
            OtApi::setLang('en');

            $answer = Otapi::request('GetCurrencyRateHistory', $otParameters, $xmlParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);

            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function clearBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('ClearBasket', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from FRONT
    public function addItemToBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setItemId($params['itemId']);
            $otParameters->setConfigurationId($params['configurationId']);
            $otParameters->setQuantity($params['quantity']);
            $otParameters->setFieldParameters($params['fieldParameters']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $answer = Otapi::request('AddItemToBasket', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            // if ($decoded['ErrorCode'] != "Ok") {
            //     if (OtApi::getLocaleCode() == 'kk') {
            //         throw new Exception('Кешіріңіз, бұл өнім жойылған, қоймада жоқ немесе сатылуға қолжетімсіз.');
            //     } else if (OtApi::getLocaleCode() == 'ru') {
            //         throw new Exception('Извините, этот товар удален, отсутствует на складе или не может быть продан.');
            //     } else {
            //         throw new Exception('Sorry, this product has been removed, is out of stock, or cannot be sold.');
            //     }
            // }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function runBasketChecking(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setElements($params['elements']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('RunBasketChecking', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Called from Front
    public function getBasketCheckingResult(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setActivityId($params['activityId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('GetBasketCheckingResult', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function setLocaleCode($localeCode)
    {
        OtApi::setLocaleCode($localeCode);
    }

    public function createOrder(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $orderCreateDataXmlParameters = new OrderCreateDataXmlParameters();
            if (array_key_exists('weight', $params) && null !== $params) $orderCreateDataXmlParameters->setWeight((float) $params['weight']);
            if (array_key_exists('elements', $params) && null !== $params) $orderCreateDataXmlParameters->setElements($params['elements']);
            if (array_key_exists('deliveryModeId', $params) && null !== $params) $orderCreateDataXmlParameters->setDeliveryModeId((string) $params['deliveryModeId']);
            if (array_key_exists('comment', $params) && null !== $params) $orderCreateDataXmlParameters->setComment((string) $params['comment']);
            if (array_key_exists('userProfileId', $params) && null !== $params) $orderCreateDataXmlParameters->setUserProfileId((int) $params['userProfileId']);

            $answer = Otapi::request('CreateOrder', $otParameters, null, null, null, $orderCreateDataXmlParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBasket(array $params = [])
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            OtApi::setLang(OtApi::getLocaleCode());

            $answer = Otapi::request('GetBasket', $otParameters);
            $decoded = json_decode($answer, true, 512, JSON_THROW_ON_ERROR);
            if ($decoded['ErrorCode'] != "Ok") {
                throw new Exception($decoded['ErrorDescription']);
            }

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}