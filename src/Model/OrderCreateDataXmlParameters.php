<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Model;

/**
 * Class OrderCreateDataXmlParameters
 */
class OrderCreateDataXmlParameters
{
    private ?float $weight = null;
    private array $elements = [];
    private ?string $deliveryModeId = null;
    private ?string $comment = null;
    private ?int $userProfileId = null;

    private string $fieldName = 'xmlCreateData';
    private string $type = 'OrderCreateData';

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

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
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

    public function getElements(): array
    {
        return $this->elements;
    }

    public function setElements(array $elements)
    {
        $this->elements = $elements;
    }

    public function createXmlParameters(): string
    {
        $result = $this->getXmlParameters();

        return $result;
    }

    public function getXmlParameters(): string
    {
        $xmlData = [];
        $xmlData[] = '<OrderCreateData>';
        if ($this->getWeight()) {
            $xmlData[] = '<Weight>'.$this->getWeight().'</Weight>';
        }
        if (count($this->getElements()) > 0) {
            $xmlData[] = '<Elements>';
            foreach ($this->getElements() as $elementId) {
                $xmlData[] = '<Id>'.$elementId.'</Id>';
            }
            $xmlData[] = '</Elements>';
        }
        if ($this->getDeliveryModeId()) {
            $xmlData[] = '<DeliveryModeId>'.$this->getDeliveryModeId().'</DeliveryModeId>';
        }
        if ($this->getComment()) {
            $xmlData[] = '<Comment>'.$this->getComment().'</Comment>';
        }
        if ($this->getUserProfileId()) {
            $xmlData[] = '<UserProfileId>'.$this->getUserProfileId().'</UserProfileId>';
        }
        $xmlData[] = '</OrderCreateData>';

        return implode('', $xmlData);
    }
}