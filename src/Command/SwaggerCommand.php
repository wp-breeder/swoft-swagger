<?php declare(strict_types=1);

namespace WpBreeder\Swagger\Command;

use InvalidArgumentException;
use RuntimeException;
use Swoft;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Helper\Interact;
use Swoft\Console\Input\Input;
use Swoft\Stdlib\Helper\DirHelper;
use Swoft\Stdlib\Helper\Sys;

/**
 * swagger command for [<cyan>swoft-swagger</cyan>] component
 *
 * @Command(coroutine=false)
 * @since 2.0
 */
class SwaggerCommand
{

    private static $version = '2.0.0';

    /**
     * @return array
     */
    public static function internalConfig(): array
    {
        return [
            'swoft/swagger' => [
                '@swagger/swagger',
                '@base/public/'
            ],
        ];
    }

    /**
     * Used to publish the internal resources of the module to the 'public' directory
     *
     * @CommandMapping()
     *
     * @CommandArgument(
     *     name="srcDir", desc="The source assets directory path. eg. `@vendor/some/lib/assets`",
     *     type="string"
     * )
     * @CommandArgument(
     *     name="dstDir", desc="The dist directory component name.(default is `@root/public/some/lib`)",
     *     type="string"
     * )
     *
     * @CommandOption("yes", short="y",
     *     desc="Do not confirm when execute publish. default is: <info>False</info>", type="bool",
     *     default="false"
     * )
     * @CommandOption("force", short="f", desc="Force override all exists file.(default: <info>False</info>)",
     *     type="bool", default="false"
     * )
     *
     * @example
     *   {fullCommand} swoft/swagger
     *
     * @param Input $input
     * @return int
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function publish(Input $input): int
    {
        $assetDir = $input->getArg(0);
        var_dump($assetDir);
        $targetDir = $input->getArg(1);
        var_dump($targetDir);
        if (!$assetDir && !$targetDir) {
            \output()->colored('arguments is required!', 'warning');
            return -1;
        }
        // first arg is internal component name
        if ($assetDir && !$targetDir) {
            $config = static::internalConfig();
            if (!isset($config[$assetDir])) {
                \output()->colored('missing arguments!', 'warning');
            }
            list($assetDir, $targetDir) = $config[$assetDir];
        }
        $assetDir = Swoft::getAlias($assetDir);
        var_dump($assetDir);
        $targetDir = Swoft::getAlias($targetDir);
        var_dump($targetDir);
        $force = \input()->sameOpt(['f', 'force'], false);
        if ($force && \is_dir($targetDir)) {
            \output()->writeln("Will delete the old assets: $targetDir");
            list($code, , $error) = Sys::run("rm -rf $targetDir");
            if ($code !== 0) {
                \output()->colored("Delete dir $targetDir is failed!", 'error');
                \output()->writeln($error);
                return -2;
            }
        }
        $yes = \input()->sameOpt(['y', 'yes'], false);
        $command = "cp -Rf $assetDir $targetDir";
        \output()->writeln("Will run shell command:\n $command");
        if (!$yes && !Interact::confirm('Ensure continue?', true)) {
            \output()->writeln(' Quit, Bye!');
            return 0;
        }
        DirHelper::make($targetDir);
        list($code, , $error) = Sys::run($command, Swoft::getAlias('@base'));
        if ($code !== 0) {
            \output()->colored("Publish assets to $targetDir is failed!", 'error');
            \output()->writeln($error);
            return -2;
        }
        \output()->colored("\nPublish assets to $targetDir is OK!", 'success');
        return 0;
    }

    /**
     * Version number
     *
     * @CommandMapping()
     *
     * @example
     *   {fullCommand} version
     */
    public function version()
    {
        \output()->writeln('swoft-swagger: <info>' . self::$version . '</info>');
    }
}