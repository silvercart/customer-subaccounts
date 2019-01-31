<?php

namespace SilverCart\CustomerSubAccounts\Extensions;

use DataExtension;
use Member;

/**
 * Extension for SilverCart Product.
 * Adds the sub account feature to Product.
 * 
 * @package SilverCart
 * @subpackage CustomerSubAccounts\Extensions
 * @author Sebastian Diel <sdiel@pixeltricks.de>
 * @since 30.01.2019
 * @copyright 2019 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class ProductExtension extends DataExtension
{
    /**
     * Updates the original canViewPrice.
     * Returns false if the given member is a sub account.
     * 
     * @param Member $member Member to check permission for.
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function canViewPrice(Member $member = null) : bool
    {
        $can = true;
        if ($member instanceof Member
         && $member->isSubAccount()
        ) {
            $can = false;
        }
        return $can;
    }
    
    /**
     * Updates the original canBuy.
     * Returns false if the given member is a sub account.
     * 
     * @param Member $member Member to check permission for.
     * 
     * @return bool
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>
     * @since 30.01.2019
     */
    public function canBuy(Member $member = null) : bool
    {
        $can = true;
        if ($member instanceof Member
         && $member->isSubAccount()
        ) {
            $can = false;
        }
        return $can;
    }
}