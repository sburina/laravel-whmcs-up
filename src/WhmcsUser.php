<?php

namespace Sburina\Whmcs;

use Illuminate\Auth\GenericUser;

/**
 * Class WhmcsUser
 *
 * @property-read int $userid
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $firstname
 * @property-read string $lastname
 * @property-read string $fullname
 * @property-read string $companyname
 * @property-read string $email
 * @property-read string $address1
 * @property-read string $address2
 * @property-read string $city
 * @property-read string $fullstate
 * @property-read string $state
 * @property-read string $postcode
 * @property-read string $countrycode
 * @property-read string $country
 * @property-read string $phonenumber
 * @property-read string $password
 * @property-read string $statecode
 * @property-read string $countryname
 * @property-read string $phonecc
 * @property-read string $phonenumberformatted
 * @property-read string $telephoneNumber
 * @property-read int $billingcid
 * @property-read string $notes
 * @property-read bool $twofaenabled
 * @property-read int $currency
 * @property-read string $defaultgateway
 * @property-read string $cctype
 * @property-read string $cclastfour
 * @property-read string $gatewayid
 * @property-read int $securityqid
 * @property-read string $securityqans
 * @property-read int $groupid
 * @property-read string $status
 * @property-read string $credit
 * @property-read bool $taxexempt
 * @property-read bool $latefeeoveride
 * @property-read bool $overideduenotices
 * @property-read bool $separateinvoices
 * @property-read bool $disableautocc
 * @property-read bool $emailoptout
 * @property-read bool $marketing_emails_opt_in
 * @property-read bool $overrideautoclose
 * @property-read int $allowSingleSignOn
 * @property-read string $language
 * @property-read bool $isOptedInToMarketingEmails
 * @property-read string $lastlogin
 * @property-read string $currency_code
 *
 * @property-read string|null $remember_token
 */
class WhmcsUser extends GenericUser
{
    /**
     * Get the model attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get the non-existent "remember me" token value.
     *
     * @return null
     */
    public function getRememberToken()
    {
        return null;
    }
}
