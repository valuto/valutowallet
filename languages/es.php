<?php
/*
------------------
Language: Spanish
------------------
*/

return [

    // Master template
    'MASTER_MENU_LOGIN' => 'Start',
    'MASTER_MENU_SIGNUP' => '',
    'MASTER_MENU_ABOUT' => '',
    'MASTER_MENU_HELP' => '',
    'MASTER_MENU_WALLET' => 'Wallet',
    'MASTER_MENU_LOGOUT' => 'Logout',
    'MASTER_MENU_ACCOUNT' => 'Account',
    'MASTER_FOOTER_DESCR' => 'Valutowallet.com - The official Valuto wallet and marketplace. ',
    'MASTER_FOOTER_RIGHTSRESERVED' => 'ALL RIGHTS RESERVED',

    //Home Page

    'FORM_LOGIN' => 'Iniciar sesión',
    'FORM_USER' => 'Nombre de usuario',
    'FORM_PASS' => 'Contraseña',
    'FORM_PASSCONF' => 'Confirmar contraseña',
    'FORM_PASSCONF_HELP' => 'Por favor, repite tu contraseña.',
    'FORM_2FA' => 'Autenticación de 2 factores',
    'FORM_2FA_PLACEHOLDER' => 'Código de autenticación',
    'FORM_2FA_HELP' => 'Ingrese su código de autenticación 2FA, si tiene habilitada la autenticación 2FA.',
    'FORM_SIGNUP' => 'Regístrate',
    'FORM_CREATE' => 'Crear una nueva cuenta',

    // Wallet Page

    'WALLET_HELLO' => 'Hola',
    'WALLET_OVERVIEW_HEADLINE' => 'Tu billetera',
    'WALLET_TRANSACTIONS_HEADLINE' => 'Transacciones',
    'WALLET_ACCOUNT_HEADLINE' => 'Cuenta',
    'WALLET_BALANCE' => 'Saldo actual:',
    'WALLET_USERLINKS' => 'Enlaces de usuario:',
    'WALLET_LOGOUT' => 'Cerrar sesión',
    'WALLET_SUPPORT' => 'Apoyar',
    'WALLET_2FAON' => 'Habilitar autenticación de factor 2',
    'WALLET_2FAOFF' => 'Deshabilitar autenticación de factor 2',
    'WALLET_2FAVERIFY_WRITE_IT_DOWN' => '* Por favor escriba esto y manténgalo en un área segura. *',
    'WALLET_2FAVERIFY_SCAN_QR' => 'Escanee esto con la aplicación Google Authenticator en su teléfono móvil. Esta página se borrará al actualizar, por favor tenga cuidado.',
    'WALLET_2FAVERIFY_DESC' => 'Por favor, introduzca un código del autenticador a continuación.',
    'WALLET_2FAVERIFY_ERROR_INPUT' => 'Debe ingresar un código del autenticador.',
    'WALLET_2FAVERIFY_ERROR_INVALID' => 'Los códigos no coinciden. Asegúrate de haber configurado correctamente la autenticación.',
    'WALLET_2FAVERIFY_ERROR_INVALID_PASSWORD' => 'La contraseña de la cuenta que ingresó no era correcta. Inténtalo de nuevo.',
    'WALLET_2FAVERIFY_ERROR_NOT_SETUP' => 'La autenticación de dos factores no se ha habilitado en su cuenta.',
    'WALLET_2FAVERIFY_SUCCESS' => 'La autenticación de dos factores ahora está habilitada. Esta página se actualizará en un momento.',
    'WALLET_2FAVERIFY_ABORT' => 'Abortar',
    'WALLET_2FAVERIFY_PASSWORD' => 'Contraseña de la cuenta',
    'WALLET_2FAVERIFY_PASSWORD_PLACEHOLDER' => 'Su cuenta Contraseña',
    'WALLET_2FACODE' => 'Código de autenticador',
    'WALLET_2FACODE_ACCEPT_SECURE_KEY' => 'Reconozco que tengo una copia de la clave secreta.',
    'WALLET_2FAVERIFY_STEP1_HEADLINE' => 'Paso 1: clave secreta',
    'WALLET_2FAVERIFY_STEP2_HEADLINE' => 'Paso 2: autenticador',
    'WALLET_2FAVERIFY_STEP3_HEADLINE' => 'Paso 3: Verificar',
    'WALLET_2FAVERIFY' => 'Verificar',
    'WALLET_PASSUPDATE' => 'Tu contraseña',
    'WALLET_PASSUPDATEOLD' => 'Contraseña actual',
    'WALLET_PASSUPDATENEW' => 'Nueva contraseña',
    'WALLET_PASSUPDATENEWCONF' => 'Confirmar nueva contraseña',
    'WALLET_PASSUPDATECONF' => 'Actualiza contraseña',
    'WALLET_SUPPORTNOTE' => 'Haga clic en Soporte arriba y anote su clave. Se usará para ayudarlo a identificarse si alguna vez olvida su contraseña. Este código también cambia cada vez que cambias tu contraseña.',
    'WALLET_SEND' => 'Enviar fondos',
    'WALLET_ADDRESS_RECEIVER' => 'Dirección del receptor',
    'WALLET_ADDRESS' => 'Dirección',
    'WALLET_AMOUNT' => 'Cantidad',
    'WALLET_AMOUNT_VLU' => 'Cantidad en VLU',
    'WALLET_SENDCONF' => 'Enviar',
    'WALLET_USERADDRESSES' => 'Tus direcciones',
    'WALLET_NEWADDRESS' => 'Obtener una nueva dirección',
    'WALLET_QRCODE' => 'QR Code',
    'WALLET_LAST10' => 'Últimas 10 transacciones',
    'WALLET_DATE' => 'Fecha',
    'WALLET_TYPE' => 'Type',
    'WALLET_FEE' => 'Cuota',
    'WALLET_CONFS' => 'Confs',
    'WALLET_INFO' => 'Información',
    'WALLET_SYSTEM_ERROR' => 'Error del sistema',
    'WALLET_UNKNOWN_ERROR' => 'Se produjo algún tipo de error.',
    'WALLET_WITHDRAW_INFO' => 'Escriba la dirección del destinatario, la cantidad que desea enviar y haga clic <strong>enviar</strong>',
    'WALLET_WITHDRAW_BALANCE' => 'La cantidad de retiro excede el saldo de su billetera. Tenga en cuenta que el propietario de la billetera ha establecido una tarifa de reserva de ',
    'WALLET_WITHDRAW_MISSING_FIELDS' => 'Tienes que llenar todos los campos',
    'WALLET_WITHDRAW_TEMP_DISABLED' => 'Los retiros están deshabilitados temporalmente',
    'WALLET_WITHDRAW_SUCCESSFUL' => 'Retiro exitoso',
    'WALLET_TOKENS_DO_NOT_MATCH' => 'Los tokens no coinciden',
    'WALLET_PASSWORD_UPDATED_SUCCESSFUL' => 'Contraseña actualizada exitosamente.',
    'WALLET_PASSWORD_MISSING_FIELDS' => 'Tienes que llenar todos los campos',
    'WALLET_REGISTER_RELOGIN_FOR_SUPPORTPIN' => 'Por favor vuelva a iniciar sesión para Clave de Soporte',
    'WALLET_LOGIN_ACCOUNT_LOCKED' => 'La cuenta está bloqueada. Póngase en contacto con soporte para más información.',
    'WALLET_LOGIN_INCORRECT' => 'Nombre de usuario, contraseña o 2 factor es incorrecto.',
    'WALLET_REGISTER_MISSING_FIELDS' => 'Por favor complete todos los campos',
    'WALLET_REGISTER_PASSWORD_NOT_MATCH' => 'Las contraseñas no coinciden',
    'WALLET_REGISTER_USERNAME_LENGTH' => 'El nombre de usuario debe tener entre 3 y 30 caracteres',
    'WALLET_REGISTER_PASSWORD_LENGTH' => 'La contraseña debe tener más de 3 caracteres',
    'WALLET_REGISTER_USERNAME_IN_USE' => 'Nombre de usuario ya tomado',
    'WALLET_UPDATEPW_NOT_MATCH' => 'Las contraseñas no coinciden.',
    'WALLET_UPDATEPW_INCORRECT_PW' => 'La contraseña es incorrecta.',
    'WALLET_2FA_DISAUTH_COMPLETED' => 'La autorización de dos factores se ha desactivado para su cuenta y ya no será necesaria cuando inicie sesión.',
    'WALLET_NOTICE' => 'Nota',
    'WALLET_DONATE_LINK' => '¡Done al dueño de Valutowallet!',
    'WALLET_DONATE_INFO' => 'Escriba la cantidad que desea donar y haga clic <strong>Enviar</strong>',
    'WALLET_WITHDRAW_CONFIRM' => '¿Estás seguro, quieres enviar :amount VLU a ":address"?\n\nEsta acción no se puede deshacer.',
    'WALLET_FRONTEND_AJAX_ERROR' => 'Ocurrió un error. Por favor, asegúrese de que su sesión aún esté activa.',
    'WALLET_TRANSACTIONS_TABLE_RECEIVED' => 'Recibido',
    'WALLET_TRANSACTIONS_TABLE_SENT' => 'Expedido',
    'WALLET_TRANSACTIONS_TABLE_INFO' => 'Información',
    'WALLET_NOTICE_ENABLE_2FA' => 'Two-factor authentication is an extra layer of security for your wallet account. Please enable two-factor authentication under the tab "Account".',
    'WALLET_NOTICE_UPDATE_PASSWORD' => 'Please update your password from the "Account" tab',
    'WALLET_NOTICE_BOUNTY_PENDING' => 'Your bounty is being processed and will be transferred to your account soon! Thanks for signing up for Valutowallet/VLU Market!',

    // Disclaimer

    'WALLET_DISCLAIMER_STEP1_HEADLINE' => 'What is ValutoWallet?',
    'WALLET_DISCLAIMER_STEP1_DESCR' => 'ValutoWallet is a free, open-source interface to the Valuto blockchain.<br><br>

It allows you to interact directly with the blockchain, while remaining in full control of your addresses & your funds.<br><br>

<strong>You and <u>only</u> you are responsible for your security.<br>
Always choose strong passwords and utilize two factor authentication.<br><br>

Only use ValutoWallet and Valuto (VLU), if you are familiar with digital assets and the risks associated.</strong>',
    'WALLET_DISCLAIMER_STEP1_ACCEPT' => 'Understood',
    'WALLET_DISCLAIMER_STEP2_HEADLINE' => 'ValutoWallet is <strong>NOT</strong> a bank',
    'WALLET_DISCLAIMER_STEP2_DESCR' => '<ul>
    <li>When you open a bank account, they create an account in their system for you, upon an agreement.</li>
    <li>The bank keeps track of all personal information, private data, balances, transactions etc.</li>
    <li>When you have an account at a bank, they are in definite control over your funds and provide all services needed.</li>
</ul>

<p><strong>ValutoWallet is NONE of these things.<br>
The sole responsibility for managing your ValutoWallet, loss of funds etc., lies on you as a private user.</strong></p>',
    'WALLET_DISCLAIMER_STEP2_ACCEPT' => 'Understood',
    'WALLET_DISCLAIMER_STEP3_HEADLINE' => 'ValutoWallet is an interface',
    'WALLET_DISCLAIMER_STEP3_DESCR' => '<ul>
    <li>When you create an account, the server at valutowallet.com is generating a cryptographic set of numbers, i.e. your private key and your public key (address).</li>
    <li>The handling of your keys happens entirely on our system. </li>
    <li>Valutowallet is indemnified in regards to any loss of funds, damage to businesses or similar events.</li>
    <li>When you use Valutowallet to interfere on the Valuto blockchain network, we do not charge a fee. There could however be fees involved in a transaction, which are decided by the network, depending on load etc.</li>
    <li>You are using the interface to interact directly with the blockchain, sending funds to a wrong address WILL result in loss of funds. </li>
    <li>If you send your VLU Address to someone, they can transfer VLU to you. </li>
    <li>If you send your private keys to someone, they now have full control of the account associated with it. </li>
</ul>',
    'WALLET_DISCLAIMER_STEP3_ACCEPT' => 'Understood',
    'WALLET_DISCLAIMER_STEP4_HEADLINE' => 'What is a Blockchain?',
    'WALLET_DISCLAIMER_STEP4_DESCR' => '<ul>
    <li>A blockchain is a huge, global, distributed (i.e. decentralized) ledger, similar to a spreadsheet.</li>
    <li>It keeps track of who sent how many coins to whom, and what the balance of every account is. </li>
    <li>It is stored, secured and maintained by thousands of people (miners), across the globe. </li>
    <li>The blocks in the blockchain are made up of all transactions from the ValutoWallet, valutod core, ValutoPy or other compatible wallets.</li>
    <li>When you see your balance on ValutoWallet / view your transactions on vluchain.info, you are looking at data currently present on the blockchain, and not our servers. </li>
</ul>',
    'WALLET_DISCLAIMER_STEP4_ACCEPT' => 'Understood',
    'WALLET_DISCLAIMER_STEP5_HEADLINE' => 'Again: WE ARE <strong>NOT A BANK</strong>',
    'WALLET_DISCLAIMER_STEP5_DESCR' => 'Why is this necessary to know? <br><br>

ValutoWallet and vlu market ltd <strong>will</strong> not: <br><br>
- Access your account or transfer your funds for you.<br>
- Recover a password - only reset them. <br><br>

And can not:<br>
- Recover or change 2FA (Two Factor Authentication)<br>
- Reserve, cancel, or refund transactions.<br>
- Freeze accounts.<br><br>

<strong>You and only you are responsible for your security.<br><br>

Always keep your private key and password safe.<br>
If you enter your private key on a phishing website, you will have all your funds taken.</strong>',
    'WALLET_DISCLAIMER_STEP5_ACCEPT' => 'Understood',
    'WALLET_DISCLAIMER_STEP6_HEADLINE' => 'If ValutoWallet can\'t do those things, what\'s the point?',
    'WALLET_DISCLAIMER_STEP6_DESCR' => '<ul>
    <li>The point of the Valuto Blockchain is using decentralization, as a way to secure your funds. </li>
    <li>You don’t have to rely on your bank, government, or anybody else, when you want to transfer funds, pay bills, buy products etc. </li>
    <li>You don’t have to rely on the integrity of banks and multinational corporations, in regards to your finances.</li>
    <li>The Valuto blockchain can never be seized and/or shut down by any national state or government body.</li>
</ul>

<strong>Protect your keys & always check that you are on the correct URL when entering them.<br>
You are as a user of valutowallet.com responsible for the security of your funds.</strong>',
    'WALLET_DISCLAIMER_STEP6_ACCEPT' => 'Understood',

    // User activation
    'USER_ACTIVATION_TOKEN_FAILED' => 'Your activation failed. Either you waited too long from initial signup, or you have supplied a wrong activation token.',
    'FORM_ACTIVATION_HEADLINE' => 'Choose password',
    'FORM_ACTIVATE' => 'Activate',

    // User profile
    'WALLET_PARTICULARS' => 'Profile',
    'WALLET_PARTICULARS_FIRST_NAME' => 'First name',
    'WALLET_PARTICULARS_LAST_NAME' => 'Last name',
    'WALLET_PARTICULARS_ADDRESS_1' => 'Address',
    'WALLET_PARTICULARS_ADDRESS_2' => 'Address (second line)',
    'WALLET_PARTICULARS_ZIP_CODE' => 'Zip code',
    'WALLET_PARTICULARS_CITY' => 'City',
    'WALLET_PARTICULARS_STATE' => 'State',
    'WALLET_PARTICULARS_COUNTRY' => 'Country',
    'WALLET_PARTICULARS_EMAIL' => 'E-mail',
    'WALLET_PARTICULARS_UPDATE_PROFILE' => 'Update',
    'WALLET_PARTICULARS_UPDATE_SUCCESSFUL' => 'Your profile was updated successfully.',
    'WALLET_PARTICULARS_UPDATE_ERROR' => 'An error occurred, please try again or contact info@valuto.io.',

];