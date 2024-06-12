<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Service;

class OtResponse
{
    public function findConfiguredItem($configuredItems, $variantCode)
    {
        foreach ($configuredItems as $configuredItem) {
            if ($configuredItem['Id'] == $variantCode) {
                return $configuredItem;
            }
        }
    }

    public function findMinPromotionPrice($itemInfo, $configuredItem)
    {
        $oneItemPriceWithoutDelivery = $configuredItem['Price']['ConvertedPriceList']['Internal']['Price'];
        $minPromotionPrice = $oneItemPriceWithoutDelivery;
        if (isset($itemInfo['Promotions'])) {
            foreach ($itemInfo['Promotions'] as $promotion) {
                foreach ($promotion['ConfiguredItems'] as $promotionItem) {
                    if ($promotionItem['Id'] === $configuredItem['Id']) {
                        $promotionOneItemPriceWithoutDelivery = $promotionItem['Price']['ConvertedPriceList']['Internal']['Price'];
                        $promotionPrice = $promotionOneItemPriceWithoutDelivery;
                        if ($minPromotionPrice > $promotionPrice) {
                            $minPromotionPrice = $promotionPrice;
                        }
                    }
                }
            }
        }

        return (int) ($minPromotionPrice * 100);
    }

    public function findAttributeInfo($attributes, $configuredItem)
    {
        $attributeInfo = [
            'value' => '', 'imageUrl' => ''
        ];
        foreach ($attributes as $attribute) {
            foreach ($configuredItem['Configurators'] as $configurator) {
                if ($configurator['Pid'] === $attribute['Pid'] && $configurator['Vid'] === $attribute['Vid']) {
                    if ($attributeInfo['value'] === '') {
                        $attributeInfo['value'] .= $attribute['PropertyName'].':'.$attribute['Value'].';';
                    } else {
                        $attributeInfo['value'] .= ' '.$attribute['PropertyName'].':'.$attribute['Value'].';';
                    }
                    $attributeInfo['imageUrl'] = $attribute['ImageUrl'] ?? $attributeInfo['imageUrl'];
                }
            }
        }

        return $attributeInfo;
    }
}