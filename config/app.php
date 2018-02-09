<?php

return [

    /**
     * ENTER WEBSITE URL ALONG WITH A TRAILING SLASH
     */
    'server_url' => 'valutowallet.local/',

    /**
     * Default language
     */
    'default_lang' => 'en',
    
    /**
     * Withdrawals enabled
     * 
     * Disable withdrawals during maintenance
     */
    'withdrawals_enabled' => true,

    /**
     * Website Title (Do Not include 'wallet')
     */
    'fullname' => "Valuto",
    
    /**
     * Coin Short (BTC)
     */
    'short' => "VLU",
    
    /**
     * Blockchain Url
     */
    'blockchain_url' => "https://vluchain.info/tx/",
    
    /**
     * Your support eMail
     */
    'support' => "support@valuto.io",
    
    /**
     * Hide account from admin dashboard
     */
    'hide_ids' => array(1),
    
    /**
     * Donation Address
     */
    'donation_address' => "VWTzPCKwTSd8R25iacwZy6jwZMku52pxAt",
    
    /**
     * This fee acts as a reserve. The users balance
     * will display as the balance in the daemon minus
     * the reserve. We don't reccomend setting this more
     * than the Fee the daemon charges.
     */
    'reserve' => "0.0001",

];