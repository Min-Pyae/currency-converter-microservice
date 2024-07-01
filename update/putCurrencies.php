<?php

    @date_default_timezone_set("GMT");

    require_once '../config.php';
    require_once '../updateCurrenciesRates.php';


    // FUNCTION FOR 'PUT' ACTION
    function putCurrencies($cur, $ratesXML, $updateXML) {

        // LOADING 'CURRENCY' ELEMENT FROM RATES XML FILE
        $currency = $ratesXML->xpath("//currency[code='{$cur}']")[0];

        // OLD RATE
        define(OLD_RATE, $currency['rate']);

        // CURRENT TIMESTAMP
        $currentDateTime = time();

        // LAST UPDATED TIMESTAMP OF RATES XML FILE
        $lastUpdated = (int)$ratesXML->xpath("//rates/@ts")[0];

        // CALCULATING THE TIME DIFFERENCE IN SECONDS
        $timeDifference = $currentDateTime - $lastUpdated;


        // CHECKING IF TIME DIFFERENCE IS MORE THAN 2 HOURS (7200 SECONDS)
        if ($timeDifference > 7200) {

            // UPDATING RATES IN RATES XML FILE
            updateRates($ratesXML);

            // LOADING UPDATED RATES XML FILE
            $updatedRatesXML = simplexml_load_file(RATES_XML_FILE_PATH);
            $updatedCurrency = $updatedRatesXML->xpath("//currency[code='{$cur}']")[0];


            // LAST UPDATED 'AT' ELEMENT
            $newLastUpdated = $updatedRatesXML->xpath("//rates/@ts")[0];
            $updateXML->addChild('at', date('d M Y H:i', (string)$newLastUpdated));

            // NEW 'RATE' ELEMENT
            $updateXML->addChild('rate', $updatedCurrency['rate']);

        } else {
            // LAST UPDATED 'AT' ELEMENT
            $updateXML->addChild('at', date('d M Y H:i', (string)$lastUpdated));

            // NEW 'RATE' ELEMENT
            $updateXML->addChild('rate', "Rate is already up to date. Last updated less than 2 hours ago.");
        }


        // 'OLD RATE' ELEMENT
        $updateXML->addChild("old_rate", OLD_RATE);

        // 'CURRENCY' ELEMENT
        $curr = $updateXML->addChild('curr');

        // 'CODE' ELEMENT
        $curr->addChild('code', $currency->code);

        // 'NAME' ELEMENT
        $curr->addChild('name', $currency->curr);

        // 'LOCATIONS' ELEMENT
        $curr->addChild('loc', $currency->loc);

        // OUTPUTTING UPDATE XML
        header('Content-Type: text/xml');
        echo $updateXML->asXML();

    }


    // FUNCTION FOR UPDATING RATES IN RATES XML FILE
    function updateRates($ratesXML) {

        // MAKING CURRENCIES RATES API REQUEST
        $currenciesRatesJson = makeAPIRequest(RATES_URL);

        // DECODING CURRENCIES RATES JSON
        $currenciesRatesData = json_decode($currenciesRatesJson, true);

        // LAST UPDATED 'AT' ELEMENT
        $ratesXML->xpath("//rates/@ts")[0][0] = time();


        // CHECKING ERROR
        if (!$currenciesRatesData) {
            die("Error fetching currencies rates data!");
        }


        // GBP RATE
        $baseRate = $currenciesRatesData['rates'][BASE_CURRENCY];

        // UPDATING EACH 'CURRENCY' ELEMENT
        foreach ($currenciesRatesData['rates'] as $curr => $rate) {

            $ratesXML->xpath("//currency[code='{$curr}']")[0]['rate'] = number_format($rate / $baseRate, 5, '.', '');
            
        }


        // SAVING RATES XML FILE
        $ratesXML->asXML(RATES_XML_FILE_PATH);
    }

?>