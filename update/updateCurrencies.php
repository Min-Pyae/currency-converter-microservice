<?php

    @date_default_timezone_set("GMT");
    
    require_once '../config.php';
    require_once '../updateCurrenciesRates.php';
    require_once 'putCurrencies.php';
    require_once 'postCurrencies.php';
    require_once 'deleteCurrencies.php';


    // FUNCTION FOR UPDATING CURRENCIERS BASED ON PUT, POST AND DELETE ACTIONS
    function updateCurrencies($cur, $action) {

        // LOADING RATES XML FILE
        $ratesXML = simplexml_load_file(RATES_XML_FILE_PATH);

        // CREATING A NEW SimpleXMLElement FOR UPDATE XML STRUCTURE
        $updateXML = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><action></action>');
        
        // 'TYPE' ELEMENT
        $updateXML->addAttribute("type", $action);


        // CHECKING IF ACTION IS 'PUT'
        if($action == ACTION[0]) {

            putCurrencies($cur, $ratesXML, $updateXML);

        } 


        // CHECKING IF ACTION IS 'POST'
        if($action == ACTION[1]) {

            postCurrencies($cur, $ratesXML, $updateXML);

        } 


        // CHECKING IF ACTION IS 'DEL'
        if($action == ACTION[2]) {

            deleteCurrencies($cur, $ratesXML, $updateXML);

        }

    }

?>