<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 10:32
 */

namespace backend\workflowManagers;


abstract class AbstractManager
{

    const REGISTER = 'register';

    const UPDATE = 'update';

    const DELETE = 'delete';


    protected function __construct($config=[]){

    }

    protected abstract function insert();

    protected abstract function update();

    protected abstract function delete();

    public abstract function run();




}