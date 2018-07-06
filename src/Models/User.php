<?php 

namespace Models;

use Defuse\Crypto\KeyProtectedByPassword;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Exception;

class User {

	private $mysqli;

	function __construct($mysqli)
	{
		$this->mysqli = $mysqli;
	}

    /**
     * Verify that $password matches the users hashed password from the database.
     * 
     * @param  string  $password  the password to verify.
     * @param  array   $user      the user data array.
     * @return boolean
     */
    public function verifyPasswordMatch($password, $user)
    {
        // No password set for user.
        if (is_null($user['password_old_md5']) && is_null($user['password'])) {
            return false;
        }

        // Old MD5
        if (is_null($user['password']) && ($user['password_old_md5'] == md5(addslashes(strip_tags($password))))) {
            return true;

        // BCRYPT
        } elseif (password_verify($password, $user['password'])) {
            return true;

        // No password match
        } else {
            return false;
        }
    }

    /**
     * Is the user account active? (not deleted, locked etc.)
     * 
     * @param  User  $user
     * @return boolean
     */
    public function isActive($user)
    {
        if ( ! $user) {
            return false;
        }

        if ($user['locked']) {
            return false;
        }

        if ( ! is_null($user['deleted_at'])) {
            return false;
        }

        if (empty($user['password']) && empty($user['password_old_md5'])) {
            return false;
        }

        return true;
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE username=?');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_assoc();

    }

    public function getUserById($id)
    {
        if (empty($id)) {
            throw new Exception('No user ID supplied.');
        }

        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE id=?');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_assoc();

    }

    public function getUserByEmail($email)
    {
        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE email=?');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_assoc();

    }

    public function getRoles($user)
    {
        $stmt = $this->mysqli->prepare('SELECT r.* FROM user_roles ur INNER JOIN roles r ON r.id=ur.role_id WHERE ur.user_id=?');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

		$collection = [];

		while ($role = $result->fetch_assoc()) {
            $collection[] = $role;
        }
        
        $result->free();

        return $collection;
    }

