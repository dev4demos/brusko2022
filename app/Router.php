<?php

declare (strict_types = 1);

namespace App;

class Router
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $defaultPath = '/dashboard';

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $parsedUri = [];

    /**
     * @var array
     */
    protected $methods = [
        'GET' => 'GET',
        'HEAD' => 'GET',
        'POST' => 'POST',
        'PUT' => 'PUT'
    ];

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var mixed
     */
    protected $dbm;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param $method
     * @param array $args
     */
    public function __call($method, array $args = [])
    {
        return static::__callStatic($method, $args);
    }

    /**
     * @param $method
     * @param array $args
     */
    public static function __callStatic($method, array $args = [])
    {
        return call_user_func_array([Helper::class, $method], $args);
    }

    /**
     * @param $uri
     * @param $rootDir
     */
    public function __construct($uri, $rootDir = null)
    {
        $this->request(new Request($this));

        $this->setUri((string) $uri)
            ->setRootDir((string) $rootDir);
    }

    public static function make()
    {
        return new static(...func_get_args());
    }

    /**
     * @return mixed
     */
    public function sessionPath()
    {
        return $this->getRootDir() . '/storage/sessions';
    }

    /**
     * @return mixed
     */
    public function listSessionFiles()
    {
        return $this->getFiles($this->sessionPath());
    }

    /**
     * @return array
     */
    public function createSessionFiles(int $number, $sessionId = null)
    {
        $items = $this->randomFileNames($number, $sessionId ?: session_id());

        $sessionDir = $this->sessionPath();

        is_dir($sessionDir) ?: @mkdir($sessionDir, 0755, true);

        foreach ($items as $key => $name) {
            $items[$key] = $sessionDir . '/' . $name;
            // create empty file
            file_put_contents($items[$key], '');
        }

        return $items;
    }

    /**
     * @return mixed
     */
    public function downloader(array $data, string $type = 'csv')
    {
        $type = strtolower($type);

        return new Downloader($data);
    }

    /**
     * @return mixed
     */
    public function run()
    {
        echo (string) $this->execute();

        exit(1);
    }

    public function execute()
    {
        $arguments = [$this];

        $handler = $this->currentHandler();

        if (!$handler && $this->isRootUri()) {
            $handler = $this->defaultHandler();
        }

        if (!$handler) {
            $this->abort();
        }
        //
        $callback = $handler['callback'];

        if (is_array($callback) && count($callback) > 2) {
            $arguments = array_merge($arguments, array_slice($callback, 2));
            $callback = [array_shift($callback), array_shift($callback)];
        }

        return call_user_func_array($callback, $arguments);
    }

    /**
     * @param int $statusCode
     * @param $view
     */
    public function abort(int $statusCode = 404, $view = '404.php')
    {
        http_response_code($statusCode);

        $this->loadView($view);

        die(1);
    }

    /**
     * @return bool
     */
    public function isRootUri()
    {
        return $this->getUri() === '/';
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return self
     */
    public function setUri(string $uri)
    {
        $this->uri = urldecode($uri);

        return $this->setParsedUri(parse_url($this->getUri()));
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param string $rootDir
     *
     * @return self
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = realpath($rootDir);

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @param string $baseDir
     *
     * @return self
     */
    public function setBaseDir(string $baseDir)
    {
        $this->baseDir = realpath($baseDir);

        return $this;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param $name
     */
    public function hasMethod($name)
    {
        return array_key_exists($name, $this->getMethods());
    }

    /**
     * @param $callback
     * @param string $path
     * @param string $method
     * @return mixed
     */
    public function addHandler(string $path, callable $callback, string $method = 'GET', array $options = [])
    {
        $path = '/' . ltrim($path, '/');

        if (is_array($callback)) {
            $callback[] = new $callback[0];
            // $this->dd(get_defined_vars());
        }

        $this->handlers[] = get_defined_vars();

        return $this;
    }

    /**
     * @param string $path
     * @param string $method
     * @return mixed
     */
    public function getHandler(string $path, string $method)
    {
        $methods = $this->getMethods();

        foreach ($this->getHandlers() as $pieces) {
            if ($pieces['path'] != $path || !$this->hasMethod($method) || ($pieces['method'] != $methods[$method])) {
                continue;
            }

            return $pieces;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function defaultHandler()
    {
        return $this->getHandler($this->getDefaultPath(), $this->requestMethod());
    }

    /**
     * @return mixed
     */
    public function currentHandler()
    {
        return $this->getHandler($this->parsedUri('path'), $this->requestMethod());
    }

    /**
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @return string
     */
    public function getDefaultPath()
    {
        return $this->defaultPath ?: '/';
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function setDefaultPath(string $path)
    {
        $this->defaultPath = $path;

        return $this;
    }

    /**
     * @param array $handlers
     *
     * @return self
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;

        return $this;
    }

    /**
     * @return array
     */
    public function getParsedUri()
    {
        return $this->parsedUri;
    }

    /**
     * @param array $pieces
     *
     * @return self
     */
    protected function setParsedUri(array $pieces)
    {
        $this->parsedUri = $pieces;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function parsedUri(string $key)
    {
        if (array_key_exists($key, $pieces = $this->getParsedUri())) {
            return $pieces[$key];
        }
    }

    /**
     * @return string
     */
    public function requestMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @param $app
     */
    public function viewPath(string $method, bool $lowerCase = true)
    {
        $path = str_ireplace($this->getMethods(), '', $method) . '.php';

        return $lowerCase == true ? strtolower($path) : $path;
    }

    /**
     * @param string $name
     * @param array $data
     *
     * @return void
     */
    public function loadView(string $name, array $data = [])
    {
        $path = implode(DIRECTORY_SEPARATOR, [$this->getRootDir(), 'views', $name]);

        return call_user_func(function ($path, $data) {
            extract($data);

            if (file_exists($path)) {
                require $path;
            }

        }, $path, array_merge(['app' => $this], $data));
    }

    /**
     * @return mixed
     */
    public function dbm($dbm = null)
    {
        $dbm === null ?: $this->dbm = $dbm;

        return $this->dbm;
    }

    /**
     * @param Request $request
     *
     * @return self
     */
    public function request(Request $request = null)
    {
        $request === null ?: $this->request = $request;

        return $this->request;
    }
}
