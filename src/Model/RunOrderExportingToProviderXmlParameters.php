<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Model;

/**
 * Class RunOrderExportingToProviderXmlParameters.
 */
class RunOrderExportingToProviderXmlParameters
{
    private ?string $orderId = null;
    private array $orderLineIds = [];

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOrderLineIds(): array
    {
        return $this->orderLineIds;
    }

    public function setOrderLineIds(array $orderLineIds)
    {
        $this->orderLineIds = $orderLineIds;
    }

    public function createXmlParameters(): string
    {
        $xmlData = [];
        $xmlData[] = '<Parameters>';
        if ($this->getOrderId()) {
            $xmlData[] = '<OrderId>'.$this->getOrderId().'</OrderId>';
        }
        if (count($this->getOrderLineIds()) > 0) {
            $xmlData[] = '<OrderLineIds>';
            foreach ($this->getOrderLineIds() as $orderLineId) {
                $xmlData[] = '<Id>'.$orderLineId.'</Id>';
            }
            $xmlData[] = '</OrderLineIds>';
        }
        $xmlData[] = '</Parameters>';

        return implode('', $xmlData);
    }
}