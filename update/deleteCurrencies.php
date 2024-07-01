<?php

    @date_default_timezone_set("GMT");

    require_once '../config.php';


    // FUNCTION FOR 'DELETE' ACTION
    function deleteCurrencies($cur, $ratesXML, $updateXML) {

        // LOADING 'CURRENCY' ELEMENT FROM RATES XML FILE
        $currency = $ratesXML->xpath("//currency[code='{$cur}']")[0];

        // LAST UPDATED 'AT' ELEMENT
        $lastUpdated = $ratesXML->xpath("//rates/@ts")[0];
        $updateXML->addChild('at', date('d M Y H:i', (string)$lastUpdated));

        // 'CODE' ELEMENT
        $updateXML->addChild('code', $currency->code);

        // UPDATING 'LIVE' ATTRIBUTE VALUE TO "0"
        $currency['live'] = 0;
        

        // SAVING RATES XML FILE
        $ratesXML->asXML(RATES_XML_FILE_PATH);
        

        // OUTPUTTING UPDATE XML
        header('Content-Type: text/xml');
        echo $updateXML->asXML();
    }

?>