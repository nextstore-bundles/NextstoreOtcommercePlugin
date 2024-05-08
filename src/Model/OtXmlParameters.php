<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Model;

/**
 * Class OtXmlParameters.
 */
class OtXmlParameters
{
    /*** @var string|null */
    private ?string $provider = null;
    /*** @var string|null */
    private ?string $categoryId = null;
    /*** @var float|null */
    private ?float $minPrice = null;
    /*** @var float|null */
    private ?float $maxPrice = null;
    /*** @var int|null */
    private ?int $minVolume = null;
    /*** @var string|null */
    private ?string $order = null;
    /*** @var bool */
    private bool $isComplete = true;
    /*** @var string|null */
    private ?string $vendorName = null;
    /*** @var string|null */
    private ?string $vendorId = null;
    /*** @var string|null */
    private ?string $itemTitle = null;
    /*** @var string|null */
    private ?string $brandId = null;
    /*** @var string|null */
    private ?string $imageUrl = null;
    /*** @var string|null */
    private ?string $imageFileId = null;

    private ?string $languageOfQuery = null;

    private string $stuffStatus = 'New';

    private array $configurators = [];
    private array $features = [];

    private string $fieldName = 'xmlParameters';
    private string $type = 'SearchItemsParameters';
    private ?string $xmlData = null;

    private bool $discount = false;
    private ?string $promotionCode = null;

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

    public function getXmlData(): ?string
    {
        return $this->xmlData;
    }

    public function setXmlData(string $xmlData): void
    {
        $this->xmlData = $xmlData;
    }

