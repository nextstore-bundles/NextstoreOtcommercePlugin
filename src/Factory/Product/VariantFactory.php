<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Factory\Product;

use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductVariantInterface as ModelProductVariantInterface;
use Nextstore\SyliusOtcommercePlugin\Service\OtResponse;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelPricing;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface as ModelProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

class VariantFactory implements ProductVariantFactoryInterface
{
    public function __construct(
        private ProductVariantFactoryInterface $decoratedFactory,
        private EntityManagerInterface $entityManager,
        private ChannelContextInterface $channelContext,
        private OtResponse $otResponse,
    ) {
    }

    public function createNew(): ProductVariant
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForProduct(ModelProductInterface $product): ProductVariantInterface
    {
        return $this->decoratedFactory->createForProduct($product);
    }

    public function createVariantFromOt($itemInfo, $params, $product)
    {
        $configuredItem = $this->otResponse->findConfiguredItem($itemInfo['ConfiguredItems'], $params['configuredItemId']);
        $attributeInfo = $this->otResponse->findAttributeInfo($itemInfo['Attributes'], $configuredItem);
        $promotionPrice = $this->otResponse->findMinPromotionPrice($itemInfo, $configuredItem);

        /** @var ModelProductVariantInterface $variant */
        $variant = $this->createForProduct($product);
        $variant->setCode($configuredItem['Id']);
        $variant->setName($attributeInfo['value']);
        $variant->setImageUrl($attributeInfo['imageUrl']);

        $cp = $this->createChannelPricing($configuredItem, $promotionPrice);
        $cp->setProductVariant($variant);
        $variant->addChannelPricing($cp);

        $this->entityManager->persist($variant);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $variant;
    }

    public function updateVariantFromOt($itemInfo, $params, ModelProductVariantInterface $variant)
    {
        $configuredItem = $this->otResponse->findConfiguredItem($itemInfo['ConfiguredItems'], $params['configuredItemId']);
        $attributeInfo = $this->otResponse->findAttributeInfo($itemInfo['Attributes'], $configuredItem);
        $promotionPrice = $this->otResponse->findMinPromotionPrice($itemInfo, $configuredItem);

        $variant->setName($attributeInfo['value']);
        $variant->setImageUrl($attributeInfo['imageUrl']);
        $cp = $variant->getChannelPricingForChannel($this->channelContext->getChannel());
        $this->updateChannelPricing($configuredItem, $promotionPrice, $cp);

        $this->entityManager->persist($variant);
        $this->entityManager->flush();

        return $variant;
    }

    private function createChannelPricing($configuredItem, $promotionPrice): ChannelPricing
    {
        $oneItemPriceWithoutDelivery = $configuredItem['Price']['ConvertedPriceList']['Internal']['Price'];

        $originalPrice = (int) $oneItemPriceWithoutDelivery * 100;
        $price = $promotionPrice;        

        $channel = $this->channelContext->getChannel();
        $cp = new ChannelPricing();
        $cp->setChannelCode($channel->getCode());
        $cp->setMinimumPrice(0);
        $cp->setOriginalPrice($originalPrice);      // Үндсэн үнэ
        $cp->setPrice($price);                      // Зарагдах үнэ (хямдарсан)

        $this->entityManager->persist($cp);

        return $cp;
    }

    private function updateChannelPricing($configuredItem, $promotionPrice, ChannelPricing $cp): ChannelPricing
    {
        $oneItemPriceWithoutDelivery = $configuredItem['Price']['ConvertedPriceList']['Internal']['Price'];

        $originalPrice = (int) $oneItemPriceWithoutDelivery * 100;
        $price = $promotionPrice;        

        $cp->setMinimumPrice(0);
        $cp->setOriginalPrice($originalPrice);      // Үндсэн үнэ
        $cp->setPrice($price);                      // Зарагдах үнэ (хямдарсан)

        $this->entityManager->persist($cp);

        return $cp;
    }
}