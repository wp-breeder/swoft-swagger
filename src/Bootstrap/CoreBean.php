<?php
/**
 * Created by PhpStorm.
 * User: WP
 * Date: 2018/11/9
 * Time: 17:52
 */

namespace Swoft\Swagger\Bootstrap;

use Doctrine\Common\Annotations\AnnotationReader;
use Swoft\App;
use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;

/**
 * Class CoreBean
 * @BootBean()
 */
class CoreBean implements BootBeanInterface
{
    /**
     * CoreBean constructor.
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        // disable "OA" annotation
        AnnotationReader::addGlobalIgnoredNamespace('OA');
        App::setAlias('@swagger', \dirname(__DIR__, 2));
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [];
    }
}