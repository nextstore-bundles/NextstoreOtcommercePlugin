<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Model;

/**
 * Class OrderAddDataXmlParameters.
 */
class OrderAddDataXmlParameters
{
    private ?string $deliveryModeId = null;
    private ?string $comment = null;
    private ?int $userProfileId = null;
    private array $items = [];

    private string $fieldName = 'xmlAddData';
    private string $type = 'OrderAddData';

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getDeliveryModeId(): ?string
    {
        return $this->deliveryModeId;
    }

    public function setDeliveryModeId(string $deliveryModeId): void
    {
        $this->deliveryModeId = $deliveryModeId;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getUserProfileId(): ?int
    {
        return $this->userProfileId;
    }

    public function setUserProfileId(int $userProfileId): void
    {
        $this->userProfileId = $userProfileId;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function createXmlParameters(): string
    {
        $result = $this->getXmlParameters();

        return $result;
    }

    public function getXmlParameters(): string
    {
        $xmlData = [];
        $xmlData[] = '<OrderAddData>';
        if ($this->getDeliveryModeId()) {
            $xmlData[] = '<DeliveryModeId>'.$this->getDeliveryModeId().'</DeliveryModeId>';
        }
        if ($this->getComment()) {
            $xmlData[] = '<Comment>'.$this->getComment().'</Comment>';
        }
        if ($this->getUserProfileId()) {
            $xmlData[] = '<UserProfileId>'.$this->getUserProfileId().'</UserProfileId>';
        }
        if (count($this->getItems()) > 0) {
            $xmlData[] = '<Items>';
            foreach ($this->getItems() as $item) {
                $xmlData[] = '<Item>';
                foreach ($item as $key=>$value) {
                    if ($key === 'id') {
                        $xmlData[] = '<Id>'.$value.'</Id>';
                    }
                    if ($key === 'configurationId') {
                        $xmlData[] = '<ConfigurationId>'.$value.'</ConfigurationId>';
                    }
                    if ($key === 'quantity') {
                        $xmlData[] = '<Quantity>'.$value.'</Quantity>';
                    }
                    if ($key === 'weight') {
                        $xmlData[] = '<Weight>'.$value.'</Weight>';
                    }
                    if ($key === 'comment') {
                        $xmlData[] = '<Comment>'.$value.'</Comment>';
                    }
                    if ($key === 'promotionId') {
                        $xmlData[] = '<PromotionId>'.$value.'</PromotionId>';
                    }
                    if ($key === 'priceType') {
                        $xmlData[] = '<PriceType>'.$value.'</PriceType>';
                    }
                }
                $xmlData[] = '</Item>';
            }
            $xmlData[] = '</Items>';
        }
        $xmlData[] = '</OrderAddData>';

        return implode('', $xmlData);
    }
}