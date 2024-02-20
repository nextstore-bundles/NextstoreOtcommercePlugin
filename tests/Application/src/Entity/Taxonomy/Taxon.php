<?php

declare(strict_types=1);

namespace Tests\Nextstore\SyliusOtcommercePlugin\Application\src\Entity\Taxonomy;

use Doctrine\ORM\Mapping as ORM;
use Nextstore\SyliusDropshippingCorePlugin\Model\TaxonTrait;
use Nextstore\SyliusDropshippingCorePlugin\Model\TaxonInterface;
use Sylius\Component\Core\Model\Taxon as CoreTaxon;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_taxon")
 */
class Taxon extends CoreTaxon implements TaxonInterface
{
    use TaxonTrait;
}