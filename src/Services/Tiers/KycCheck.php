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
     * Determine if the user is KYC verified (information filled out).
     * 
     * @param array $user  the user.
     * @return boolean.
     */
    public static function isVerified($user)
    {
        return $user['tier_level'] >= 1;
    }

    /**
     * User skipped the reminder.
     * 
     * @return boolean.
     */
    public static function reminderSkipped()
    {
        if ( ! isset($_SESSION['kyc_reminder_skipped'])) {
            return false;
        }

        // User skipped reminder within the last 12 hours.
        if ($_SESSION['kyc_reminder_skipped'] < (time() - 43200)) {
            return false;
        }

        return true;
    }

}