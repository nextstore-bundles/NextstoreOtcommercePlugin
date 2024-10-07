<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Factory\Product;

use App\Entity\Product\ProductVariantTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductVariantInterface as ModelProductVariantInterface;
use Nextstore\SyliusOtcommercePlugin\Service\OtResponse;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface as ModelProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class VariantFactory implements ProductVariantFactoryInterface
{
    public function __construct(
        private ProductVariantFactoryInterface $decoratedFactory,
        private EntityManagerInterface $entityManager,
        private ChannelContextInterface $channelContext,
        private OtResponse $otResponse,
        private FactoryInterface $channelPricingFactory,
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
        if ($configuredItem === null) {
            $configuredItem = $itemInfo;
            $attributeInfo = ['value' => $itemInfo['Title'], 'imageUrl' => $itemInfo['MainPictureUrl']];
        } else {
            $attributeInfo = $this->otResponse->findAttributeInfo($itemInfo['Attributes'], $configuredItem);
        }
        // Tmall baraa bwal
        $promotionPrice = 0;
        if (!in_array('Tmall', $itemInfo['Features'])) {
            $promotionPrice = $this->otResponse->findMinPromotionPrice($itemInfo, $configuredItem);
        }

        /** @var ModelProductVariantInterface $variant */
        $variant = $this->createForProduct($product);
        $variant->setCode($configuredItem['Id']);
        $this->modifyTranslation($variant, $params, $attributeInfo);
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
        if ($configuredItem === null) {
            $configuredItem = $itemInfo;
            $attributeInfo = ['value' => $itemInfo['Title'], 'imageUrl' => $itemInfo['MainPictureUrl']];
        } else {
            $attributeInfo = $this->otResponse->findAttributeInfo($itemInfo['Attributes'], $configuredItem);
        }
        // Tmall baraa bwal
        $promotionPrice = 0;
        if (!in_array('Tmall', $itemInfo['Features'])) {
            $promotionPrice = $this->otResponse->findMinPromotionPrice($itemInfo, $configuredItem);
        }

        $this->modifyTranslation($variant, $params, $attributeInfo);
        $variant->setImageUrl($attributeInfo['imageUrl']);
        $cp = $variant->getChannelPricingForChannel($this->channelContext->getChannel());
        $this->updateChannelPricing($configuredItem, $promotionPrice, $cp);

        $this->entityManager->persist($variant);
        $this->entityManager->flush();

        return $variant;
    }

    private function createChannelPricing($configuredItem, $promotionPrice): ChannelPricingInterface
    {
        $oneItemPriceWithoutDelivery = $configuredItem['Price']['ConvertedPriceList']['Internal']['Price'];

        $originalPrice = (int) $oneItemPriceWithoutDelivery * 100;
        $price = (int) ($oneItemPriceWithoutDelivery * 100);
        if ($promotionPrice > 0) {
            $price = $promotionPrice;
        }

        $channel = $this->channelContext->getChannel();
        $cp = $this->channelPricingFactory->createNew();
        $cp->setChannelCode($channel->getCode());
        $cp->setMinimumPrice(0);
        $cp->setOriginalPrice($originalPrice);      // Үндсэн үнэ
        $cp->setPrice($price);                      // Зарагдах үнэ (хямдарсан)

        $this->entityManager->persist($cp);

        return $cp;
    }

    private function updateChannelPricing($configuredItem, $promotionPrice, ChannelPricingInterface $cp): ChannelPricingInterface
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

    private function modifyTranslation(ModelProductVariantInterface $variant, $params, $attributeInfo)
    {
        $translation = $variant->getTranslation($params['localeCode']);
        if ($translation->getLocale() === $params['localeCode']) {
            $translation->setName($attributeInfo['value']);
            $this->entityManager->persist($translation);
        } else {
            $newTranslation = new ProductVariantTranslation();
            $newTranslation->setName($attributeInfo['value']);
            $newTranslation->setLocale($params['localeCode']);
            $this->entityManager->persist($newTranslation);
            $variant->addTranslation($newTranslation);
            $this->entityManager->persist($variant);
        }
    }
}