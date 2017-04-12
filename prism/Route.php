<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/4/3
 * Time: 下午4:40
 * Desc: 路由解析
 */

namespace prism;


use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\core\exception\ErrorException;

class Route {
    public    $route = [];
    protected $request;
    //默认绑定的路由
    protected $default;
    private   $inputs = [];

    public function __construct(Request $request) {
        $this->request = $request;
        $this->inputs = $request->getInput();
    }

    /**
     * @throws ErrorException
     *
     * 路由解析
     */
    public function parse() {
        if (!is_null($this->request)) {
            // 无路由情况下是用默认绑定路由
            if (empty($this->request->getUri()) || trim($this->request->getUri(), '/') == 'index.php') {
                $this->route = $this->default;
            } else {
                $uri  = $this->request->getUri();
                $uris = explode('/', substr($uri, 1, strlen($uri) - 1));
                if (!empty($uris)) {
                    $this->route['app']        = $uris[0];
                    $this->route['controller'] = $uris[1];
                    $this->route['action']     = $uris[2];
                } else {
                    Response::sendError(PrismCode::ERR_REQUEST_ROUTE, PRISM_MSG[PrismCode::ERR_REQUEST_ROUTE]);
                }
            }
        } else {
            Response::sendError(PrismCode::ERR_REQUEST, PRISM_MSG[PrismCode::ERR_REQUEST]);
//            throw new ErrorException(PrismCode::ERR_REQUEST, PRISM_MSG[PrismCode::ERR_REQUEST], __FILE__, __LINE__);
        }
    }

    /**
     * @return Request
     */
    public function getRequest(): Request {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getRoute(): array {
        return $this->route;
    }

    /**
     * @param array $route
     */
    public function setRoute(array $route) {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * @param mixed $default
     */
    public function setDefault($default) {
        $this->default = $default;
    }

    /**
     * @return array|mixed
     */
    public function getInputs() {
        return $this->inputs;
    }

    /**
     * @param array|mixed $inputs
     */
    public function setInputs($inputs) {
        $this->inputs = $inputs;
    }

}