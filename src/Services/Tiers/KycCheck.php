<?php

namespace Services\Tiers;

use Models\User;

class KycCheck
{
    /**
     * Maximum number of times the KYC reminder can be skipped.
     * 
     * @var int
     */
    const MAX_REMINDER_SKIP = 2;

    /**
     * Determine if the user is blocked due to 
     * missing KYC information.
     * 
     * @param array $user  the user.
     * @return boolean.
     */
    public static function userIsBlocked($user)
    {
        return $user['tier_level'] < 1 && (int) $user['kyc_skipped'] > self::MAX_REMINDER_SKIP;
    }

    /**
     * Determine if the user should receive a reminder
     * about missing KYC information.
     * 
     * @param array $user  the user.
     * @return boolean.
     */
    public static function showReminder($user)
    {
        return $user['tier_level'] < 1;
    }

}