<?php declare(strict_types=1);

namespace WpBreeder\Swagger;

use Doctrine\Common\Annotations\AnnotationReader;
use Swoft;
use Swoft\Helper\ComposerJSON;
use Swoft\SwoftComponent;
use function dirname;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // disable "OA" annotation
        AnnotationReader::addGlobalIgnoredNamespace('OA');
        Swoft::setAlias('@swagger', dirname(__DIR__));
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';
        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return bool
     */
    public function enable(): bool
    {
        return (int)\env("AUTO_SWAGGER", 0) > 0;
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [];
    }
}