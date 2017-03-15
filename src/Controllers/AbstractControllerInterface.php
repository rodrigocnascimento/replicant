<?php
namespace Controllers;

use \Controllers\ControllerInterface;
use \Bots\BotFactory;
use \Services\RequestFactory;
use \Factory\AbstractRequestFactory;

abstract class AbstractControllerInterface implements ControllerInterface
{
    protected $payloaderInput;
    abstract public function __construct();
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
        $uri = str_replace('.php', '', filter_input(INPUT_SERVER, 'REQUEST_URI'));
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
            $method = strtolower($this->method());
            $this->{$method}();
            return ;
        }

        if ($this->method() === 'GET') {
            $this->renderPage();
            return ;
        }

        // if ($this->method() === 'POST') {
        //     header("HTTP/1.1 401 Unauthorized");
        //     return ;
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
        
        $whiteList = array_map(function($pagePath) {
            $pagePath = explode('/', $pagePath);
            return '/' . str_replace('.php', '', $pagePath[count($pagePath) - 1]);
        }, glob(sprintf('%s/*.php', APP_PUBLIC)));
        
        return in_array($uri, $whiteList);
    }
    /**
     * Gera a página que está sendo solicitada pelo client
     * @return include
     */
    public function renderPage()
    {
        $uri = $this->uri();
        $layout = sprintf('%s/layout.php', APP_PUBLIC);
        
        if (!$this->isPage()) {
            $uri = 'notfound';
        }

        $GLOBAL_PAGE_VAR = ['page' => $uri];
        if (!file_exists($layout)) {
            throw new \Exception("Precisa criar o layout e as páginas");
        }

        include $layout;
    }
    /**
     * [getArrayDataPayload description]
     * @return [type] [description]
     */
    public function getArrayDataPayload()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    /**
     * [getRequesterPayload description]
     * @return [type] [description]
     */
    public function getRequesterPayload()
    {
        preg_match('/\/facebook/', $this->uri(), $matches);
        if (empty($matches)) {
            header("HTTP/1.1 400 Bad Request");
            die;
        }
        
        if ($matches[0] === '/facebook') {
            $payloader = ['name' => 'Facebook', 'className' => '\\Services\\FacebookRequest'];
        }
        
        return $payloader;
    }
}
