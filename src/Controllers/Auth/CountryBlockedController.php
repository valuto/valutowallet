<?php

namespace Controllers\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Traits\RenderMessage;

class CountryBlockedController extends Controller
{
    use RenderMessage;
    
    /**
     * Show country is blocked.
     * 
     * @return 
     */
    public function show(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();

        return $this->renderMessageView(lang('COUNTRY_BLOCKED'));
    }
}