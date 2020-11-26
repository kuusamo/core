<?php

namespace Kuusamo\Vle\Service\Templating;

use Kuusamo\Vle\Entity\Theme\Theme;
use Kuusamo\Vle\Service\Meta;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Psr\Http\Message\ResponseInterface as Response;

class Templating
{
    private $engine;
    private $theme;
    private $data = [];

    public function __construct(Theme $theme = null)
    {
        $this->initialise(__DIR__ . '/../../../templates');
        $this->theme = $theme;
    }

    /**
     * Initialise the engine here. Doing it here, rather than the
     * constructor, allows us to override the directory easily
     * in the Clinic Control codebase.
     *
     * @param $string $templatePath Path to template files.
     * @return void
     */
    protected function initialise(string $templatePath)
    {
        $this->engine = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader(
                $templatePath,
                [
                    'extension' => ''
                ]
            )
        ]);
    }

    /**
     * Just return the rendered content. Useful for inline renders.
     *
     * @param string $template Filename.
     * @param array  $data     Mustache data.
     * @return string
     */
    public function renderTemplate($template, $data = []): string
    {
        return $this->engine->render($template, $data);
    }

    /**
     * Renders to a response object.
     *
     * @param Response $response PSR-7 response.
     * @param string   $template Filename.
     * @param array    $data     Mustache data.
     * @return Response
     */
    public function render(Response $response, string $template, array $data = []): Response
    {
        $content = $this->engine->render($template, array_merge($this->data, $data));
        $response->getBody()->write($content);
        return $response;
    }

    /**
     * Renders a full page including the layout.
     *
     * @param Response $response PSR-7 response.
     * @param string   $template Filename.
     * @param array    $data     Mustache data.
     * @param Meta     $meta     Metadata.
     */
    public function renderPage(Response $response, string $template, array $data = [], Meta $meta = null): Response
    {
        $content = $this->engine->render($template, array_merge($this->data, $data));

        $layoutData = [
            'body' => $content,
            'theme' => $this->theme
        ];

        if ($meta) {
            $layoutData['meta'] = $meta;
        }

        $layout = $this->engine->render(
            'layout.html',
            array_merge($this->data, $data, $layoutData)
        );

        $response->getBody()->write($layout);
        return $response;
    }

    /**
     * Set some data in advance.
     *
     * @param string $key  Key.
     * @param mixed  $valu Value.
     * @return void
     */
    public function setData(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Add a helper to the Mustache engine.
     *
     * @param string $name   Name.
     * @param mixed  $helper Closure or array of closures.
     */
    public function addHelper(string $name, $helper)
    {
        $this->engine->addHelper($name, $helper);
    }
}
