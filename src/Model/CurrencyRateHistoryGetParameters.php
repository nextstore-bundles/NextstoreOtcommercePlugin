<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Model;

/**
 * Class CurrencyRateHistoryGetParameters.
 */
class CurrencyRateHistoryGetParameters
{
    private ?string $firstCurrencyCode = null;
    private ?string $secondCurrencyCode = null;
    private ?string $dateFrom = null;
    private ?string $dateTo = null;

    public function getFirstCurrencyCode(): ?string
    {
        return $this->firstCurrencyCode;
    }

    public function setFirstCurrencyCode(string $firstCurrencyCode): void
    {
        $this->firstCurrencyCode = $firstCurrencyCode;
    }

    public function getSecondCurrencyCode(): ?string
    {
        return $this->secondCurrencyCode;
    }

    public function setSecondCurrencyCode(string $secondCurrencyCode): void
    {
        $this->secondCurrencyCode = $secondCurrencyCode;
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    public function setDateFrom(string $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }

    public function setDateTo(string $dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    public function createXmlParameters(): string
    {
        $xmlData = [];
        $xmlData[] = '<CurrencyRateHistoryGetParameters>';
        if ($this->getFirstCurrencyCode()) {
            $xmlData[] = '<FirstCurrencyCode>'.$this->getFirstCurrencyCode().'</FirstCurrencyCode>';
        }
        if ($this->getSecondCurrencyCode()) {
            $xmlData[] = '<SecondCurrencyCode>'.$this->getSecondCurrencyCode().'</SecondCurrencyCode>';
        }
        if ($this->getDateFrom()) {
            $xmlData[] = '<DateFrom>'.$this->getDateFrom().'</DateFrom>';
        }
        if ($this->getDateTo()) {
            $xmlData[] = '<DateTo>'.$this->getDateTo().'</DateTo>';
        }
        $xmlData[] = '</CurrencyRateHistoryGetParameters>';

        return implode('', $xmlData);
    }
}