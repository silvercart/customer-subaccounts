<?php

namespace SilverCart\CustomerSubAccounts\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Member;

/**
 * Extension for SilverCart Address.
 * Adds the sub account feature to Address.
 * 
 * @package SilverCart
 * @subpackage CustomerSubAccounts\Extensions
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 30.01.2019
 * @copyright 2019 pixeltricks GmbH
 * @license see license file in modules root directory
 * 
 * @property \SilverCart\Model\Customer\Address $owner Owner
 */
class AddressExtension extends DataExtension
{
    /**
     * Updates the original canCreate.
     * Returns false if the given member is a sub account and is set to display
     * its parent's account data.
     * 
     * @param Member $member Member to check permission for.
     * 
     * @return bool
     */
    public function canCreate($member = null) : bool
    {
        $can = true;
        if ($member instanceof Member
         && $member->displayParentAccountData()
        ) {
            $can = false;
        }
        return $can;
    }
}