<?php

    @date_default_timezone_set("GMT");

    require_once '../config.php';


    // FUNCTION FOR CHECKING UPDATE ERRORS
    function checkUpdateError($cur, $action) {

        $getActionParams = array_intersect(ACTION_PARAMS, array_keys($_GET));

        // CHECKING IF THERE ARE MISSING OR INCORRECT PARAMETERS
        if (count($getActionParams) < 2 || count($getActionParams) > 2 || (!in_array($action, ACTION)) || $action == NULL) {
            return 2000;
        }

        // CHECKING IF CURRENCY CODE IS IN WRONG FORMAT
        if($cur == NULL || !ctype_upper($cur) || strlen($cur) != 3) {
            return 2100;
        }


        // LOADING RATES XML FILE
        $ratesXML = simplexml_load_file(RATES_XML_FILE_PATH);
        $currency = $ratesXML->xpath("//currency[code='{$cur}']")[0];
        $liveValue = $currency['live'];
        $rate = $currency['rate'];

       
        // CHECKING IF CURRENCY CODE IS NOT FOUND FOR UPDATE
        if((empty($currency)) || ($action == "put" && $liveValue == 0) || ($action == "post" && $liveValue == 1)) {
            return 2200;
        }

        // CHECKING IF NO RATE IS LISTED FOR THIS CURRENCY
        if ($rate == '' || ($liveValue == 0 && $action == "del")) {
            return 2300;
        }

        // CHECKING IF THE BASE CURRENCY IS BEING UPDATED
        if ($cur == "GBP") {
            return 2400;
        }

        // CHECKING IF RATES XML DATA FILES EXISTS
        if (!file_exists(RATES_XML_FILE_PATH)) {
            return 2500;
        }

    }


    // FUNCTION FOR GENERATING UPDATE ERROR IN XML FORMAT
    function generateUpdateError($errorCode, $action) {

        // ERROR MESSAGE
        $errorMessage = ERRORS[$errorCode];
        
        // CREATING A NEW SimpleXMLElement FOR UDPATE ERROR XML STRUCTURE
        $updateErrorXml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><action></action>');
        $updateErrorXml->addAttribute('type', !empty($action) ? $action : "NULL");

        // 'ERROR' ELEMENT
        $errorNode = $updateErrorXml->addChild('error');

        // 'CODE' ELEMENT
        $errorNode->addChild('code', $errorCode);

        // 'MESSAGE' ELEMENT
        $errorNode->addChild('msg', $errorMessage);
        
        // DISPLAYING XML FORMATTED UPDATE ERROR
        header('Content-Type: text/xml');
        echo $updateErrorXml->asXML();
    
    }
    
?>