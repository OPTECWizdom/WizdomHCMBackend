<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/11/2017
 * Time: 15:18
 */

namespace backend\commands;


interface IBackendBackgroundProcess
{

    public function runJob();

}