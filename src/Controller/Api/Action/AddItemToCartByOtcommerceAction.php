<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Controller\Api\Action;

use Nextstore\SyliusOtcommercePlugin\Service\OtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AddItemToCartByOtcommerceAction extends AbstractController
{
    public function __construct(
        private OtService $otService,
    ) {  
    }

    public function __invoke(Request $request, $tokenValue)
    {
        if ($request->query->all()) {
            $params = $request->query->all();
        } else {
            $params = json_decode($request->getContent(), true);
        }

        $res = $this->otService->getItemFullInfo($params);

        return new JsonResponse([
            'success' => true,
            'data' => $res
        ]);
    }
}