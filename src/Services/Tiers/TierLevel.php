<?php

namespace Services\Tiers;

use Models\User;

class TierLevel
{
    /**
     * Tier level constants.
     * 
     * @var int
     */
    const TIER_LEVEL_0 = 0;
    const TIER_LEVEL_1 = 1;
    const TIER_LEVEL_2 = 2;
    const TIER_LEVEL_3 = 3;

    /**
     * Mandatory fields to be verified as tier 1.
     * 
     * @var array
     */
    const TIER_LEVEL_1_MANDATORY_FIELDS = [
        'first_name',
        'last_name',
        'address_1',
        'zip_code',
        'city',
        'country',
        'email',
    ];

    /**
     * Determine the tier level for the $user.
     * 
     * @param array $user  the user.
     * @return int  the tier level.
     */
    public static function determine($user)
    {
        $checkFields = array_intersect_key($user, array_flip(self::TIER_LEVEL_1_MANDATORY_FIELDS));

        $emptyFields = array_filter($checkFields, function($item) {
            return empty(trim($item));
        });

        if ($emptyFields) {
            return self::TIER_LEVEL_0;
        }

        // @TODO add phone number confirmation.
        if (self::emailIsConfirmed($user)) {
            return self::TIER_LEVEL_2;
        } else {
            return self::TIER_LEVEL_1;
        }

        // @TODO tier level 3
    }

    /**
     * Is user email confirmed?
     * 
     * @param array $user  the user.
     * @return boolean
     */
    protected static function emailIsConfirmed($user)
    {
        return ! is_null($user['email_confirmed_at']) && $user['email_confirmed_at'] > '0000-00-00 00:00:00';
    }

    /**
     * Is user phone number confirmed?
     * 
     * @param array $user  the user.
     * @return boolean
     */
    protected static function phoneNumberIsConfirmed($user)
    {
        return ! is_null($user['phone_number_confirmed_at']) && $user['phone_number_confirmed_at'] > '0000-00-00 00:00:00';
    }
}