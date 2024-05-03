<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Service;

use Exception;
use Nextstore\SyliusOtcommercePlugin\Model\OtParameters;
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

    public function authenticateInstanceOperator()
    {
        try {
            $otParameters = new OtParameters();
            $otParameters->setUserLogin($this->parameterBag->get('ot_user_login'));
            $otParameters->setUserPassword($this->parameterBag->get('ot_user_password'));

            $res = Otapi::request('AuthenticateInstanceOperator', $otParameters);
            $decoded = json_decode($$res, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}