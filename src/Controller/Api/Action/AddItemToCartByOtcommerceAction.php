<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Controller\Api\Action;

use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductVariantInterface;
use Nextstore\SyliusOtcommercePlugin\Factory\Product\ProductFactory;
use Nextstore\SyliusOtcommercePlugin\Factory\Product\VariantFactory;
use Nextstore\SyliusOtcommercePlugin\Service\OtService;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Webmozart\Assert\Assert;

class AddItemToCartByOtcommerceAction extends AbstractController
{
    public function __construct(
        private OtService $otService,
        private ProductFactory $productFactory,
        private VariantFactory $variantFactory,
        private FactoryInterface $orderItemFactory,
        private EntityManagerInterface $entityManager,
        private OrderProcessorInterface $orderProcessor,
        private OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        private NormalizerInterface $orderNormalizer,
    ) {  
    }

    public function __invoke(Request $request, $tokenValue)
    {
        if ($request->query->all()) {
            $params = $request->query->all();
        } else {
            $params = json_decode($request->getContent(), true);
        }

        try {
            // Create or Update Product from OT
            $itemInfo = $this->otService->getItemFullInfo($params);
            Assert::true(isset($itemInfo['OtapiItemFullInfo']), $itemInfo);
            $product = $this->entityManager->getRepository(ProductInterface::class)->findOneBy(['code' => $params['productId']]);
            if (!$product instanceof ProductInterface) $product = $this->productFactory->createProductFromOt($itemInfo['OtapiItemFullInfo'], $params);
            else $product = $this->productFactory->updateProductFromOt($itemInfo['OtapiItemFullInfo'], $params, $product);
            
            $variant = $this->entityManager->getRepository(ProductVariantInterface::class)->findOneBy(['code' => $params['configuredItemId']]);
            if (!$variant instanceof ProductVariantInterface) $variant = $this->variantFactory->createVariantFromOt($itemInfo['OtapiItemFullInfo'], $params, $product);
            else $variant = $this->variantFactory->updateVariantFromOt($itemInfo['OtapiItemFullInfo'], $params, $variant);

            // Add Item to Order
            /** @var Order $order */
            $order = $this->entityManager->getRepository(Order::class)->findOneBy(['tokenValue' => $tokenValue]);
            Assert::isInstanceOf($order, Order::class, 'Order not found!');

            $orderItems = $order->getItems()->filter(function (OrderItem $item) use ($variant): bool {
                if ($item->getVariant() != null) {
                    return $variant->getCode() === $item->getVariant()->getCode();
                }

                return $variant->getCode() === $item->getProduct()->getCode();
            });

            if (count($orderItems) > 0) {
                /** @var OrderItem $orderItem */
                foreach ($orderItems as $orderItem) {
                    $targetQuantity = $orderItem->getQuantity() + $params['quantity'];
                    $this->orderItemQuantityModifier->modify($orderItem, $targetQuantity);
                }
            } else {
                $orderItem = $this->orderItemFactory->createNew();
                $orderItem->setVariant($variant);
                $this->orderItemQuantityModifier->modify($orderItem, $params['quantity']);
                $order->addItem($orderItem);
            }

            $this->orderProcessor->process($order);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }

        return new JsonResponse([
            'success' => true,
            'data' => $this->orderNormalizer->normalize($order)
        ]);
    }
}