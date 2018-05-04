<?php

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Services\Bounty\Signup\Admin as BountyService;
use Models\User;
use Models\Flash;

class AdminBountyController extends Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * The admin bounty service instance.
     * 
     * @var Services\Bounty\Signup\Admin
     */
    protected $bounty;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $mysqli;

        $this->user = new User($mysqli);
        $this->bounty = new BountyService();

        parent::__construct();
    }

    /**
     * Process bounty payout to user.
     * 
     * @return string JSON response data.
     */
    public function store(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();

        $receiver = $this->user->adminGetUserInfo($params['user_id']);

        if ( ! is_null($receiver['bounty_received_at'])) {
            return json('error', 'bounty_already_paid');
        }

        if ((int)$receiver['bounty_signup'] !== 1) {
            return json('error', 'user_not_signed_up_for_bounty');
        }

        try {
            $status = $this->bounty->payout($receiver);
        } catch (\Exception $e) {
            return json('error', $e->getMessage());
        }

        $this->bounty->updateUser($receiver['id']);

        Flash::save('flashNotice', 'Bounty paid out to user successfully.');

        return redirect('/admin/info?i=' . $receiver['id']);
    }
}