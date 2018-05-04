<?php

namespace Controllers\Api\V1;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;
use Models\Client;
use Services\Bounty\Signup\User as UserBounty;

class UserActivateController extends Controller
{
    /**
     * The database instance.
     * 
     * @var MySQLi
     */
    protected $mysqli;

    /**
     * The user bounty service instance.
     * 
     * @var Services\Bounty\Signup\User
     */
    protected $bountyService;

    /**
     * Construct controller with dependencies.
     * 
     * @return void
     */
    public function __construct()
    {
        global $mysqli;

        $this->mysqli = $mysqli;
        $this->bountyService = new UserBounty();
    }

    /**
     * Show activation form for user.
     * 
     * @return 
     */
    public function show(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();

        $user = (new User($this->mysqli))->getUserByActivationToken($params['user_id'], $params['token']);

        if ( ! $user || ! is_array($user)) {
            return $this->renderMessageView(lang('USER_ACTIVATION_TOKEN_FAILED'));
        }

        include __DIR__ . "/../../../../view/header.php";
        include __DIR__ . "/../../../../view/api/v1/user/activate.php";
        include __DIR__ . "/../../../../view/footer.php";
    }

    /**
     * Activate the user by setting the user password.
     * 
     * @return 
     */
    public function store(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();

        $userId = (int)$params['user_id'];
        
        $model = new User($this->mysqli);
        $user = $model->getUserByActivationToken($userId, $params['token']);

        if ( ! $user || ! is_array($user)) {
            return $this->renderMessageView(lang('USER_ACTIVATION_TOKEN_FAILED'));
        }

        if ($params['password'] !== $params['confirmPassword']) {
            return $this->renderMessageView(lang('WALLET_REGISTER_PASSWORD_NOT_MATCH'));
        }

        $model->setPassword($userId, $params['password']);
        $model->clearSetPasswordToken($userId);

        $authedUser = $model->logIn($user['username'], $params['password']);

        if ( ! is_array($authedUser)) {
            return $this->renderMessageView(lang('WALLET_SYSTEM_ERROR'));
        }

        $model->setAuthSession($authedUser);

        $this->bountyService->prepareForBountyPayout($user);

        return redirect('');
    }

    /**
     * Render a message view.
     * 
     * @param  string $message
     * @return void
     */
    protected function renderMessageView($message)
    {
        include __DIR__ . "/../../../../view/header.php";
        include __DIR__ . "/../../../../view/api/v1/user/message.php";
        include __DIR__ . "/../../../../view/footer.php";
    }
}