<?php

return [

    /**
     * Private key file path.
     * 
     * @var string
     */
    'private_key_path' => 'file://',

    /**
     * Private key passphrase. Optional.
     * 
     * @var boolean|string
     */
    'private_key_passphrase' => false,

    /**
     * Public key file path.
     * 
     * @var string
     */
    'public_key_path' => 'file://',

    /**
     * Encryption key.
     * 
     * Generate using base64_encode(random_bytes(32)).
     * 
     * @var string
     */
    'encryption_key' => '',

    /**
     * Only allow API access from this list of IP addresses.
     * 
     * @var array
     */
    'ip_whitelist' => [],

    /**
     * List of clients allowed to access the API.
     * 
     * @var array
     */
    'clients' => [
        'clientid' => [
            'secret'          => password_hash('', PASSWORD_BCRYPT),
            'name'            => 'clientid',
            'redirect_uri'    => 'http://foo/bar',
            'is_confidential' => true,
        ],
    ],
    

    /**
     * Access token expire after this amount of time.
     * 
     * @var string
     */
    'access_token_expiration' => 'PT6H',
];