<?php
namespace Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;
use \Controllers\ControllerInterface;
use \Bots\BotFactory;
use \Services\RequestFactory;

abstract class AbstractControllerInterface implements ControllerInterface
{
    abstract public function __construct(array $botConfigs);
    /**
     * Método que está sendo executado pelo client
     * @return string $_SERVER['REQUEST_METHOD']
     */
    public function method()
    {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }
    /**
     * URI do cliente
     * @return string $_SERVER['REQUEST_URI']
     */
    public function uri()
    {
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $uri = ($uri === '/') ? '/index' : $uri;
        return $uri;
    }
    /**
     * Recebe a requisição e trata
     * @return
     */
    public function handle()
    {
        if ($this->isWebhook()) {
            $method = ucfirst(strtolower($this->method()));
            $webhook  = "handleWebhook{$method}";
            $this->{$webhook}();
            return ;
        }

        if ($this->method() === 'GET') {
            $this->renderPage();
            return ;
        }
        // if ($this->method() === 'POST') {
        //     die('posteou');
        // }
    }
    /**
     * Verifica se a requisição é do tipo webhook
     * @return boolean é webhook?
     */
    public function isWebhook()
    {
        return preg_match('/^\/webhook/', $this->uri());
    }
    /**
     * Verifica se q requisição é uma página
     * @return boolean é uma página e é permitido o acesso?
     */
    public function isPage()
    {
        $uri = $this->uri();
        $pagesWhitelisted = in_array($uri, [
            '/termos', '/index'
        ]);
        return $pagesWhitelisted;
    }
    /**
     * Gera a página que está sendo solicitada pelo client
     * @return include
     */
    public function renderPage()
    {
        $uri = $this->uri();
        $layout = sprintf('%s/../../public/layout.php', __DIR__, $uri);

        if (!file_exists($layout)) {
            echo '<h1>Precisa criar o layout e as páginas.</h1>';
            return ;
        }

        if (!$this->isPage()) {
            $uri = 'notfound';
        }

        $GLOBAL_PAGE_VAR = ['page' => $uri];
        include $layout;
    }

    abstract public function dispatch();
}
