<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Service;

use Exception;
use Nextstore\SyliusOtcommercePlugin\Model\OrderAddDataXmlParameters;
use Nextstore\SyliusOtcommercePlugin\Model\OtParameters;
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

            $item = Otapi::request('GetItemFullInfo', $otParameters);
            
            $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function authenticate()
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setUserLogin($this->parameterBag->get('ot_customer_login'));
            $otParameters->setUserPassword($this->parameterBag->get('ot_customer_password'));
            $otParameters->setRememberMe(true);

            $res = Otapi::request('Authenticate', $otParameters);
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

            $transaction = Otapi::request('PaymentPersonalAccount', $otParameters);
            
            $decoded = json_decode($transaction, true, 512, JSON_THROW_ON_ERROR);

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

            $transaction = Otapi::request('GetProviderOrdersIntegrationSessionAuthenticationInfo', $otParameters);
            
            $decoded = json_decode($transaction, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}