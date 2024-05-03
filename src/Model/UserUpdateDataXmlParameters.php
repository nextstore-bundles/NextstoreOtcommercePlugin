<?php

declare(strict_types=1);

namespace Nextstore\SyliusOtcommercePlugin\Model;

/**
 * Class UserUpdateDataXmlParameters.
 */
class UserUpdateDataXmlParameters
{
    public const MALE = 'Male';
    public const FEMALE = 'Female';

    /*** @var int|null */
    private ?int $id = null;
    /*** @var string|null */
    private ?string $login = null;
    /*** @var string|null */
    private ?string $email = null;
    /*** @var string|null */
    private ?string $password = null;
    /*** @var string|null */
    private ?string $additionalInfo = null;
    /*** @var string|null */
    private ?string $firstName = null;
    /*** @var string|null */
    private ?string $lastName = null;
    /*** @var string|null */
    private ?string $middleName = null;
    /*** @var string|null */
    private ?string $sex = self::MALE;
    /*** @var string|null */
    private ?string $countryCode = null;
    /*** @var string|null */
    private ?string $country = null;
    /*** @var string|null */
    private ?string $city = null;
    /*** @var string|null */
    private ?string $address = null;
    /*** @var string|null */
    private ?string $postalCode = null;
    /*** @var string|null */
    private ?string $region = null;
    /*** @var string|null */
    private ?string $recipientFirstName = null;
    /*** @var string|null */
    private ?string $recipientLastName = null;
    /*** @var string|null */
    private ?string $recipientMiddleName = null;
    /*** @var string|null */
    private ?string $phone = null;
    /*** @var string|null */
    private ?string $skype = null;

    private string $fieldName = 'userParameters';
    private string $type = 'UserUpdateData';

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

    /** @return int|null */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @param int $id */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /** @return string|null */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /** @param string $login */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /** @return string|null */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /** @param string $email */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /** @return string|null */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /** @param string $password */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /** @return string|null */
    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }

    /** @param string $additionalInfo */
    public function setAdditionalInfo(string $additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }

    /** @return string|null */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /** @param string $firstName */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /** @return string|null */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /** @param string $lastName */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /** @return string|null */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /** @param string $middleName */
    public function setMiddleName(string $middleName): void
    {
        $this->middleName = $middleName;
    }

    /** @return string|null */
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /** @param string $sex */
    public function setSex(string $sex): void
    {
        $this->sex = $sex;
    }

    /** @return string|null */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /** @param string $countryCode */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /** @return string|null */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /** @param string $country */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /** @return string|null */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /** @param string $city */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /** @return string|null */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /** @param string $address */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /** @return string|null */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /** @param string $postalCode */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /** @return string|null */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /** @param string $region */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /** @return string|null */
    public function getRecipientFirstName(): ?string
    {
        return $this->recipientFirstName;
    }

    /** @param string $recipientFirstName */
    public function setRecipientFirstName(string $recipientFirstName): void
    {
        $this->recipientFirstName = $recipientFirstName;
    }

    /** @return string|null */
    public function getRecipientLastName(): ?string
    {
        return $this->recipientLastName;
    }

    /** @param string $recipientLastName */
    public function setRecipientLastName(string $recipientLastName): void
    {
        $this->recipientLastName = $recipientLastName;
    }

    /** @return string|null */
    public function getRecipientMiddleName(): ?string
    {
        return $this->recipientMiddleName;
    }

    /** @param string $recipientMiddleName */
    public function setRecipientMiddleName(string $recipientMiddleName): void
    {
        $this->recipientMiddleName = $recipientMiddleName;
    }

    /** @return string|null */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /** @param string $phone */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /** @return string|null */
    public function getSkype(): ?string
    {
        return $this->skype;
    }

    /** @param string $skype */
    public function setSkype(string $skype): void
    {
        $this->skype = $skype;
    }

    /*** @return string */
    public function createXmlParameters(): string
    {
        $result = $this->getXmlParameters();

        return $result;
    }

    public function getXmlParameters(): string
    {
        $xmlData = [];
        $xmlData[] = '<UserUpdateData>';
        if ($this->getId()) {
            $xmlData[] = '<Id>'.$this->getId().'</Id>';
        }
        if ($this->getLogin()) {
            $xmlData[] = '<Login>'.$this->getLogin().'</Login>';
        }
        if ($this->getEmail()) {
            $xmlData[] = '<Email>'.$this->getEmail().'</Email>';
        }
        if ($this->getPassword()) {
            $xmlData[] = '<Password>'.$this->getPassword().'</Password>';
        }
        if ($this->getAdditionalInfo()) {
            $xmlData[] = '<AdditionalInfo>'.$this->getAdditionalInfo().'</AdditionalInfo>';
        }
        if ($this->getFirstName()) {
            $xmlData[] = '<FirstName>'.$this->getFirstName().'</FirstName>';
        }
        if ($this->getLastName()) {
            $xmlData[] = '<LastName>'.$this->getLastName().'</LastName>';
        }
        if ($this->getMiddleName()) {
            $xmlData[] = '<MiddleName>'.$this->getMiddleName().'</MiddleName>';
        }
        if ($this->getSex()) {
            $xmlData[] = '<Sex>'.$this->getSex().'</Sex>';
        }
        if ($this->getCountryCode()) {
            $xmlData[] = '<CountryCode>'.$this->getCountryCode().'</CountryCode>';
        }
        if ($this->getCountry()) {
            $xmlData[] = '<Country>'.$this->getCountry().'</Country>';
        }
        if ($this->getCity()) {
            $xmlData[] = '<City>'.$this->getCity().'</City>';
        }
        if ($this->getAddress()) {
            $xmlData[] = '<Address>'.$this->getAddress().'</Address>';
        }
        if ($this->getPostalCode()) {
            $xmlData[] = '<PostalCode>'.$this->getPostalCode().'</PostalCode>';
        }
        if ($this->getRegion()) {
            $xmlData[] = '<Region>'.$this->getRegion().'</Region>';
        }
        if ($this->getRecipientFirstName()) {
            $xmlData[] = '<RecipientFirstName>'.$this->getRecipientFirstName().'</RecipientFirstName>';
        }
        if ($this->getRecipientLastName()) {
            $xmlData[] = '<RecipientLastName>'.$this->getRecipientLastName().'</RecipientLastName>';
        }
        if ($this->getRecipientMiddleName()) {
            $xmlData[] = '<RecipientMiddleName>'.$this->getRecipientMiddleName().'</RecipientMiddleName>';
        }
        if ($this->getPhone()) {
            $xmlData[] = '<Phone>'.$this->getPhone().'</Phone>';
        }
        if ($this->getSkype()) {
            $xmlData[] = '<Skype>'.$this->getSkype().'</Skype>';
        }
        $xmlData[] = '</UserUpdateData>';

        return implode('', $xmlData);
    }
}