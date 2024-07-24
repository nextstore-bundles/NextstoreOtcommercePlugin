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
    private array $orderIds = [];

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

    public function getOrderIds(): array
    {
        return $this->orderIds;
    }

    public function setOrderIds(array $orderIds)
    {
        $this->orderIds = $orderIds;
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
        if (count($this->getOrderIds()) > 0) {
            $xmlData[] = '<OrderIds>';
            foreach ($this->getOrderIds() as $orderId) {
                $xmlData[] = '<Id>'.$orderId.'</Id>';
            }
            $xmlData[] = '</OrderIds>';
        }
        $xmlData[] = '</Parameters>';

        return implode('', $xmlData);
    }
}