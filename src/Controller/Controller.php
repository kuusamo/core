<?php

namespace Kuusamo\Vle\Controller;

use Kuusamo\Vle\Helper\Environment;
use Kuusamo\Vle\Service\Templating\Decache;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class Controller
{
    protected $ci;

    /**
     * Array of alerts to show at the top of the page.
     *
     * @var array
     */
    private $alerts = [];

    /**
     * Constructor.
     *
     * @param ContainerInterface $ci Service container.
     */
    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;

        if ($this->ci->get('session')->getFlash()->has('success')) {
            $this->alertSuccess($this->ci->get('session')->getFlash()->get('success'));
        }
    }

    /**
     * Convenience method for rendering a page.
     *
     * @param Request  $request  Request object.
     * @param Response $response Response object.
     * @param string   $template Template name.
     * @param array    $data     Data.
     */
    protected function renderPage(Request $request, Response $response, $template, $data = [])
    {
        $data = array_merge($data, [
            'alerts' => $this->alerts,
            'siteName' => Environment::get('SITE_NAME'),
            'headerTags' => $this->ci->get('settings')->get('HEADER_TAGS'),
            'footerText' => $this->ci->get('settings')->get('FOOTER_TEXT'),
            'css' => new Decache('css'),
            'js' => new Decache('js')
        ]);

        return $this->ci->get('templating')->renderPage(
            $response,
            $template,
            $data,
            $this->ci->get('meta')
        );
    }

    /**
     * Add a danger alert.
     *
     * @param string $message Message.
     * @return void
     */
    protected function alertDanger($message)
    {
        $this->alerts[] = [
            'type' => 'danger',
            'message' => $message
        ];
    }

    /**
     * Add a success alert.
     *
     * @param string  $message Message.
     * @param boolean flash    Flash the message on next request?
     * @return void
     */
    protected function alertSuccess($message, bool $flash = false)
    {
        if ($flash) {
            $this->ci->get('session')->getFlash()->set('success', $message);
            return;
        }

        $this->alerts[] = [
            'type' => 'success',
            'message' => $message
        ];
    }
}
