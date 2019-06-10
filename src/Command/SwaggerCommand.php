<?php

namespace Swoft\Swagger\Command;

use OpenApi\Annotations\Options;
use Swoft\App;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Helper\ConsoleUtil;
use Swoft\Console\Input\Input;
use Swoft\Helper\DirHelper;
use Swoft\Helper\ProcessHelper;

/**
 * swagger command for [<cyan>swoft-swagger</cyan>] component
 * @Command()
 */
class SwaggerCommand
{

    private static $version = '1.0.0';
    /**
     * @return array
     */
    public static function internalConfig(): array
    {
        return [
            'swoft/swagger' => [
                '@swagger/swagger',
                '@root/public/'
            ],
        ];
    }

    /**
     * Used to publish the internal resources of the module to the 'public' directory
     * @Arguments
     *   srcDir   The source assets directory path. eg. `@vendor/some/lib/assets`
     *   dstDir   The dist directory component name.(default is `@root/public/some/lib`)
     * @Options
     *   -y, --yes BOOL      Do not confirm when execute publish. default is: <info>False</info>
     *   -f, --force BOOL    Force override all exists file.(default: <info>False</info>)
     * @Example
     *   {fullCommand} swoft/swagger
     * @param Input $input
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @author inhere <in.798@qq.com>
     */
    public function publish(Input $input): int
    {
        $assetDir = $input->getArg(0);
        $targetDir = $input->getArg(1);
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
        $assetDir = App::getAlias($assetDir);
        $targetDir = App::getAlias($targetDir);
        $force = \input()->sameOpt(['f', 'force'], false);
        if ($force && \is_dir($targetDir)) {
            \output()->writeln("Will delete the old assets: $targetDir");
            list($code, , $error) = ProcessHelper::run("rm -rf $targetDir");
            if ($code !== 0) {
                \output()->colored("Delete dir $targetDir is failed!", 'error');
                \output()->writeln($error);
                return -2;
            }
        }
        $yes = \input()->sameOpt(['y', 'yes'], false);
        $command = "cp -Rf $assetDir $targetDir";
        \output()->writeln("Will run shell command:\n $command");
        if (!$yes && !ConsoleUtil::confirm('Ensure continue?', true)) {
            \output()->writeln(' Quit, Bye!');
            return 0;
        }
        DirHelper::mkdir($targetDir);
        list($code, , $error) = ProcessHelper::run($command, App::getAlias('@root'));
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
     * @Example
     *   {fullCommand} version
     */
    public function version()
    {
        \output()->writeln('sowft-swagger: <info>'. self::$version . '</info>');
    }
}