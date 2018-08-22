<?php

namespace Contracts\Repositories\Database;

/**
 * User repository interface.
 */
interface UserRepositoryInterface
{
    /**
     * Update tier level for user.
     * 
     * @param int $id  the user id.
     * @param int $level  the tier level.
     * @return boolean
     */
    public function updateTier($id, $level);
    
    /**
     * Update user profile.
     * 
     * @param int $id  the user id.
     * @param array $data  the user data to update.
     * @return boolean
     */
    public function updateProfile($id, $data);
    
    /**
     * Reset email confirmation status.
     * 
     * @param int $id  the user id.
     * @return boolean
     */
    public function resetEmailConfirmation($id);
    
    /**
     * Reset phone number confirmation status.
     * 
     * @param int $id  the user id.
     * @return boolean
     */
    public function resetPhoneNumberConfirmation($id);
    
    /**
     * Skip KYC reminder.
     * 
     * @param int $id  the user id.
     * @return boolean
     */
    public function skipKycReminder($id);

}