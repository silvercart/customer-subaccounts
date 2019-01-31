<?php

namespace SilverCart\CustomerSubAccounts\Extensions;

use DataExtension;
use FieldList;
use SS_List;
use Member;
use SilvercartAddress as Address;
use SilvercartTools as Tools;

/**
 * Extension for SilverStripe Member.
 * Adds the sub account feature to Member.
 * 
 * @package SilverCart
 * @subpackage CustomerSubAccounts\Extensions
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 30.01.2019
 * @copyright 2019 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class MemberExtension extends DataExtension
{
    /**
     * Set to true by YAML configuration to display the parent account's data in
     * a sub account's customer area and checkout.
     *
     * @var bool
     */
    private static $display_parent_account_data = false;
    /**
     * Has one relations.
     *
     * @var array
     */
    private static $has_one = [
        'ParentAccount' => Member::class,
    ];
    /**
     * Has many relations.
     *
     * @var array
     */
    private static $has_many = [
        'SubAccounts' => Member::class . ".ParentAccount",
    ];
    
    /**
     * Updates the CMS fields.
     * 
     * @param FieldList $fields Field to update
     * 
     * @return void
     */
    public function updateCMSFields(FieldList $fields) : void
    {
        $fields->insertBefore('FirstName', $fields->dataFieldByName('ParentAccountID'));
        $fields->dataFieldByName('ParentAccountID')->setDescription($this->owner->fieldLabel('ParentAccountDesc'));
    }
    
    /**
     * Updates the field labels.
     * 
     * @param array &$labels Labels to update
     * 
     * @return void
     */
    public function updateFieldLabels(&$labels) : void
    {
        $labels = array_merge(
                $labels,
                Tools::field_labels_for(self::class)
        );
    }
    
    /**
     * Updates the related addresses.
     * 
     * @param SS_List &$addresses Addersses to update
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function updateSilvercartAddresses(SS_List &$addresses) : void
    {
        if ($this->displayParentAccountData()
         && !$addresses->exists()) {
            $addresses = $this->owner->ParentAccount()->SilvercartAddresses();
        }
    }
    
    /**
     * Updates the invoice address.
     * 
     * @param Address &$address Address to update
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function updateSilvercartInvoiceAddress(Address &$address) : void
    {
        if ($this->displayParentAccountData()
         && !$address->exists()) {
            $address = $this->owner->ParentAccount()->SilvercartInvoiceAddress();
        }
    }
    
    /**
     * Updates the shipping address.
     * 
     * @param Address &$address Address to update
     * 
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function updateSilvercartShippingAddress(Address &$address) : void
    {
        if ($this->displayParentAccountData()
         && !$address->exists()) {
            $address = $this->owner->ParentAccount()->SilvercartShippingAddress();
        }
    }
    
    /**
     * Returns whether to display the parent account data.
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function displayParentAccountData() : bool
    {
        return !(Tools::isIsolatedEnvironment()
              || Tools::isBackendEnvironment())
            && $this->isSubAccount()
            && $this->owner->config()->display_parent_account_data;
    }
    
    /**
     * Returns whether the extended Member is a parent account.
     * Alias for $this->hasSubAccounts().
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function isParentAccount() : bool
    {
        return $this->hasSubAccounts();
    }
    
    /**
     * Returns whether the extended Member is a sub account.
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function isSubAccount() : bool
    {
        return (bool) $this->owner->ParentAccount()->exists();
    }
    
    /**
     * Returns whether the extended Member has sub accounts.
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function hasSubAccounts() : bool
    {
        return (bool) $this->owner->SubAccounts()->exists();
    }
}