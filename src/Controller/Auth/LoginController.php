<?php

namespace Kuusamo\Vle\Controller\Auth;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Exception\ProcessException;
use Kuusamo\Vle\Helper\Password;
use Kuusamo\Vle\Helper\TokenGenerator;
use Kuusamo\Vle\Helper\UrlUtils;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DateTime;

class LoginController extends Controller
{
    const DEFAULT_REDIRECT = '/';

    public function login(Request $request, Response $response)
    {
        if ($this->ci->get('auth')->isLoggedIn()) {
            return $response->withRedirect(self::DEFAULT_REDIRECT);
        }

        if ($request->isPost()) {
            if ($request->getParam('action') == 'magicLink') {
                try {
                    if (!$this->ci->get('session')->getCsrfToken()->isValid($request->getParam('csrf'))) {
                        throw new ProcessException('Request blocked for security reasons');
                    }

                    $user = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\User')->findOneBy(['email' => $request->getParam('email')]);

                    if (!$user) {
                        throw new ProcessException('This email address is not registered to any account');
                    }

                    $this->ci->get('email')->sendMagicLinkEmail($user);
                    return $this->renderPage($request, $response, 'auth/magic-link-sent.html');
                } catch (ProcessException $e) {
                    $this->alertDanger($e->getMessage());
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

                    $returnTo = $request->getParam('from') ? UrlUtils::sanitiseInternal($request->getParam('from')) : self::DEFAULT_REDIRECT;
                    return $response->withRedirect($returnTo);
                } catch (ProcessException $e) {
                    $this->alertDanger($e->getMessage());
                }
            }
        }

        if ($request->getParam('token')) {
            $result = $this->loginWithToken($request->getParam('token'));

            if ($result === true) {
                return $response->withRedirect(self::DEFAULT_REDIRECT);
            }
        }

        $this->ci->get('meta')->setTitle('Login');

        return $this->renderPage($request, $response, 'auth/login.html', [
            'from' => $request->getParam('from'),
            'email' => $request->getParam('email'),
            'csrf' => $this->ci->get('session')->getCsrfToken()->getToken()
        ]);
    }

    /**
     * Attempt to authorise a user via their security token.
     *
     * @param string $token Token.
     * @return boolean
     */
    private function loginWithToken(string $token): bool
    {
        $user = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\User')->findOneBy(['securityToken' => $token]);

        if (!$user) {
            $this->alertDanger('This magic link has expired. You can generate a new one below.');
            return false;
        }

        if ($user->getStatus() !== User::STATUS_ACTIVE) {
            $this->alertDanger('This user account has been disabled. Please contact us for assistance.');
            return false;
        }

        $user->setSecurityToken(TokenGenerator::generate());
        $user->setLastLogin(new DateTime);

        $this->ci->get('db')->persist($user);
        $this->ci->get('db')->flush();

        $this->ci->get('auth')->authoriseUser($user);

        return true;
    }
}
