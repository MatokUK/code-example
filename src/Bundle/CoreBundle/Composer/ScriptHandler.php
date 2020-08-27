<?php

namespace Matok\Bundle\CoreBundle\Composer;

use Composer\Script\Event;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as SensioScriptHandler;

/**
 * Description of ScriptHandler
 *
 * @author matejkuna
 */
class ScriptHandler extends SensioScriptHandler
{
    /**
     * Execute command during composer processing
     * @param Event $event
     * @return type
     */
    public static function generatePublicPhpFiles(Event $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'generate public php files');

        if (null === $consoleDir) {
            return;
        }

        static::executeCommand($event, $consoleDir, 'leadinger:generate:index', $options['process-timeout']);
    }
}
