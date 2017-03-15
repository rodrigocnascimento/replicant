<?php
namespace Services;

use \Factory\AbstractRequestFactory;

class RequestFactory extends AbstractRequestFactory
{
    /**
    * Invoca a Classe de request da plataforma apropriada
    * @param  array  $requestPayloader Info da plataforma que realiza a
    * requisição
    * @param  array  $request          Raw http request
    * @return AbstractRequest          Class abstraída
    */
    public static function build(array $requestPayloader, array $request)
    {
        $payloaderClass = $requestPayloader['service'];

        if(class_exists($payloaderClass)) {
            return new $payloaderClass($request);
        }
        throw new \Exception("Invalid class given.");
    }
}
