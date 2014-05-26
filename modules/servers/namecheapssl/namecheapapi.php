<?php

// ****************************************************************************
// *                                                                          *
// * NameCheap.com WHMCS domain register/SSL Module                           *
// * Version 1.5
// * Email: sslsupport@namecheap.com                                          *
// *                                                                          *
// * Copyright 2010-2013 NameCheap.com                                        *
// *                                                                          *
// ****************************************************************************


/**
 * NamecheapApi
 */
if (!class_exists("NamecheapApi")) {
class NamecheapApi
{
    public static $url = "https://api.namecheap.com/xml.response";
    public static $testUrl = "https://api.sandbox.namecheap.com/xml.response";

    private static $_phoneCountryCodes = array(
        1, 7, 20, 27, 30, 31, 32, 33, 34, 36, 39, 40, 41, 43, 44, 45, 46, 47, 48, 49, 51, 52, 54, 55, 56, 57, 58, 60,
        61, 62, 63, 64, 65, 66, 81, 82, 84, 86, 90, 91, 92, 93, 94, 95, 98, 212, 213, 216, 220, 221, 222, 224, 225, 226,
        227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 248, 249,
        250, 251, 252, 253, 254, 255, 256, 257, 258, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 290, 291, 297,
        298, 299, 340, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 370, 371, 372, 373, 374, 375, 376, 377, 378,
        380, 381, 382, 385, 386, 387, 389, 420, 421, 423, 500, 501, 502, 503, 504, 505, 506, 507, 508, 509, 590, 591,
        592, 593, 594, 595, 596, 597, 598, 599, 618, 670, 672, 673, 674, 675, 676, 677, 678, 679, 680, 681, 682, 683,
        684, 686, 687, 688, 689, 690, 691, 692, 850, 852, 853, 855, 856, 872, 880, 886, 960, 961, 962, 963, 965, 966,
        967, 968, 970, 971, 972, 973, 974, 975, 976, 977, 992, 993, 994, 995, 996, 998
    );
    private $_apiUser;
    private $_apiKey;

    private $_testMode = true;
    private $_debugMode = false;

    private $_requestUrl;
    private $_requestParams;
    private $_response;
    
    private $_lastLogRecordId=0;
    

    public function  __construct($apiUser, $apiKey, $testMode = true, $debugMode = false)
    {
        $this->_apiUser = $apiUser;
        $this->_apiKey  = $apiKey;

        $this->setTestMode($testMode);
        
        $this->_debugMode = (bool)$debugMode;
    }

    /**
     * parseResponse
     * @param string $response
     * @return array
     */
    public function parseResponse($response)
    {
        
        if (false === ($xml = simplexml_load_string($response))) {
            throw new NamecheapApiException("Unable to parse response");
        }
        $result = $this->_xml2Array($xml);
        
        if(empty($result['@attributes']['Status']) || !isset($result['Server'])){
            throw new NamecheapApiException("Response error. It is not look like normal api response. Please try again");
        }
        
        
        if ("ERROR" == $result['@attributes']['Status']) {
            $errors = isset($result['Errors']['Error'][0]) ? $result['Errors']['Error'] : array($result['Errors']['Error']);

            $err = $errors[count($errors) - 1];
            $err_msg = sprintf("[%s] %s", $err['@attributes']['Number'], $err['@value']) ;
            throw new NamecheapApiException($err_msg, $err['@attributes']['Number']);
        }
        
        
        
        return $result['CommandResponse'];
    }

    /**
     * request
     * @param string $command
     * @param array $params
     * @return string
     */
    public function request($command, array $params)
    {
        $result = false;        
        $curl_error = false;
        
        $this->_requestUrl = $this->_getApiUrl($command, $params);
        $this->_requestParams = $this->_getApiParams($command, $params);
        
        
        if ($this->_debugMode){
            $logRequestParams = $this->_requestParams;
            if(!empty($logRequestParams['ApiKey'])){
                $logRequestParams['ApiKey'] = '***';
            }
            $this->_lastLogRecordId = namecheapssl_save_debug_info("REQUEST:\n".date('Y-m-d H:i:s')."\ncommand:".$command."\nparams:"."\n" . var_export($logRequestParams,true)."\n",$command);
        }
        
        
        if (extension_loaded("curl") && ($ch = curl_init()) !== false)
        {
            curl_setopt($ch, CURLOPT_URL, $this->_requestUrl);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            
            // we set peer verification of namecheap server to false - else the process will fail
            // if the host server doesnt have an accurate ca bundle.
            // Can turn this on, when you place an up to date ca bundle at your host server.
            
            if ($command == 'namecheap.ssl.activate' || strtolower($command) == 'namecheap.ssl.parsecsr' || strtolower($command) == 'namecheap.ssl.reissue') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_requestParams);
            }
            
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $this->_response = curl_exec($ch);
            $curl_error = curl_error($ch);
            curl_close($ch);
            
            if($curl_error){
                $message = $curl_error . " Unable to request data from " . $this->_requestUrl;
                if($this->_debugMode){
                   namecheapssl_save_debug_info($message,$command,true,$this->_lastLogRecordId);
                }
                throw new NamecheapApiException($message);
            }
            
        }else{
            $message = "Unable to request data, curl extention is not loaded";
               if($this->_debugMode){
                   namecheapssl_save_debug_info($message,$command,true,$this->_lastLogRecordId);
               }
            throw new NamecheapApiException($message);
        }
        /*else{
            $this->_response = @file_get_contents($this->_requestUrl);
            if (!$this->_response) {
               $message = "Unable to request data from (file_get_contents used)" . $this->_requestUrl;
               if($this->_debugMode){
                   namecheapssl_save_debug_info($message,$command,true,$this->_lastLogRecordId);
               }
               throw new NamecheapApiException($message);
            }
        }*/

        
        if ($this->_debugMode){
            namecheapssl_save_debug_info("RESPONSE:\n".date('Y-m-d H:i:s')."\n".$this->_response."\n",$command,true,$this->_lastLogRecordId);
        }
        
        
        return $this->_response;
    }

    /**
     * setTestMode
     * @param boolean $flag
     */
    public function setTestMode($flag)
    {
        $this->_testMode = (bool)$flag;
    }

    // private methods

    /**
     * formatPhone
     * @param string $phone
     * @return string
     */
    private function _formatPhone($phone)
    {
        /**
         * Namecheap API phone format requirement is +NNN.NNNNNNNNNN
         */
        
        $arg = $phone;
        
        // strip all non-digit characters
        $phone = preg_replace('/[^\d]/', '', $phone);

        // check country code
        $phone_code = "";
        foreach (self::$_phoneCountryCodes as $v) {
            if (preg_match("/^$v\d+$/", $phone)) {
                $phone_code = $v;
                break;
            }
        }
        if (!$phone_code) {
            return $arg;
        }
        
        // add '+' and dot to result phone number
        $phone = preg_replace("/^$phone_code/", "+{$phone_code}.", $phone);
        return $phone;
    }

    /**
     * _getApiUrl
     * @param string $command
     * @param array $params
     * @return string
     */
    private function _getApiUrl($command, array $params)
    {

        $isPost = false;
        if ($command == 'namecheap.ssl.activate' || strtolower($command) == 'namecheap.ssl.parsecsr' || strtolower($command) == 'namecheap.ssl.reissue') {
            $isPost = true;
        }

        if (!$isPost) {
            return ($this->_testMode ? self::$testUrl : self::$url) . '?' . http_build_query($this->_getApiParams($command, $params),'','&');
        } else {
            return ($this->_testMode ? self::$testUrl : self::$url);
        }
    }

    /**
     * _getApiParams
     * @param string $command
     * @param array $params
     * @return string
     */
    private function _getApiParams($command, array $params)
    {
        $params['Command'] = $command;
        $params['ApiUser'] = $this->_apiUser;
        $params['ApiKey']  = $this->_apiKey;

        if (!array_key_exists('UserName', $params) || !strlen($params['UserName'])) {
            $params['UserName'] = $params['ApiUser'];
        }
        if (!array_key_exists('ClientIp', $params)) {
            $params['ClientIp'] = $this->_getClientIp();
        }

        // format phone/fax fields
        foreach ($params as $k => &$v) {
            if (preg_match('/(Phone|Fax)/i', $k)) {
                $v = trim($v);
                if(!empty($v)){
                    $v = $this->_formatPhone($v);
                }                
            }
        }
        
        return $params;
    }
    
    /**
     * _getClientIp
     * @return string
     */
    private function _getClientIp()
    {
        $clientip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        return $clientip ? $clientip : "10.11.12.13";
    }

    /**
     * _xml2Array
     * @param string $xml
     * @return array
     */
    private function _xml2Array($xml)
    {
        if (!($xml instanceof SimpleXMLElement)) {
            throw new NamecheapApiException("Not a SimpleXMLElement object");
        }
        $result = array();
        foreach ($xml->attributes() as $attrName => $attr) {
            $result['@attributes'][$attrName] = (string)$attr;
        }
        foreach ($xml->children() as $childName => $child) {
            if (array_key_exists($childName, $result)) {
                if (!is_array($result[$childName]) || !isset($result[$childName][1])) {
                    $result[$childName] = array($result[$childName]);
                }
                $result[$childName][] = $this->_xml2Array($child);
            } else {
                $result[$childName] = $this->_xml2Array($child);
            }
        }
        $value = trim((string)$xml);
        if (array_keys($result)) {
            if ($value) {
                $result['@value'] = $value;
            }
        } else {
            $result = $value;
        }
        return $result;
    }

    /**
     * 
     * @param string $xmlResponse
     * @return integer
     */
    public function getGmtTS($response)
    {
        $gmt = 0;
        if (preg_match("/<GMTTimeDifference>(-|\+)+([0-9:]+)<\/GMTTimeDifference>/", $response, $matches)) {
            $gmt = $matches[2];
            $gmtsign = preg_replace("/-+/", "-", $matches[1]);
            $gmtsign = $gmtsign == '-' ? -1 : 1;
            list($gmth, $gmtm) = explode(":", $gmt);
            $gmt = $gmtsign*(($gmth*3600)+($gmtm*60));
        }
        return $gmt;
    }

    public function getLastUrl() {
        return $this->_requestUrl;
    }
    public function getLastParams() {
        return $this->_requestParams;
    }
    public function getLastResponse() {
        return $this->_response;
    }
}
}

/**
 * NamecheapApiException
 */
if (!class_exists("NamecheapApiException")) {
    class NamecheapApiException extends Exception {}
}


