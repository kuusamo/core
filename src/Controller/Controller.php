<?php

namespace Kuusamo\Vle\Controller;

use Kuusamo\Vle\Helper\Environment;

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
            'siteName' => Environment::get('SITE_NAME')
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
     * @param string $message Message.
     * @return void
     */
    protected function alertSuccess($message)
    {
        $this->alerts[] = [
            'type' => 'success',
            'message' => $message
        ];
    }
}
