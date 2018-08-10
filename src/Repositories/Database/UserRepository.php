<?php

namespace Repositories\Database;

use Contracts\Repositories\Database\UserRepositoryInterface;
use Models\UserProfileLog;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Instantiate repository with dependencies.
     * 
     * @return void
     */
    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;
    }

    /**
     * Update tier level for user.
     * 
     * @param int $id  the user id.
     * @param int $level  the tier level.
     * @return boolean
     */
    public function updateTier($id, $level)
    {
        $stmt = $this->mysqli->prepare("
            UPDATE 
                users
            SET
                tier_level = ?
            WHERE
                id = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            'ii',
            $level,
            $id
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
    
    /**
     * Update user profile.
     * 
     * @param int $id  the user id.
     * @param array $data  the user data to update.
     * @return boolean
     */
    public function updateProfile($id, $data)
    {
        $userProfileLog = new UserProfileLog($this->mysqli);
        $userProfileLog->create([
            'user_id' => $id,
            'payload' => json_encode($data),
        ]);

        $stmt = $this->mysqli->prepare("
            UPDATE 
                users
            SET
                first_name = ?,
                last_name = ?,
                address_1 = ?,
                address_2 = ?,
                zip_code = ?,
                city = ?,
                state = ?,
                country_code = ?,
                email = ?,
                phone_number = ?
            WHERE
                id = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            'ssssssssssi',
            $data['first_name'],
            $data['last_name'],
            $data['address_1'],
            $data['address_2'],
            $data['zip_code'],
            $data['city'],
            $data['state'],
            $data['country'],
            $data['email'],
            $data['phone_number'],
            $id
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Reset email confirmation status.
     * 
     * @param int $id  the user id.
     * @return boolean
     */
    public function resetEmailConfirmation($id)
    {
        $stmt = $this->mysqli->prepare("
            UPDATE 
                users
            SET
                email_confirmed_at = NULL
            WHERE
                id = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            'i',
            $id
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Reset phone number confirmation status.
     * 
     * @param int $id  the user id.
     * @return boolean
     */
    public function resetPhoneNumberConfirmation($id)
    {
        $stmt = $this->mysqli->prepare("
            UPDATE 
                users
            SET
                phone_number_confirmed_at = NULL
            WHERE
                id = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            'i',
            $id
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}