<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 08/11/2017
 * Time: 9:15
 */

namespace common\components\trntv\bus\middleware;

use trntv\bus\middlewares\BackgroundCommandMiddleware;
use trntv\bus\interfaces\BackgroundCommand;

class BackgroundCommandMiddlewareCustom extends BackgroundCommandMiddleware
{

    /**
     * @param BackgroundCommand $command
     * @return string
     */
    protected function runProcess(BackgroundCommand $command)
    {
        $binary = $this->getBackgroundHandlerBinary();
        $path = $this->getBackgroundHandlerPath();
        $route = $this->getBackgroundHandlerRoute();
        $arguments = implode(' ', $this->getBackgroundHandlerArguments($command));
        $binaryArguments = implode(' ', $this->backgroundHandlerBinaryArguments);
        if ($command->isAsync()) {
            try {
                exec("{$binary} {$binaryArguments} {$path} {$route} {$arguments} > nul &"); // no $output

            }
            catch (\Exception $e)
            {
                throw $e;
            }
        }
        else {
            exec("{$binary} {$binaryArguments} {$path} {$route} {$arguments}"); // no $output

        }

        return 1;
    }

    /**
     * @param $command
     * @return array
     */
    private function getBackgroundHandlerArguments($command)
    {
        $arguments = $this->backgroundHandlerArguments;
        $command = base64_encode(serialize($command));
        array_unshift($arguments, $command);
        return $arguments;
    }

}