    /**
     * Get user to be activated.
     * 
     * @param  int    $userId
     * @param  string $token
     * @return array
     */
    public function getUserByActivationToken($userId, $token)
    {
        $stmt = $this->mysqli->prepare('
            SELECT
                *
            FROM
                users
            WHERE
                id=? AND 
                set_password_token IS NOT NULL AND 
                set_password_token=? AND 
                set_password_before > NOW() AND 
                password IS NULL AND 
                password_old_md5 IS NULL 
            LIMIT 1');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('is', $userId, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_assoc();
    }

	public function logIn($username, $password)
	{
		if (empty($username) || empty($password))

		{
			return false;
		} 
		else {
			$auth= isset($_POST['auth']) ? $_POST['auth'] : 0;
			$username = $this->mysqli->real_escape_string(strip_tags($username));
  		  	//$password = md5(addslashes(strip_tags($password	))); 
			$auth = $this->mysqli->real_escape_string(	strip_tags(	$auth));
    		$user = $this->getUserByUsername($username);

            if (!is_null($user['deleted_at'])) {
                return lang('WALLET_LOGIN_ACCOUNT_LOCKED');
            }

            if ($user['authused']) {
                try {
                    $oneCode = $this->get2faOneCode($user, $password);
                } catch (WrongKeyOrModifiedCiphertextException $ex) {
                    return lang('WALLET_LOGIN_INCORRECT');
                }
            }

            $passwordmatch = $this->verifyPasswordMatch($password, $user);

            if ($user && $passwordmatch && $user['locked'] == 0 && $user['authused'] == 0) {
                return $user;
        	} elseif ($user && $passwordmatch && $user['locked'] == 1) {
    			$pin = $user['supportpin'];
        		return lang('WALLET_LOGIN_ACCOUNT_LOCKED') . " $pin";
            } elseif ($user && $passwordmatch && $user['locked'] == 0 && ($user['authused'] == 1 && $oneCode == $auth))  {
			    return $user;
        	} else {
        		return lang('WALLET_LOGIN_INCORRECT');
        	}

		}

    }
    
    /**
     * Authenticate user by setting session information.
     * 
     * @param  array $data
     * @return void
     */
    public function setAuthSession($data)
    {
        $_SESSION['user_session']    = $data['username'];
        $_SESSION['user_admin']      = $data['admin'];
        $_SESSION['user_supportpin'] = $data['supportpin'];
        $_SESSION['user_id']         = $data['id'];
        $_SESSION['user_2fa']        = $data['authused'];
        $_SESSION['user_uses_old_account_identifier'] = $data['uses_old_account_identifier'];
    }

    /**
     * Get 2fa one code.
     * 
     * @param  array  $user      the user data array.
     * @param  string $password  the password in clear text to use for unlocking the secret.
     * @return int               the one code.
     */
    public function get2faOneCode($user, $password)
    {
        return $this->getCode($this->get2faSecret($user, $password));
    }

    /**
     * Get 2fa secret.
     * 
     * @param  array  $user      the user data array.
     * @param  string $password  the password in clear text to use for unlocking the secret.
     * @return int               the one code.
     */
    protected function get2faSecret($user, $password)
    {
        if ($user['authused'] && is_null($user['secret_encrypted'])) {
            return $user['secret'];
        } elseif ($user['authused'] && ! is_null($user['secret_encrypted'])) {
            return $this->decrypt2faSecret($user['secret_encrypted'], $password, $user);
        }

        return $oneCode;
    }

    /**
     * Decrypt 2fa secret from database.
     * 
     * @param  string $secretEncrypted the encryptet secret.
     * @param  string $password        the password in clear text to use for unlocking the secret.
     * @param  array  $user            the user data array.
     * @return string                  the clear text secret.
     */
    protected function decrypt2faSecret($secretEncrypted, $password, $user)
    {
        $protectedKey = KeyProtectedByPassword::loadFromAsciiSafeString($user['protected_key']);
        $userKey = $protectedKey->unlockKey($password);

        return Crypto::decrypt($user['secret_encrypted'], $userKey);
    }

	function add($username, $password, $confirmPassword)
	{

		if (empty($username) || empty($password) || empty($confirmPassword))

		{

			return lang('WALLET_REGISTER_MISSING_FIELDS');

		} elseif ($password != $confirmPassword)

		{

			return lang('WALLET_REGISTER_PASSWORD_NOT_MATCH');

		} elseif ((strlen($username) < 3) || (strlen($username) > 30))

		{

			return lang('WALLET_REGISTER_USERNAME_LENGTH');

		} elseif (strlen($password) < 3)

		{

			return lang('WALLET_REGISTER_PASSWORD_LENGTH');

		} else {

			//Let's do a database check

			$username = $this->mysqli->real_escape_string(strip_tags($username));

            $password = password_hash($password, PASSWORD_BCRYPT, [
                'cost' => 12,
            ]);

			$user = $this->mysqli->query("SELECT * FROM users WHERE username='" . $username . "'");

			if ($user->num_rows > 0)

			{

				return lang('WALLET_REGISTER_USERNAME_IN_USE');

			} else {

                $clientIp = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

				$query = $this->mysqli->query("INSERT INTO users (`date`, `ip`, `username`, `password`, `supportpin`, `uses_old_account_identifier`) VALUES (\"" . date("n/j/Y g:i a") . "\", \"". $clientIp . "\", \"" . $username ."\", \"" . $password . "\", \"". rand(10000,99999) . "\", \"0\");");				

				if ($query)
				{

					return true;
				} else {
					return lang('WALLET_SYSTEM_ERROR');

				}

			}
		}
	}


	function updatePassword($user_session, $oldPassword, $newPassword, $confirmPassword)
	{
		if ($newPassword != $confirmPassword)
		{
			return lang('WALLET_UPDATEPW_NOT_MATCH');
		} else {
			//Get old password
			$result = $this->mysqli->query("SELECT * FROM users WHERE username='" . $user_session . "'");
			if ($result->num_rows > 0)
			{
				$user = $result->fetch_assoc();
				$oldPassword = addslashes(strip_tags($oldPassword));


                // Old MD5
                if (is_null($user['password']) && $user['password_old_md5'] != md5($oldPassword))
                {

                    return lang('WALLET_UPDATEPW_INCORRECT_PW');

                // BCRYPT
                } elseif (!is_null($user['password']) && ! password_verify($oldPassword, $user['password'])) {

                    return lang('WALLET_UPDATEPW_INCORRECT_PW');

				} else {

                    $password = password_hash($newPassword, PASSWORD_BCRYPT, [
                        'cost' => 12,
                    ]);

                    if ($user['authused']) {
                        $secret = $this->get2faSecret($user, $oldPassword);
                    }

                    $stmt = $this->mysqli->prepare("UPDATE users SET password=?,password_old_md5=NULL,supportpin='" . rand(10000,99999) . "' WHERE id=?");

                    if (!$stmt) {
                        return false;
                    }

                    $stmt->bind_param('si', $password, $user['id']);
                    $result = $stmt->execute();
                    $stmt->close();

					if (!$result) {
						return lang('WALLET_UNKNOWN_ERROR');
                    }

                    // Update encrypted 2fa secret.
                    if ($user['authused']) {
                        $this->enableauth($secret, $newPassword);
                    }

                    return true;

				}

			} else {

				return lang('WALLET_UNKNOWN_ERROR');

			}

		}

	}

    /**
     * Set initial password for user without updating 2FA secret.
     * 
     * @param  int    $userId
     * @param  string $password
     * @return boolean|string true or error description.
     */
	public function setPassword($userId, $password)
	{
        $password = password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);

        $stmt = $this->mysqli->prepare("UPDATE users SET password=?,password_old_md5=NULL,supportpin='" . rand(10000,99999) . "' WHERE id=?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('si', $password, $userId);
        $result = $stmt->execute();
        $stmt->close();

        if (!$result) {
            return $stmt->error;
        }

        return true;
	}

    /**
     * Clears the token allowing the user to set his password.
     * 
     * @param  int    $userId
     * @return boolean|string true or error description.
     */
	public function clearSetPasswordToken($userId)
	{
        $stmt = $this->mysqli->prepare("UPDATE users SET set_password_token=NULL,set_password_before=NULL WHERE id=?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $userId);
        $result = $stmt->execute();
        $stmt->close();

        if (!$result) {
            return $stmt->error;
        }

        return true;
	}

	function adminGetUserList()
	{

		$users = $this->mysqli->query("SELECT * FROM users");

		$return = array();

		while ($user = $users->fetch_assoc())

		{
			if (!in_array($user['id'], config('app', 'hide_ids'))) {
                $user['alert_flag'] = $this->shouldBeAwareOfUser($user);
				$return[] = $user;
			}
		}
		return $return;
    }
    
    /**
     * Should you, as an admin, be aware of this user?
     * 
     * @param  array   $user
     * @return boolean
     */
    protected function shouldBeAwareOfUser($user)
    {
        // User has not received bounty yet.
        if ($user['bounty_signup'] && is_null($user['bounty_received_at'])) {
            return true;
        }

        return false;
    }


	function adminGetUserInfo($id)
	{
		if (is_numeric($id) && !in_array($id, config('app', 'hide_ids')))
		{
			$users = $this->mysqli->query("SELECT * FROM users WHERE id=" . $id);
			if ($users->num_rows > 0)
			{
				return $users->fetch_assoc();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	function adminUpdatePassword($id, $newPassword)
	{

        $password = password_hash($newPassword, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);

		if (is_numeric($id) && !in_array($id, config('app', 'hide_ids')))
		{
			$result = $this->mysqli->query("UPDATE users SET password='" . $password . "' WHERE id=" . $id . ";");
			if ($result)
			{
				return true;
			} else {
				return "Error.";
			}
		} else {
			return "User does not exist";
		}
	}

    /**
     * Enable 2FA on user.
     * 
     * @param  string   $secret
     * @param  string   $password the user account password in clear text.
     * @return boolean
     */
    function enableauth($secret, $password)
    {
        $id = $_SESSION['user_id'];

        if (!$id) {
            return false;
        }

        $protectedKey        = KeyProtectedByPassword::createRandomPasswordProtectedKey($password);
        $protectedKeyEncoded = $protectedKey->saveToAsciiSafeString();
        $userKey             = $protectedKey->unlockKey($password);
        $secretEncrypted     = Crypto::encrypt($secret, $userKey);

        $stmt = $this->mysqli->prepare('UPDATE users SET protected_key=?, authused=1, secret=NULL, secret_encrypted=? WHERE id=?');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ssi', $protectedKeyEncoded, $secretEncrypted, $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    function disauth()
    {
 	    $id = $_SESSION['user_id'];

		if ($id) {
            $msg = lang('WALLET_2FA_DISAUTH_COMPLETED');

            $stmt = $this->mysqli->prepare('UPDATE users SET authused=0, secret=NULL, protected_key=NULL, secret_encrypted=NULL WHERE id=?');

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param('i', $id);
            $result = $stmt->execute();
            $stmt->close();

            return "$msg";
        }
    }

    public function adminDeleteAccount($id)
    {
        if (!is_numeric($id) || in_array($id, config('app', 'hide_ids'))) {
            return false;
        }

        $stmt = $this->mysqli->prepare('UPDATE users SET deleted_at=NOW() WHERE id=?');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

	function adminLockAccount($id)
	{
		if (is_numeric($id) && !in_array($id, config('app', 'hide_ids')))
		{
			$users = $this->mysqli->query("UPDATE users SET locked=1 WHERE id=" . $id);
		}
	}

	function adminUnlockAccount($id)
	{
		if (is_numeric($id) && !in_array($id, config('app', 'hide_ids')))
		{
			$users = $this->mysqli->query("UPDATE users SET locked=0 WHERE id=" . $id);
		}
	}

	function adminPrivilegeAccount($id)
	{
		if (is_numeric($id) && !in_array($id, config('app', 'hide_ids')))
		{
			$users = $this->mysqli->query("UPDATE users SET admin=1 WHERE id=" . $id);
		}
	}

	function adminDeprivilegeAccount($id)
	{
		if (is_numeric($id) && !in_array($id, config('app', 'hide_ids')))

		{
			$users = $this->mysqli->query("UPDATE users SET admin=0 WHERE id=" . $id);
		}
	}

//GoogleAuthenticator 
//Created by PHPGangsta

protected $_codeLength = 6;
    /**
     * Create new secret.
     * 16 characters, randomly chosen from the allowed base32 characters.
     *
     * @param int $secretLength
     * @return string
     */
    public function createSecret($secretLength = 16)
    {
        $validChars = $this->_getBase32LookupTable();
        unset($validChars[32]);
        $secret = '';
        for ($i = 0; $i < $secretLength; $i++) {
            $secret .= $validChars[array_rand($validChars)];
        }
        return $secret;
    }
    /**
     * Calculate the code, with given secret and point in time
     *
     * @param string $secret
     * @param int|null $timeSlice
     * @return string
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }
        $secretkey = $this->_base32Decode($secret);
        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        // Hash it with users secret key
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        // Use last nipple of result as index/offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        // grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);
        // Unpak binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;
        $modulo = pow(10, $this->_codeLength);
        return str_pad($value % $modulo, $this->_codeLength, '0', STR_PAD_LEFT);
    }
    /**
     * Get QR-Code URL for image, from google charts
     *
     * @param string $name
     * @param string $secret
     * @param string $title
     * @return string
     */
    public function getQRCodeGoogleUrl($name, $secret, $title = null) {
        $urlencoded = urlencode('otpauth://totp/'.$name.'?secret='.$secret.'');
	if(isset($title)) {
                $urlencoded .= urlencode('&issuer='.urlencode($title));
        }
        return 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl='.$urlencoded.'';
    }
    /**
     * Check if the code is correct. This will accept codes starting from $discrepancy*30sec ago to $discrepancy*30sec from now
     *
     * @param string $secret
     * @param string $code
     * @param int $discrepancy This is the allowed time drift in 30 second units (8 means 4 minutes before or after)
     * @param int|null $currentTimeSlice time slice if we want use other that time()
     * @return bool
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null)
    {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / 30);
        }
        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($calculatedCode == $code ) {
                return true;
            }
        }
        return false;
    }
    /**
     * Set the code length, should be >=6
     *
     * @param int $length
     * @return PHPGangsta_GoogleAuthenticator
     */
    public function setCodeLength($length)
    {
        $this->_codeLength = $length;
        return $this;
    }
    /**
     * Helper class to decode base32
     *
     * @param $secret
     * @return bool|string
     */
    protected function _base32Decode($secret)
    {
        if (empty($secret)) return '';
        $base32chars = $this->_getBase32LookupTable();
        $base32charsFlipped = array_flip($base32chars);
        $paddingCharCount = substr_count($secret, $base32chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) return false;
        for ($i = 0; $i < 4; $i++){
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])) return false;
        }
        $secret = str_replace('=','', $secret);
        $secret = str_split($secret);
        $binaryString = "";
        for ($i = 0; $i < count($secret); $i = $i+8) {
            $x = "";
            if (!in_array($secret[$i], $base32chars)) return false;
            for ($j = 0; $j < 8; $j++) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); $z++) {
                $binaryString .= ( ($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48 ) ? $y:"";
            }
        }
        return $binaryString;
    }
    /**
     * Helper class to encode base32
     *
     * @param string $secret
     * @param bool $padding
     * @return string
     */
    protected function _base32Encode($secret, $padding = true)
    {
        if (empty($secret)) return '';
        $base32chars = $this->_getBase32LookupTable();
        $secret = str_split($secret);
        $binaryString = "";
        for ($i = 0; $i < count($secret); $i++) {
            $binaryString .= str_pad(base_convert(ord($secret[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
        }
        $fiveBitBinaryArray = str_split($binaryString, 5);
        $base32 = "";
        $i = 0;
        while ($i < count($fiveBitBinaryArray)) {
            $base32 .= $base32chars[base_convert(str_pad($fiveBitBinaryArray[$i], 5, '0'), 2, 10)];
            $i++;
        }
        if ($padding && ($x = strlen($binaryString) % 40) != 0) {
            if ($x == 8) $base32 .= str_repeat($base32chars[32], 6);
            elseif ($x == 16) $base32 .= str_repeat($base32chars[32], 4);
            elseif ($x == 24) $base32 .= str_repeat($base32chars[32], 3);
            elseif ($x == 32) $base32 .= $base32chars[32];
        }
        return $base32;
    }
    /**
     * Get array with all 32 characters for decoding from/encoding to base32
     *
     * @return array
     */
    protected function _getBase32LookupTable()
    {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
            '='  // padding char
        );
    }
}

?>
