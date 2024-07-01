<?php

    @date_default_timezone_set("GMT");

    require_once '../config.php';


    // FUNCTION FOR 'POST' ACTION
    function postCurrencies($cur, $ratesXML, $updateXML) {

        // LOADING 'CURRENCY' ELEMENT FROM RATES XML FILE
        $currency = $ratesXML->xpath("//currency[code='{$cur}']")[0];

        // LAST UPDATED 'AT' ELEMENT
        $lastUpdated = $ratesXML->xpath("//rates/@ts")[0];
        $updateXML->addChild('at', date('d M Y H:i', (string)$lastUpdated));

        // 'NEW RATE' ELEMENT
        $updateXML->addChild("rate", $currency['rate']);

        // 'CURRENCY' ELEMENT
        $curr = $updateXML->addChild('curr');

        // 'CODE' ELEMENT
        $curr->addChild('code', $currency->code);

        // 'NAME' ELEMENT
        $curr->addChild('name', $currency->curr);

        // 'LOCATIONS' ELEMENT
        $curr->addChild('loc', $currency->loc);


        // UPDATING 'LIVE' ATTRIBUTE VALUE TO "1"
        $currency['live'] = 1;


        // SAVING RATES XML FILE
        $ratesXML->asXML(RATES_XML_FILE_PATH);
    

        // OUTPUTTING UPDATE XML
        header('Content-Type: text/xml');
        echo $updateXML->asXML();
    }

?>