<?php

namespace Kuusamo\Vle\Controller;

use Kuusamo\Vle\Helper\Password;
use Kuusamo\Vle\Helper\TokenGenerator;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AccountController extends Controller
{
    public function account(Request $request, Response $response)
    {
        $user = $this->ci->get('auth')->getUser();

        if ($request->isPost()) {
            $this->changePassword($request);
        }

        $this->ci->get('meta')->setTitle('My Account');

        return $this->renderPage($request, $response, 'account/account.html', [
            'hasPassword' => $user->getPassword() !== null
        ]);
    }

    /**
     * Logic for password change.
     *
     * @param Request $request Server request.
     * @return boolean
     */
    private function changePassword(Request $request)
    {
        $user = $this->ci->get('auth')->getUser();

        if ($user->getPassword() && Password::verify($user->getpassword(), $request->getParam('old')) == false) {
            $this->alertDanger('Your current password did not match');
            return false;
        }

        if (strlen($request->getParam('new')) < 6) {
            $this->alertDanger('Your password is too short');
            return false;
        }

        if ($request->getParam('new') != $request->getParam('confirm')) {
            $this->alertDanger('Your new passwords do not match');
            return false;
        }

        $user->setPassword(Password::hash($request->getParam('new')));

        $this->ci->get('db')->persist($user);
        $this->ci->get('db')->flush();

        $this->alertSuccess('Password changed successfully');
        return true;
    }
}
