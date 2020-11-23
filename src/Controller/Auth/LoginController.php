<?php

namespace Kuusamo\Vle\Controller\Auth;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Exception\ProcessException;
use Kuusamo\Vle\Helper\Password;
use Kuusamo\Vle\Helper\UrlUtils;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DateTime;

class LoginController extends Controller
{
    public function login(Request $request, Response $response)
    {
        if ($this->ci->get('auth')->isLoggedIn()) {
            return $response->withRedirect('/dashboard');
        }

        if ($request->isPost()) {
            if ($request->getParam('action') == 'magicLink') {
                $user = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\User')->findOneBy(['email' => $request->getParam('email')]);

                if (!$user) {
                    $this->alertDanger('This email addresss is not registered');
                } else {
                    $this->ci->get('email')->sendMagicLinkEmail($user);
                    return $this->renderPage($request, $response, 'auth/magic-link-sent.html');
                }
            } elseif ($request->getParam('action') == 'login') {
                try {
                    if (!$this->ci->get('session')->getCsrfToken()->isValid($request->getParam('csrf'))) {
                        throw new ProcessException('Request blocked for security reasons');
                    }

                    $user = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\User')->findOneBy(['email' => $request->getParam('email')]);

                    if (!$user) {
                        throw new ProcessException('This email address is not registered to any account');
                    }

                    if (!Password::verify($user->getPassword(), $request->getParam('password'))) {
                        throw new ProcessException('Incorrect email or password');
                    }

                    if ($user->getStatus() !== User::STATUS_ACTIVE) {
                        throw new ProcessException('This user account has been disabled');
                    }

                    $user->setLastLogin(new DateTime);
                    $this->ci->get('auth')->authoriseUser($user);

                    $returnTo = $request->getParam('from') ? UrlUtils::sanitiseInternal($request->getParam('from')) : '/dashboard';
                    return $response->withRedirect($returnTo);
                } catch (ProcessException $e) {
                    $this->alertDanger($e->getMessage());
                }
            }
        }

        $this->ci->get('meta')->setTitle('Login');

        return $this->renderPage($request, $response, 'auth/login.html', [
            'from' => $request->getParam('from'),
            'email' => $request->getParam('email'),
            'csrf' => $this->ci->get('session')->getCsrfToken()->getToken()
        ]);
    }
}
