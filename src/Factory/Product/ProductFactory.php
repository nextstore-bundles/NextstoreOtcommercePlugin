<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Factory\Product;

use App\Entity\Product\ProductTranslation;
use App\Entity\Product\ProductVariantTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductInterface as ModelProductInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductVariantInterface;
use Nextstore\SyliusOtcommercePlugin\Service\OtResponse;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelPricing;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ProductFactory implements ProductFactoryInterface
{
    public function __construct(
        private ProductFactoryInterface $decoratedFactory,
        private EntityManagerInterface $entityManager,
        private ChannelContextInterface $channelContext,
        private OtResponse $otResponse,
        private FactoryInterface $channelPricingFactory,
    ) {
    }

    public function createNew(): ProductInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createWithVariant(): ProductInterface
    {
        return $this->decoratedFactory->createWithVariant();
    }

    public function createProductFromOt($itemInfo, $params)
    {
        /** @var ModelProductInterface $product */
        $product = $this->createWithVariant();
        $product->setCode($itemInfo['Id']);
        $this->modifyTranslation($product, $params, $itemInfo['Title'], $itemInfo['Id']);
        $product->setExternalProductId($itemInfo['Id']);
        $product->setExternalVendorId($itemInfo['VendorId']);
        $product->setWebUrl($itemInfo['ExternalItemUrl']);
        $product->setImageUrl($itemInfo['MainPictureUrl']);
        $product->setProviderType($itemInfo['ProviderType']);

        $configuredItem = $this->otResponse->findConfiguredItem($itemInfo['ConfiguredItems'], $params['configuredItemId']);
        if ($configuredItem === null) {
            $configuredItem = $itemInfo;
            $attributeInfo = ['value' => $itemInfo['Title'], 'imageUrl' => $itemInfo['MainPictureUrl']];
        } else {
            $attributeInfo = $this->otResponse->findAttributeInfo($itemInfo['Attributes'], $configuredItem);
        }
        
        // Tmall baraa bwal
        $promotionPrice = 0;
        if (in_array('Tmall', $itemInfo['Features'])) {
            $promotionPrice = $this->otResponse->findMinPromotionPrice($itemInfo, $configuredItem);
        }

        /** @var ProductVariantInterface $variant */
        $variant = $product->getVariants()[0];
        $variant->setCode($configuredItem['Id']);
        $this->modifyTranslation($variant, $params, $attributeInfo['value']);
        $variant->setImageUrl($attributeInfo['imageUrl']);

        $cp = $this->createChannelPricing($configuredItem, $promotionPrice);
        $cp->setProductVariant($variant);
        $product->setPriceOt($cp->getPrice());
        $product->setOriginalPriceOt($cp->getOriginalPrice());
        $variant->addChannelPricing($cp);
        $channel = $this->channelContext->getChannel();
        $product->addChannel($channel);

        $this->entityManager->persist($variant);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return ['product' => $product, 'variant' => $variant];
    }

    public function updateProductFromOt($itemInfo, $params, ModelProductInterface $product)
    {
        $this->modifyTranslation($product, $params, $itemInfo['Title'], $itemInfo['Id']);
        $product->setExternalVendorId($itemInfo['VendorId']);
        $product->setWebUrl($itemInfo['ExternalItemUrl']);
        $product->setImageUrl($itemInfo['MainPictureUrl']);
        $product->setProviderType($itemInfo['ProviderType']);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    private function createChannelPricing($configuredItem, $promotionPrice): ChannelPricingInterface
    {
        $oneItemPriceWithoutDelivery = $configuredItem['Price']['ConvertedPriceList']['Internal']['Price'];

        $originalPrice = (int) ($oneItemPriceWithoutDelivery * 100);
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

    private function modifyTranslation(ModelProductInterface|ProductVariantInterface $object, $params, $name, $slug = null)
    {
        $translation = $object->getTranslation($params['localeCode']);
        if ($object instanceof ModelProductInterface) {
            if ($translation->getLocale() === $params['localeCode']) {
                $translation->setName($name);
                $translation->setSlug($slug);
                $this->entityManager->persist($translation);
            } else {
                $newTranslation = new ProductTranslation();
                $newTranslation->setName($name);
                $newTranslation->setSlug($slug);
                $newTranslation->setLocale($params['localeCode']);
                $this->entityManager->persist($newTranslation);
                $object->addTranslation($newTranslation);
                $this->entityManager->persist($object);
            }
        } elseif ($object instanceof ProductVariantInterface) {
            if ($translation->getLocale() === $params['localeCode']) {
                $translation->setName($name);
                $this->entityManager->persist($translation);
            } else {
                $newTranslation = new ProductVariantTranslation();
                $newTranslation->setName($name);
                $newTranslation->setLocale($params['localeCode']);
                $this->entityManager->persist($newTranslation);
                $object->addTranslation($newTranslation);
                $this->entityManager->persist($object);
            }
        }
    }
}