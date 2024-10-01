<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Service;

use Exception;
use Nextstore\SyliusOtcommercePlugin\Model\CurrencyRateHistoryGetParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OrderAddDataXmlParameters;
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

    public function getItemFullInfo(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setItemId($params['productId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('GetItemFullInfo', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getItemInfoList(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setIdsList($params['idsList']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('GetItemInfoList', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

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

            $item = Otapi::request('GetItemPrice', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

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

            $res = Otapi::request('Authenticate', $otParameters);
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function authenticateAsUser(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setUserLogin($this->parameterBag->get('ot_customer_login'));
            $otParameters->setSessionId($params['sessionId']);
            if (array_key_exists('userLogin', $params)) {
                $otParameters->setUserLogin($params['userLogin']);
            }

            $res = Otapi::request('AuthenticateAsUser', $otParameters);
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function authenticateInstanceOperator()
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setUserLogin($this->parameterBag->get('ot_user_login'));
            $otParameters->setUserPassword($this->parameterBag->get('ot_user_password'));

            $res = Otapi::request('AuthenticateInstanceOperator', $otParameters);
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUserProfileInfoList(array $params = [])
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);

            $res = Otapi::request('GetUserProfileInfoList', $otParameters);
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addUser($sessionId, array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($sessionId);

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

            $res = Otapi::request('AddUser', $otParameters, null, $userUpdateDataXmlParameters);
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addOrder(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);

            $orderAddDataXmlParameters = new OrderAddDataXmlParameters();
            if (array_key_exists('deliveryModeId', $params) && null !== $params) $orderAddDataXmlParameters->setDeliveryModeId((string) $params['deliveryModeId']);
            if (array_key_exists('comment', $params) && null !== $params) $orderAddDataXmlParameters->setComment((string) $params['comment']);
            if (array_key_exists('userProfileId', $params) && null !== $params) $orderAddDataXmlParameters->setUserProfileId((int) $params['userProfileId']);
            if (array_key_exists('items', $params) && null !== $params) $orderAddDataXmlParameters->setItems($params['items']);

            $order = Otapi::request('AddOrder', $otParameters, null, null, $orderAddDataXmlParameters);
            
            $decoded = json_decode($order, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Deposit funds to customer account
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

            $transaction = Otapi::request('PostTransaction', $otParameters);
            
            $decoded = json_decode($transaction, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function paymentPersonalAccount(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setAmount($params['amount']);
            $otParameters->setSalesId($params['salesId']);

            $res = Otapi::request('PaymentPersonalAccount', $otParameters);
            
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getProviderOrdersIntegrationSessionAuthenticationInfo(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);
            $otParameters->setReturnUrl($params['returnUrl']);

            $res = Otapi::request('GetProviderOrdersIntegrationSessionAuthenticationInfo', $otParameters);
            
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getProviderOrdersIntegrationSessionInfoList(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);

            $res = Otapi::request('GetProviderOrdersIntegrationSessionInfoList', $otParameters);
            
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function runOrderExportingToProvider(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);
            $otParameters->setProviderSessionId($params['providerSessionId']);

            $runOrderXmlParameters = new RunOrderExportingToProviderXmlParameters();
            $runOrderXmlParameters->setOrderId($params['orderId']);

            $xmlParameters = new OtXmlParameters();
            $xmlParameters->setFieldName('xmlParameters');
            $xmlParameters->setType('Parameters');
            $xmlParameters->setXmlData($runOrderXmlParameters->createXmlParameters());

            $res = Otapi::request('RunOrderExportingToProvider', $otParameters, $xmlParameters);
            
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function runOrdersSynchronizingWithProvider(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setProviderType($params['providerType']);
            $otParameters->setProviderSessionId($params['providerSessionId']);

            $runOrderXmlParameters = new RunOrderExportingToProviderXmlParameters();
            $runOrderXmlParameters->setOrderIds($params['orderIds']);

            $xmlParameters = new OtXmlParameters();
            $xmlParameters->setFieldName('xmlParameters');
            $xmlParameters->setType('Parameters');
            $xmlParameters->setXmlData($runOrderXmlParameters->createXmlParameters());

            $res = Otapi::request('RunOrdersSynchronizingWithProvider', $otParameters, $xmlParameters);
            
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getVendorInfo(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setVendorId($params['vendorId']);

            $item = Otapi::request('GetVendorInfo', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSalesOrderDetails(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setSalesId($params['salesId']);

            $res = Otapi::request('GetSalesOrderDetails', $otParameters);
            
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

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

            $res = Otapi::request('GetCurrencyRateHistory', $otParameters, $xmlParameters);
            
            $decoded = json_decode($res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('GetBasket', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getPartialBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setElements($params['elements']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('GetPartialBasket', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function clearBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('ClearBasket', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

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

            $item = Otapi::request('AddItemToBasket', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function editBasketItemQuantity(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setElementId($params['elementId']);
            $otParameters->setQuantity($params['quantity']);
            $otParameters->setFieldParameters($params['fieldParameters']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('EditBasketItemQuantity', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function batchSimplifiedAddItemsToBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setItemId($params['itemId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('BatchSimplifiedAddItemsToBasket', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function removeItemFromBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setElementId($params['elementId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('RemoveItemFromBasket', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function removeItemsFromBasket(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setElements($params['elements']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('RemoveItemsFromBasket', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function runBasketChecking(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setElements($params['elements']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('RunBasketChecking', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBasketCheckingResult(array $params)
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setSessionId($params['sessionId']);
            $otParameters->setActivityId($params['activityId']);
            if (array_key_exists('language', $params)) {
                OtApi::setLang($params['language']);
            }

            $item = Otapi::request('GetBasketCheckingResult', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}