    /*** @return string|null */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /*** @param string $provider */
    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    /*** @return string|null */
    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    /*** @param string $categoryId */
    public function setCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /*** @return float|null */
    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    /*** @param float $minPrice */
    public function setMinPrice(float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /*** @return float|null */
    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    /*** @param float $maxPrice */
    public function setMaxPrice(float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /*** @return int|null */
    public function getMinVolume(): ?int
    {
        return $this->minVolume;
    }

    /*** @param int $minVolume */
    public function setMinVolume(int $minVolume): void
    {
        $this->minVolume = $minVolume;
    }

    /*** @return string|null */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /*** @param string $order */
    public function setOrder(string $order): void
    {
        $this->order = $order;
    }

    /*** @return bool */
    public function getIsComplete(): bool
    {
        return $this->isComplete;
    }

    /*** @param bool $isComplete */
    public function setIsComplete(bool $isComplete): void
    {
        $this->isComplete = $isComplete;
    }

    /*** @return string|null */
    public function getVendorName(): ?string
    {
        return $this->vendorName;
    }

    /*** @param string $vendorName */
    public function setVendorName(string $vendorName): void
    {
        $this->vendorName = $vendorName;
    }

    /*** @return string|null */
    public function getVendorId(): ?string
    {
        return $this->vendorId;
    }

    /*** @param string $vendorId */
    public function setVendorId(string $vendorId): void
    {
        $this->vendorId = $vendorId;
    }

    /*** @return string|null */
    public function getItemTitle(): ?string
    {
        return $this->itemTitle;
    }

    /*** @param string $itemTitle */
    public function setItemTitle(string $itemTitle): void
    {
        $this->itemTitle = $itemTitle;
    }

    /*** @return string|null */
    public function getBrandId(): ?string
    {
        return $this->brandId;
    }

    /*** @param string $brandId */
    public function setBrandId(string $brandId): void
    {
        $this->brandId = $brandId;
    }

    /*** @return string|null */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /*** @param string $imageUrl */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /*** @return string|null */
    public function getImageFileId(): ?string
    {
        return $this->imageFileId;
    }

    /*** @param string $imageFileId */
    public function setImageFileId(string $imageFileId): void
    {
        $this->imageFileId = $imageFileId;
    }

    /*** @return string|null */
    public function getStuffStatus(): ?string
    {
        return $this->stuffStatus;
    }

    /*** @param string $stuffStatus */
    public function setStuffStatus(string $stuffStatus): void
    {
        $this->stuffStatus = $stuffStatus;
    }

    /*** @return array */
    public function getConfigurators(): array
    {
        return $this->configurators;
    }

    /*** @param array */
    public function setConfigurators(array $property)
    {
        $this->configurators = $property;
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    /*** @param array */
    public function setFeatures(array $features)
    {
        $this->features = $features;
    }

    public function getLanguageOfQuery(): ?string
    {
        return $this->languageOfQuery;
    }

    public function setLanguageOfQuery($languageOfQuery): void
    {
        $this->languageOfQuery = $languageOfQuery;
    }

    public function getDiscount(): bool
    {
        return $this->discount;
    }

    public function setDiscount(bool $discount): void
    {
        $this->discount = $discount;
    }

    public function getPromotionCode(): ?string
    {
        return $this->promotionCode;
    }

    public function setPromotionCode(string $promotionCode): void
    {
        $this->promotionCode = $promotionCode;
    }

    /*** @return string */
    public function createXmlParameters(): string
    {
        $result = '';
        if ('xmlParameters' == $this->getFieldName() && 'SearchItemsParameters' == $this->getType()) {
            $result = $this->getXmlParameters();
        } elseif ('xmlParameters' == $this->getFieldName() && 'CurrencyRateHistoryGetParameters' == $this->getType()) {
            $result = $this->getXmlData();
        } elseif ('xmlSettings' == $this->getFieldName() && 'CurrencySettings' == $this->getType()) {
            $result = $this->getXmlData();
        } elseif ('xmlParameters' == $this->getFieldName() && 'Parameters' == $this->getType()) {
            $result = $this->getXmlData();
        }

        return $result;
    }

    public function getXmlParameters(): string
    {
        $xmlData = [];
        $xmlData[] = '<SearchItemsParameters>';
        if ($this->getProvider()) {
            $xmlData[] = '<Provider>'.$this->getProvider().'</Provider>';
        }
        if ($this->getVendorName()) {
            $xmlData[] = '<VendorName>'.$this->getVendorName().'</VendorName>';
        }
        if ($this->getVendorId()) {
            $xmlData[] = '<VendorId>'.$this->getVendorId().'</VendorId>';
        }
        if ($this->getItemTitle()) {
            $xmlData[] = '<ItemTitle>'.$this->getItemTitle().'</ItemTitle>';
        }
        if ($this->getBrandId()) {
            $xmlData[] = '<BrandId>'.$this->getBrandId().'</BrandId>';
        }
        if ($this->getCategoryId()) {
            $xmlData[] = '<CategoryId>'.$this->getCategoryId().'</CategoryId>';
        }
        if ($this->getMinPrice()) {
            $xmlData[] = '<MinPrice>'.$this->getMinPrice().'</MinPrice>';
        }
        if ($this->getMaxPrice()) {
            $xmlData[] = '<MaxPrice>'.$this->getMaxPrice().'</MaxPrice>';
        }
        if ($this->getMinVolume()) {
            $xmlData[] = '<MinVolume>'.$this->getMinVolume().'</MinVolume>';
        }
        if ($this->getOrder()) {
            $xmlData[] = '<OrderBy>'.$this->getOrder().'</OrderBy>';
        } else {
            $xmlData[] = '<OrderBy>Default</OrderBy>';
        }
        if ($this->getStuffStatus()) {
            $xmlData[] = '<StuffStatus>'.$this->getStuffStatus().'</StuffStatus>';
        } else {
            $xmlData[] = '<StuffStatus>Default</StuffStatus>';
        }
        if ($this->getImageUrl()) {
            $xmlData[] = '<ImageUrl>'.$this->getImageUrl().'</ImageUrl>';
            $xmlData[] = '<Module>Image</Module>';
        }
        if ($this->getImageFileId()) {
            $xmlData[] = '<ImageFileId>'.$this->getImageFileId().'</ImageFileId>';
            $xmlData[] = '<Module>Image</Module>';
        }
        if ($this->getLanguageOfQuery()) {
            $xmlData[] = '<LanguageOfQuery>'.$this->getLanguageOfQuery().'</LanguageOfQuery>';
        }

        if (count($this->getConfigurators()) > 0) {
            $xmlData[] = '<Configurators>';
            foreach ($this->getConfigurators() as $property) {
                foreach ($property['vids'] as $vid) {
                    $xmlData[] = '<Configurator Pid="'.$property['pid'].'" Vid="'.$vid.'" />';
                }
            }
            $xmlData[] = '</Configurators>';
        }

        $xmlData[] = '<Features>';
        if ($this->getBrandId() || $this->getVendorId()) {
            $xmlData[] = '<Feature Name="Tmall">false</Feature>';
        } else {
            $xmlData[] = '<Feature Name="Tmall">true</Feature>';
        }
        // $xmlData[] = '<Feature Name="Tmall">true</Feature>';
        // $xmlData[] = '<Feature Name="IsComplete">true</Feature>';
        if ($this->getDiscount()) {
            $xmlData[] = '<Feature Name="Discount">true</Feature>';
        }
        if ($this->getPromotionCode()) {
            $xmlData[] = '<Feature Name="'.$this->getPromotionCode().'">true</Feature>';
        }
        $xmlData[] = '</Features>';
        $xmlData[] = '<IsSellAllowed>true</IsSellAllowed>';
        // $xmlData[] = '<IsTmall>true</IsTmall>';

        // if (count($this->getFeatures()) > 0) {
        //     $xmlData[] = '<Features>';
        //     foreach ($this->getFeatures() as $key => $feature) {
        //         $xmlData[] = '<Feature Name="'.$key.'">'.$feature.'</Feature>';
        //     }
        //     $xmlData[] = '</Features>';
        // }
        $xmlData[] = '</SearchItemsParameters>';

        return implode('', $xmlData);
    }
}
