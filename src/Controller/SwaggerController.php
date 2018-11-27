<?php
/**
 * Created by PhpStorm.
 * User: WP
 * Date: 2018/11/21
 * Time: 10:35
 */

namespace Swoft\Swagger\Controller;

use Swoft\App;
use Swoft\Exception\RuntimeException;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Class SwaggerController
 * @package Swoft\Swagger\Controller
 * @Controller(prefix="/swagger/")
 */
class SwaggerController
{
    /**
     * generator openapi json
     * @RequestMapping(route="api-json", method=RequestMethod::GET)
     * @return array
     * @throws RuntimeException
     */
    public function genDocJson(): array
    {
        $isEnable = App::getAppProperties()->get('server.server.autoSwagger', true);
        if ((int)$isEnable === 1) {
            $projectPath = App::getAlias('@root');
            $openapi = \OpenApi\scan($projectPath, ['exclude' =>
                [
                    $projectPath . "/vendor",
                    $projectPath . "/test",
                    $projectPath . "/tests",
                ]
            ]);
            return \json_decode($openapi->toJson(), true);
        } else {
            throw new RuntimeException("Please open the generated document");
        }
    }

    /**
     * show swagger view
     * @RequestMapping(route="docs", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function showDoc(Request $request, Response $response): Response
    {
        $isEnable = App::getAppProperties()->get('server.server.autoSwagger', true);
        if (\is_dir(\alias('@root/public/swagger')) && (int)$isEnable === 1) {
            //return html
            $content = self::getContent('@root/public/swagger/index.html');
            $response = self::setMimeType($content, $request, $response);
            return $response;
        } else {
            throw new RuntimeException("Please publish static resources first");
        }
    }

    /**
     * return swagger swagger-ui.css
     * @RequestMapping(route="swagger-ui.css", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function swaggerCss(Request $request, Response $response): Response
    {
        $content = self::getContent('@root/public/swagger/swagger-ui.css');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger swagger-ui-bundle.js
     * @RequestMapping(route="swagger-ui-bundle.js", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function swaggerBundle(Request $request, Response $response): Response
    {
        $content = self::getContent('@root/public/swagger/swagger-ui-bundle.js');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger swagger-ui-standalone-preset.js
     * @RequestMapping(route="swagger-ui-standalone-preset.js", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function swaggerStandalonePreset(Request $request, Response $response): Response
    {
        $content = self::getContent('@root/public/swagger/swagger-ui-standalone-preset.js');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger favicon-32x32.png
     * @RequestMapping(route="favicon-32x32.png", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function swaggerIco32(Request $request, Response $response): Response
    {
        $content = self::getContent('@root/public/swagger/favicon-32x32.png');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger favicon-16x16.png
     * @RequestMapping(route="favicon-16x16.png", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function swaggerIco16(Request $request, Response $response): Response
    {
        $content = self::getContent('@root/public/swagger/favicon-16x16.png');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * set mime type
     * @param $content
     * @param Response $response
     * @param Request $request
     * @return Response
     */
    private static function setMimeType($content, Request $request, Response $response): Response
    {
        // accept and the of response
        $accepts = $request->getHeader('accept');
        $currentAccept = current($accepts);
        /** @var Response $response */
        $response = $response->withContent($content);
        $response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', $currentAccept);

        return $response;
    }

    /**
     * get resource content
     * @param $path
     * @return mixed
     */
    private static function getContent($path)
    {
        return file_get_contents(\alias($path));
    }
}