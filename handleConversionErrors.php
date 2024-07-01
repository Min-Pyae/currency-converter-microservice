<?php

    @date_default_timezone_set("GMT");

    require_once 'config.php';


    // FUNCTION FOR CHECKING CONVERSION ERRORS
    function checkConversionError($from, $to, $amnt, $format) {

        // CHECKING IF REQUIRED PARAMETERS ARE MISSING 
        if (count(array_intersect(CONV_PARAMS, array_keys($_GET))) < 4) {
            return 1000;
        }

        // CHECKING IF PARAMETERS NOT RECOGNIZED
        if (count($_GET) > 4) {
            return 1100;
        }

        // CHECKING IF CURRENCY TYPES ARE NOT RECOGNIZED
        if (!isRecognizedCurrencies($from, $to)) {
            return 1200;
        }

        // CHECKING IF CURRENCY AMOUNT IS A DECIMAL VALUE
        if (!preg_match('/^[+-]?(\d*\.\d+([eE]?[+-]?\d+)?|\d+[eE][+-]?\d+)$/', $amnt)) {
            return 1300;
        }

        // CHECKING IF FORMAT IS XML OR JSON
        if (!in_array($format, FORMATS)) {
            return 1400;
        }

        //CHECKING IF RATES XML DATA FILES EXISTS
        if(!file_exists(RATES_XML_FILE_PATH)) {
            return 1500;
        }

    }


    // FUNCTION FOR GENERATING CONVERSION ERROR IN XML OR JSON FORMAT
    function generateConversionError($errorCode, $format) {

        // ERROR MESSAGE
        $errorMessage = ERRORS[$errorCode];
        
        // CREATING A NEW SimpleXMLElement FOR CONVERSION ERROR XML STRUCTURE
        $conv = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><conv></conv>');
        
        // 'ERROR' NODE
        $errorNode = $conv->addChild('error');
        $errorNode->addChild('code', $errorCode);
        $errorNode->addChild('msg', $errorMessage);

        // SAVING AS XML FORMAT
        $conversionErrorXml = $conv->asXML();
        
        // DISPLAYING XML FORMATTED CONVERSION ERROR
        if ($format === 'xml') {
            header('Content-Type: text/xml');
            echo $conversionErrorXml;
        }

        // DISPLAYING JSON FORMATTED CONVERSION ERROR
        if ($format === 'json') {
            $arrayForJson = array(
                'conv' => array(
                    "error" => array(
                        "code" => "$errorCode", 
                        "msg" => "$errorMessage"
                    )
                )
            );
            $json = header('Content-Type: application/json');
		    $json .= json_encode($arrayForJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            echo $json;
        }

    }


    // FUNCTION FOR CHECKING IF CURRENCIES ARE RECOGNIZED
    function isRecognizedCurrencies($from, $to) {

        // LOADING THE RATES XML FILE
        $xml = simplexml_load_file(RATES_XML_FILE_PATH);

        // 'TO' CURRENCY LIVE VALUE
        $toCurrencyLiveValue = (string)$xml->xpath("//currency[code='" . $to . "']/@live")[0];

        // 'FROM' CURRENCY LIVE VALUE
        $fromCurrencyLiveValue = (string)$xml->xpath("//currency[code='" . $from . "']/@live")[0];
    
        // CHECKING IF BOTH 'TO' AND 'FROM' CURRENCY LIVE VALUES ARE EQUAL TO "1"
        if($toCurrencyLiveValue === "1" && $fromCurrencyLiveValue === "1") {
            return true;
        } else {
            return false;
        }

    }
    
?>