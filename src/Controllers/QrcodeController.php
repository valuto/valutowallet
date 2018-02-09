<?php

namespace Controllers;

class QrcodeController extends Controller
{

    /**
     * Get QR code
     */
    public function show()
    {
        include __DIR__ . '/../../qrgen/phpqrcode/qrlib.php';

        $address = $_GET['address'];
        return \QRcode::png($address);
    }
    
}