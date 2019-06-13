<?php declare(strict_types=1);

namespace WpBreeder\Swagger\Http\Controller;

use ReflectionException;
use Swoft;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Exception\HttpServerException;
use function alias;
use function current;
use function is_dir;
use function json_decode;
use function OpenApi\scan;

/**
 * Class SwaggerController
 *
 * @Controller(prefix="/swagger/")
 *
 * @since 2.0
 */
class SwaggerController
{
    /**
     * generator openapi json
     * @RequestMapping(route="api-json", method=RequestMethod::GET)
     * @return array
     * @throws HttpServerException
     */
    public function genDocJson(): array
    {
        $isEnable = (int)\env('AUTO_SWAGGER', 1);
        if ((int)$isEnable === 1) {
            $projectPath = Swoft::getAlias('@base');
            $openapi = scan($projectPath, ['exclude' =>
                [
                    $projectPath . "/vendor",
                    $projectPath . "/test",
                    $projectPath . "/tests",
                ]
            ]);
            return json_decode($openapi->toJson(), true);
        } else {
            throw new HttpServerException("Please open the generated document");
        }
    }

    /**
     * show swagger view
     * @RequestMapping(route="docs", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     * @throws HttpServerException
     */
    public function showDoc(Request $request, Response $response): Response
    {
        $isEnable = (int)\env('AUTO_SWAGGER', 1);
        if (is_dir(alias('@base/public/swagger')) && (int)$isEnable === 1) {
            //return html
            $content = self::getContent('@base/public/swagger/index.html');
            $response = self::setMimeType($content, $request, $response);
            return $response;
        } else {
            throw new HttpServerException("Please publish static resources first");
        }
    }

    /**
     * return swagger swagger-ui.css
     * @RequestMapping(route="swagger-ui.css", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function swaggerCss(Request $request, Response $response): Response
    {
        $content = self::getContent('@base/public/swagger/swagger-ui.css');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger swagger-ui-bundle.js
     * @RequestMapping(route="swagger-ui-bundle.js", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function swaggerBundle(Request $request, Response $response): Response
    {
        $content = self::getContent('@base/public/swagger/swagger-ui-bundle.js');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger swagger-ui-standalone-preset.js
     * @RequestMapping(route="swagger-ui-standalone-preset.js", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function swaggerStandalonePreset(Request $request, Response $response): Response
    {
        $content = self::getContent('@base/public/swagger/swagger-ui-standalone-preset.js');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger favicon-32x32.png
     * @RequestMapping(route="favicon-32x32.png", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function swaggerIco32(Request $request, Response $response): Response
    {
        $content = self::getContent('@base/public/swagger/favicon-32x32.png');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * return swagger favicon-16x16.png
     * @RequestMapping(route="favicon-16x16.png", method=RequestMethod::GET)
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function swaggerIco16(Request $request, Response $response): Response
    {
        $content = self::getContent('@base/public/swagger/favicon-16x16.png');
        $response = self::setMimeType($content, $request, $response);
        return $response;
    }

    /**
     * set mime type
     * @param $content
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
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
        return file_get_contents(alias($path));
    }
}