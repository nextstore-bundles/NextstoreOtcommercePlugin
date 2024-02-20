<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Form\Extension;

use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class TaxonTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('externalId')
            ->add('providerType')
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [TaxonType::class];
    }
}
