<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/10/2017
 * Time: 12:10
 */

namespace backend\wizdomWebServices;
use mongosoft\soapclient\Client;

class AbstractWizdomWebService extends Client
{

    /**
     * @var string $serverUrl
     */
    public $serverUrl;

    /**
     * @var string  $path
     */
    protected $path;

    /**
     * @var string $method
     */
    protected $method;

    /**
     *
     * @var string $methodParmsPath;
     */
    protected $methodParmsPath;

    public function __construct(array $config = [])
    {
        $this->serverUrl = \Yii::$app->params['wizdomWebService'];
        $this->url = $this->serverUrl."/".$this->path;
        parent::__construct($config);
    }


    /**
     * @return string
     */
    public function getServerUrl(): string
    {
        return $this->serverUrl;
    }

    /**
     * @param string $serverUrl
     */
    public function setServerUrl(string $serverUrl)
    {
        $this->serverUrl = $serverUrl;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getMethodParmsPath()
    {
        return $this->methodParmsPath;
    }

    /**
     * @param mixed $methodParmsPath
     */
    public function setMethodParmsPath($methodParmsPath)
    {
        $this->methodParmsPath = $methodParmsPath;
    }




}