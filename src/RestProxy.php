<?php
namespace AppManager\RestProxy;

use Symfony\Component\HttpFoundation\Request;

class RestProxy
{
    private $request;
    private $curl;
    private $map;

    private $content;
    private $headers;

    const GET = "GET";
    const POST = "POST";
    const DELETE = "DELETE";
    const PUT = "PUT";
    const OPTIONS = "OPTIONS";

    private $actions = [
        self::GET     => 'doGet',
        self::POST    => 'doPost',
        self::DELETE  => 'doDelete',
        self::PUT     => 'doPut',
        self::OPTIONS => 'doOptions',
    ];

    public function __construct(Request $request, CurlWrapper $curl)
    {
        $this->request = $request;
        $this->curl    = $curl;
    }

    public function register($name, $url)
    {
        $this->map[$name] = $url;
    }

    public function run()
    {
        $url = $this->request->getPathInfo();

        foreach ($this->map as $name => $mapUrl) {
            if (strpos($url, $name) == 1 || $name == "/") {
                return $this->dispatch($mapUrl . str_replace("/{$name}", NULL, $url));
            }
        }

        throw new \Exception("Not match");
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getContent()
    {
        return $this->content;
    }

    private function dispatch($url)
    {
        $queryString = $this->request->getQueryString();
        $action      = $this->getActionName($this->request->getMethod());

        // Parse JSON input data
        $data = NULL;
        if (0 === strpos($this->request->headers->get('Content-Type'), 'application/json')) {
            $data = $this->request->getContent();
        }

        $this->content = $this->curl->$action($url, $queryString, $data);
        $this->headers = $this->curl->getHeaders();
    }

    private function getActionName($requestMethod)
    {
        if (!array_key_exists($requestMethod, $this->actions)) throw \Exception("Method not allowed");

        return $this->actions[$requestMethod];
    }
}
