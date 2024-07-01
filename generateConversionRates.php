<?php

    @date_default_timezone_set("GMT"); 

    require_once 'config.php';
    require_once 'updateCurrenciesRates.php';

    
    // FUNCTION FOR GENERATING CONVERSION RATES IN XML OR JSON FORMAT
    function generateConversionRates($from, $to, $amnt, $format) {

        // CHECKING IF RATES XML IS MORE THAN 2 HOURS OLD
        checkRatesXMLMoreThanTwoHours();

        // LOADING CURRENCIES XML FILE
        $ratesXML = simplexml_load_file(RATES_XML_FILE_PATH);

        // 'FROM' CURRENCY
        $fromCurrency = $ratesXML->xpath("//currency[code='{$from}']")[0];

        // 'TO' CURRENCY
        $toCurrency = $ratesXML->xpath("//currency[code='{$to}']")[0];


        // CALCULATING RATE BETWEEN THE TWO SPECIFIED CURRENCIES
        $fromCurrencyRate = (float)$fromCurrency['rate'];
        $toCurrencyRate = (float)$toCurrency['rate'];
        $rate = $toCurrencyRate / $fromCurrencyRate;

        
        // CREATING A NEW SimpleXMLElement FOR CONVERSION XML STRUCTURE
        $conversionXML = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><conv></conv>');

        // LAST UPDATED 'AT' ELEMENT
        $lastUpdated = $ratesXML->xpath("//rates/@ts")[0];
        $lastUpdatedInt = (int)$lastUpdated;
        $conversionXML->addChild('at', date('d M Y H:i', $lastUpdatedInt)); 


        // 'RATE' ELEMENT
        $conversionXML->addChild('rate', number_format($rate , 2, '.', ''));
        

        // 'FROM' CURRENCY ELEMENT
        $fromNode = $conversionXML->addChild('from');
        $fromNode->addChild('code', $from);
        $fromNode->addChild('curr', $fromCurrency->curr);
        $fromNode->addChild('loc', $fromCurrency->loc);
        $fromNode->addChild('amnt', $amnt);


        // 'TO' CURRENCY ELEMENT
        $toNode = $conversionXML->addChild('to');
        $toNode->addChild('code', $to);
        $toNode->addChild('curr', $toCurrency->curr);
        $toNode->addChild('loc', $toCurrency->loc);
        

        // CALCULATING AMOUNT BASED ON THE SPECIFIED CURRENCIES
        $toCurrencyAmount = number_format((float)$amnt * $rate, 2, '.', '');

        // 'AMOUNT' ELEMENT
        $toNode->addChild('amnt', $toCurrencyAmount);


        // SAVING AS XML FORMAT
        $xml = $conversionXML->asXML();
        
        // XML FORMATTED CONVERSION
        if ($format === 'xml') {
            header('Content-Type: text/xml');
            echo $xml;
        }

        // JSON FORMATTED CONVERSION
        if ($format === 'json') {
            $arrayForJson = array(
                'conv' => array(
                    "at" => date('d M Y H:i'),
                    "rate" => floatval(number_format($rate , 2, '.', '')),
                    "from" => array(
                        "code" => "$from", 
                        "curr" => "$fromCurrency->curr",
                        "loc" => "$fromCurrency->loc",
                        "amnt" => floatval(number_format($amnt, 2, '.', ''))
                    ),
                    "to" => array(
                        "code" => "$to", 
                        "curr" => "$toCurrency->curr",
                        "loc" => "$toCurrency->loc",
                        "amnt" => floatval($toCurrencyAmount),
                    )
                )
            );
            $json = header('Content-Type: application/json');
		    $json .= json_encode($arrayForJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            echo $json;
        }

    }
        
?>