<?php

namespace Controllers\Vlumarket;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;

use Repositories\Authentication\RefreshTokenRepository;
use Repositories\Authentication\UserRepository;
use Repositories\Authentication\AccessTokenWithoutHttpRepository;

class RedirectWithAuthController extends Controller
{
    /**
     * Redirect to VLU Market with authenticated valutowallet OAuth2 token.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function show(ServerRequestInterface $request)
    {   
        $params = $request->getQueryParams();

        $accessTokenRepository = new AccessTokenWithoutHttpRepository(
            new UserRepository(),
            new RefreshTokenRepository()
        );
        $jwt = $accessTokenRepository->issue();

        header("Location: " . env('VLU_MARKET_URL') . '/auth/signin-and-redirect?jwt=' . urlencode(base64_encode($jwt)) . '&url=' . urlencode($params['url']));
    }

}