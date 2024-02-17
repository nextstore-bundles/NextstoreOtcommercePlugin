<?php

declare(strict_types=1);

namespace Tests\Nextstore\SyliusOtcommercePlugin\Application\src\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductVariantInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductVariantTrait;
use Sylius\Component\Core\Model\ProductVariant as CoreProductVariant;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 */
class ProductVariant extends CoreProductVariant implements ProductVariantInterface
{
    use ProductVariantTrait;
}