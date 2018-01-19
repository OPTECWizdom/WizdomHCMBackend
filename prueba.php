<?php
define('RETURN_TRANSFER',1);
define('TIMEOUT',15);

	function requestToken($username,$password){
        $token = new stdClass();
        $token->grant_type = 'password';
        $token->username = $username;
        $token->password = $password;
        $clientTokens = getClientTokens();
        $token->client_id = $clientTokens[0];
        $token->client_secret = $clientTokens[1];
        $tokenJSON = json_encode($token);
        makeRequest('/oauth2/token','POST',$tokenJSON,false);

    }

    function getClientTokens()
    {

        if(empty([gs_client_id]) || empty([gs_client_secret]))
        {
            sc_include_library("prj","config","conf.php",true,true);
            [gs_client_id] = cf_get_conf(["oathClientId"]);
            [gs_client_secret] = cf_get_conf(["oauthPassword"]);

        }
        $clientId = [gs_client_id];
        $clientSecret = [gs_client_secret];
        return [$clientId,$clientSecret];
    }

    function getServerUrl()
    {
        if(empty([gs_server_url]))
        {
            sc_include_library("prj","config","conf.php",true,true);
            [gs_server_url] = cf_get_conf(["server"]);
        }
        return [gs_server_url];
    }

	function refreshToken(){


    }

    function getToken()
    {
        if

    }



	function makeRequest($url,$type,$params=null,$requiresAuthorization){
        $ch = curl_init();
        $headerArray = ['Content-Type: application/json'];
        curl_setopt($ch, CURLOPT_URL, getServerUrl()."/$url");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $ch = $params==null?$ch:makeRequestWithData($ch,$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, RETURN_TRANSFER);
        curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);
        $headerArray = $requiresAuthorization?makeRequestWithAuthorization($headerArray):$headerArray;
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headerArray);
        return $ch;

    }

    function makeRequestWithAuthorization($headerArray){
        $headerArray[] =  "Authorization: Bearer ".getToken();
        return $headerArray;
    }

	function makeAPIRequest(){

    }

	function makeMultipleAPIRequest(){

    }



    function makeRequestWithData($ch,$param)
    {
        curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
        return $ch;
    }


?>