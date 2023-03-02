<?php

namespace Kuusamo\Vle\Controller\Auth;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\SsoToken;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Exception\ProcessException;
use Kuusamo\Vle\Helper\Environment;
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
            if ($request->getQueryParam('sso_token')) {
                $this->pruneSsoToken($request->getQueryParam('sso_token'));
            }

            return $response->withRedirect($this->returnTo($request));
        }

        if ($request->isPost()) {
            if ($request->getParam('action') == 'magicLink') {
                try {
                    if (!$this->ci->get('session')->getCsrfToken()->isValid($request->getParam('csrf'))) {
                        throw new ProcessException('Request blocked for security reasons');
                    }

                    $user = $this->ci->get('db')->getRepository(User::class)->findOneBy(['email' => $request->getParam('email')]);

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

                    $user = $this->ci->get('db')->getRepository(User::class)->findOneBy(['email' => $request->getParam('email')]);

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

                    return $response->withRedirect($this->returnTo($request));
                } catch (ProcessException $e) {
                    $this->alertDanger($e->getMessage());
                }
            }
        }

        if ($request->getQueryParam('token')) {
            $result = $this->loginWithToken($request->getQueryParam('token'));

            if ($result === true) {
                return $response->withRedirect(self::DEFAULT_REDIRECT);
            }
        }

        if ($request->getQueryParam('sso_token')) {
            $result = $this->loginWithSsoToken($request->getQueryParam('sso_token'));

            if ($result === true) {
                return $response->withRedirect($this->returnTo($request));
            }
        }

        $this->ci->get('meta')->setTitle('Login');

        if (Environment::get('SSO_LOGIN_URL')) {
            return $this->renderPage($request, $response, 'auth/sso.html', [
                'url' => Environment::get('SSO_LOGIN_URL'),
            ]);
        }

        return $this->renderPage($request, $response, 'auth/login.html', [
            'from' => $request->getParam('from'),
            'email' => $request->getParam('email'),
            'csrf' => $this->ci->get('session')->getCsrfToken()->getToken()
        ]);
    }

    /**
     * Build the URL to redirect the user back to.
     *
     * @param Request $request Request.
     * @return string
     */
    private function returnTo(Request $request): string
    {
        return $request->getParam('from') ? UrlUtils::sanitiseInternal($request->getParam('from')) : self::DEFAULT_REDIRECT;
    }

    /**
     * Attempt to authorise a user via their security token.
     *
     * @param string $token Token.
     * @return boolean
     */
    private function loginWithToken(string $token): bool
    {
        $user = $this->ci->get('db')->getRepository(User::class)->findOneBy(['securityToken' => $token]);

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

    /**
     * Attempt to authorise a user via an SSO token.
     *
     * @param string $token Token.
     * @return boolean
     */
    private function loginWithSsoToken(string $token): bool
    {
        $token = $this->ci->get('db')->getRepository(SsoToken::class)->findOneBy(['token' => $token]);

        if (!$token) {
            $this->alertDanger('Token expired.');
            return false;
        }

        if ($token->hasExpired()) {
            $dql = "DELETE Kuusamo\Vle\Entity\SsoToken t
                    WHERE t.expires < CURRENT_TIMESTAMP()";
            $query = $this->ci->get('db')->createQuery($dql);
            $query->execute();

            $this->alertDanger('Token expired.');
            return false;
        }

        if ($token->getUser()->getStatus() !== User::STATUS_ACTIVE) {
            $this->alertDanger('This user account has been disabled. Please contact us for assistance.');
            return false;
        }

        $this->ci->get('db')->remove($token);
        $this->ci->get('db')->flush();

        $this->ci->get('auth')->authoriseUser($token->getUser());

        return true;
    }

    /**
     * If the user is already logged in, burn the SSO token.
     *
     * @param string $token Token.
     * @return void
     */
    private function pruneSsoToken(string $token): void
    {
        $dql = "DELETE Kuusamo\Vle\Entity\SsoToken t
                WHERE t.token = :token";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('token', $token);
        $query->execute();
    }
}
