<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Model;

/**
 * Class OtParameters.
 */
class OtParameters
{
    private ?int $framePosition = null;

    private ?int $frameSize = null;

    private ?string $activityId = null;

    private ?bool $getResult = null;

    private ?string $ids = null;

    private ?OtBlockList $blockList = null;

    private ?string $itemId = null;

    private ?int $quantity = null;
    /** @var string */
    private ?string $promotionId = null;
    /** @var string */
    private ?string $configurationId = null;
    /** @var string */
    private ?string $categoryId = null;
    /** @var string */
    private ?string $brandId = null;
    /** @var string */
    private ?string $vendorId = null;
    /** @var string */
    private ?string $parentCategoryId = null;
    /** @var string */
    private ?string $categoryItemFilter = null;

    private ?string $categoryIds = null;

    private ?string $userLogin = null;
    private ?string $userPassword = null;
    private ?string $sessionId = null;

    /** @return int|null */
    public function getFramePosition(): ?int
    {
        return $this->framePosition;
    }

    /** @param int $framePosition */
    public function setFramePosition(int $framePosition): void
    {
        $this->framePosition = $framePosition;
    }

    /** @return int|null */
    public function getFrameSize(): ?int
    {
        return $this->frameSize;
    }

    /** @param int $frameSize */
    public function setFrameSize(int $frameSize): void
    {
        $this->frameSize = $frameSize;
    }

    /** @return string|null */
    public function getActivityId(): ?string
    {
        return $this->activityId;
    }

    /** @param string $activityId */
    public function setActivityId(string $activityId): void
    {
        $this->activityId = $activityId;
    }

    /** @return bool|null */
    public function getGetResult(): ?bool
    {
        return $this->getResult;
    }

    /*** @param bool $getResult */
    public function setGetResult(bool $getResult): void
    {
        $this->getResult = $getResult;
    }

    /** @return string|null */
    public function getIds(): ?string
    {
        return $this->ids;
    }

    /** @param array $ids */
    public function setIds(array $ids): void
    {
        $this->ids = implode(';', $ids);
    }

    /** @return OtBlockList|null */
    public function getBlockList(): ?OtBlockList
    {
        return $this->blockList;
    }

    /** @param OtBlockList $blockList */
    public function setBlockList(OtBlockList $blockList): void
    {
        $this->blockList = $blockList;
    }

    /** @return string|null */
    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    /** @param string $itemId */
    public function setItemId(string $itemId): void
    {
        $this->itemId = $itemId;
    }

    /** @return int|null */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /** @param int $quantity */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /** @return string|null */
    public function getPromotionId(): ?string
    {
        return $this->promotionId;
    }

    /** @param string $promotionId */
    public function setPromotionId(string $promotionId): void
    {
        $this->promotionId = $promotionId;
    }

    /** @return string|null */
    public function getConfigurationId(): ?string
    {
        return $this->configurationId;
    }

    /** @param string $configurationId */
    public function setConfigurationId(string $configurationId): void
    {
        $this->configurationId = $configurationId;
    }

    /** @return string|null */
    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    /** @param string $categoryId */
    public function setCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /** @return string|null */
    public function getBrandId(): ?string
    {
        return $this->brandId;
    }

    /** @param string $brandId */
    public function setBrandId(string $brandId): void
    {
        $this->brandId = $brandId;
    }

    /** @return string|null */
    public function getVendorId(): ?string
    {
        return $this->vendorId;
    }

    /** @param string $vendorId */
    public function setVendorId(string $vendorId): void
    {
        $this->vendorId = $vendorId;
    }

    /** @return string|null */
    public function getParentCategoryId(): ?string
    {
        return $this->parentCategoryId;
    }

    /** @param string $parentCategoryId */
    public function setParentCategoryId(string $parentCategoryId): void
    {
        $this->parentCategoryId = $parentCategoryId;
    }

    /** @param string $categoryItemFilter */
    public function setCategoryItemFilter(string $categoryItemFilter): void
    {
        $this->categoryItemFilter = $categoryItemFilter;
    }

    /** @return string|null */
    public function getCategoryIds(): ?string
    {
        return $this->categoryIds;
    }

    /** @param array $categoryIds */
    public function setCategoryIds(array $categoryIds): void
    {
        $this->categoryIds = implode(',', $categoryIds);
    }

    public function getUserLogin(): ?string
    {
        return $this->userLogin;
    }

    public function setUserLogin(string $userLogin): void
    {
        $this->userLogin = $userLogin;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): void
    {
        $this->userPassword = $userPassword;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    /** @return array */
    public function getData(): array
    {
        $props = get_object_vars($this);
        $parameters = [];
        foreach ($props as $propName => $propValue) {
            if (is_null($propValue)) {
                continue;
            }
            if (is_object($propValue) && OtBlockList::class === get_class($propValue)) {
                $parameters[$propName] = $propValue->getData();
            } elseif (!is_array($propValue) || count($propValue)) {
                $parameters[$propName] = $propValue;
            }
        }

        return $parameters;
    }
}
