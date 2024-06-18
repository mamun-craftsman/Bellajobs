<?php
namespace Framework;
use Framework\Middleware\Authentication;
class Router {
	
	private static $instanceCount = 0;
	public function __construct() {
        self::$instanceCount++;
    }
	public static function getInstanceCount() {
        return self::$instanceCount;
    }



	private $routes=[];
	/**
	 * create new router
	 *
	 * @param string $method
	 * @param string $uri
	 * @param string $controller
	 * @return void
	 */
    private function registerRoutes($method, $uri, $action, $middleware=[]) {
		list($controller,$controllerMethod) = explode('@', $action);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
			'controllerMethod'=>$controllerMethod,
			'middleware'=>$middleware
        ];
    }

	public function get($uri, $controller, $middleware=[]) {
		$this->registerRoutes('GET', $uri, $controller, $middleware);
	}
	public function post($uri, $controller, $middleware=[]) {
		$this->registerRoutes('POST', $uri, $controller,$middleware);
	}
	public function put($uri, $controller,$middleware=[]) {
		$this->registerRoutes('PUT', $uri, $controller,$middleware);
	}
	public function delete($uri, $controller,$middleware=[]) {
		$this->registerRoutes('DELETE', $uri, $controller, $middleware);
	}

	public function route($uri) {
		$RequestMethod = $_SERVER['REQUEST_METHOD'];
		if($RequestMethod==='POST' && isset($_POST['_method'])) {
			$RequestMethod = $_POST['_method'];
		}
		$uriSeg = explode('/', trim($uri,'/'));
		foreach($this->routes as $route) {
			$routeSeg = explode('/', trim($route['uri'],'/'));
			
			if(count($uriSeg) == count($routeSeg) && $route['method'] === $RequestMethod)  {
				$match = true;
				$params = [];
				for($i = 0; $i < count($routeSeg); $i++) {
					
					if ($routeSeg[$i] !== $uriSeg[$i] && !preg_match('/\{(.+?)\}/', $routeSeg[$i])) {
						$match = false;
						break;
					} elseif (preg_match('/\{(.+?)\}/', $routeSeg[$i], $matches)) {
						$params[$matches[1]] = $uriSeg[$i];
					}
				}
		
				if ($match) {
					foreach($route['middleware'] as $role) {
						(new Authentication())->handle($role);
					}
					$controllerClassName = 'App\\controllers\\' . $route['controller'];
					$controller = new $controllerClassName();
					$controllerMethod = $route['controllerMethod'];
					$controller->$controllerMethod($params);
					return;
				}
			}
		}
		$errorClassName = 'App\\controllers\\'."ErrorControllers";
		$errorClassName::notFound();
	
		// If no route matched, handle this case (e.g., show a 404 page)
	}
}
?>
