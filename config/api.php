<?php

return [

    /**
     * Private key file path.
     * 
     * @var string
     */
    'private_key_path' => env('API_PRIVATE_KEY_PATH', 'file://'),

    /**
     * Private key passphrase. Optional.
     * 
     * @var boolean|string
     */
    'private_key_passphrase' => env('API_PRIVATE_KEY_PASSPHRASE', false),

    /**
     * Public key file path.
     * 
     * @var string
     */
    'public_key_path' => env('API_PUBLIC_KEY_PATH', 'file://'),

    /**
     * Encryption key.
     * 
     * Generate using base64_encode(random_bytes(32)).
     * 
     * @var string
     */
    'encryption_key' => env('API_ENCRYPTION_KEY', ''),

    /**
     * Only allow API access from this list of IP addresses.
     * 
     * @var array
     */
    'ip_whitelist' => env('API_IP_WHITELIST') ? explode(',', env('API_IP_WHITELIST')) : [],

    /**
     * Only allow access from this list of origins.
     * 
     * @var array
     */
    'cors_whitelist' => explode(',', env('API_CORS_WHITELIST')),

    /**
     * List of clients allowed to access the API.
     * 
     * @var array
     */
    'clients' => [
        'valutobounty' => [
            'secret'          => password_hash(env('API_BOUNTY_CLIENT_SECRET'), PASSWORD_BCRYPT),
            'name'            => 'Valutobounty',
            'redirect_uri'    => 'http://foo/bar',
            'is_confidential' => true,
            'allowed_grant_types' => [
                'client_credentials',
            ],
        ],
        'vlumarketsystem' => [
            'secret'          => password_hash(env('API_VLUMARKET_SYSTEM_CLIENT_SECRET'), PASSWORD_BCRYPT),
            'name'            => 'VLU Market system',
            'redirect_uri'    => 'http://foo/bar',
            'is_confidential' => true,
            'allowed_grant_types' => [
                'client_credentials',
            ],
        ],
        'vlumarketusers' => [
            'secret'          => password_hash(env('API_VLUMARKET_USERS_CLIENT_SECRET'), PASSWORD_BCRYPT),
            'name'            => 'VLU Market users',
            'redirect_uri'    => 'http://foo/bar',
            'is_confidential' => true,
            'allowed_grant_types' => [
                'password',
                'refresh_token',
            ],
        ],
    ],

    /**
     * Access token expire after this amount of time.
     * 
     * @var string
     */
    'access_token_expiration' => 'PT6H',
];