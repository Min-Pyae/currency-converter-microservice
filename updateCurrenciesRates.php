<?php

    @date_default_timezone_set("GMT"); 
    
    require_once 'config.php';


    // FUNCTION FOR MAKING API REQUESTS
    function makeAPIRequest($url) {

        // INITIALIZING cURL SESSION
        $curl = curl_init($url);

        // SETTING cURL OPTION
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // RETURN THE RESPONSE AS A STRING
        
        // EXECUTING cURL SESSION AND STORING THE RESPONSE
        $response = curl_exec($curl);

        // CHECKING FOR ERRORS
        if (curl_errno($curl)) {

            $curlErrorMessage = curl_error($curl);
            curl_close($curl);

            // RETURNING THE ERROR MESSAGE
            return "cURL error: $curlErrorMessage";

        }

        // CLOSING cURL SESSION
        curl_close($curl);

        // RETURNING THE RESPONSE
        return $response;
    }


    // FUNCTION FOR FETCHING COUNTRIES DATA
    function fetchCountriesData() {
        
        // MAKING COUNTRIES API REQUEST
        $countries_xml_string = makeAPIRequest(COUNTRIES_URL);

        // LOADING COUNTRIES XML FILE
        $xml = simplexml_load_string($countries_xml_string);

        // COUNTRIES DATA ARRAY
        $countriesData = [];

        // UPDATING COUNTRIES DATA ARRAY WITH DATA FROM COUNTRIES API
        foreach ($xml->CcyTbl->CcyNtry as $entry) {
            $currencyCode = (string)$entry->Ccy;
            $countryName = (string)$entry->CtryNm;
            $currencyName = (string)$entry->CcyNm;

            $countriesData[$currencyCode] = [
                'name' => $currencyName,
                'countries' => array_unique(array_merge($countriesData[$currencyCode]['countries'] ?? [], [$countryName])),
            ];
        }

        // RETURNING THE COUNTRIES DATA
        return $countriesData;
    }


    // FUNCTION FOR FETCHING AND UPDATING CURRENCIES RATES
    function fetchAndUpdateCurrencies() {

        // MAKING CURRENCIES RATES API REQUEST
        $currenciesRatesJson = makeAPIRequest(RATES_URL);

        // DECODING CURRENCIES RATES JSON
        $currenciesRatesData = json_decode($currenciesRatesJson, true);


        // CHECKING ERROR
        if (!$currenciesRatesData) {
            die("Error fetching currencies rates data!");
        }


        // FETCHING COUNTRIES DATA
        $countriesData = fetchCountriesData();


        // CREATING A NEW SimpleXMLElement FOR RATES XML STRUCTURE
        $ratesXML = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><rates></rates>');

        // TIMESTAMP 'AT' ELEMENT
        $ratesXML->addAttribute('ts', time());
        
        // 'BASE' CURRENCY ELEMENT
        $ratesXML->addAttribute('base', BASE_CURRENCY);

        // GBP RATE 
        $baseRate = $currenciesRatesData['rates'][BASE_CURRENCY];


        foreach ($currenciesRatesData['rates'] as $currency => $rate) {

            if (!isset($countriesData[$currency])) {
                continue;
            }

            // 'CURRENCY' ELEMENT
            $currencyRate = $ratesXML->addChild('currency');
            $currencyRate->addAttribute('rate', number_format($rate / $baseRate, 5, '.', ''));
            

            // 'LIVE' ATTRIBUTE
            $currencyLiveValue = in_array($currency, CURRENCY_CODES) ? "1" : "0";
            $currencyRate->addAttribute('live', $currencyLiveValue);


            // 'CODE' ELEMENT
            $currencyRate->addChild('code', $currency);


            // CURRENCY 'NAME' ELEMENT
            $currencyRate->addChild('curr', $countriesData[$currency]['name']);


            // CONCATENATING ALL COUNTRIES INTO A SINGLE STRING
            $countries = implode(', ', $countriesData[$currency]['countries']);
            
            // FORMATTING WORDS FOR CURRENCY LOCATIONS
            $wordsToBeFixed = array("Of", "And", "U.s.");
            $fixedWords = array("of", "and", "U.S.");
            $countriesText = str_replace($wordsToBeFixed, $fixedWords, mb_convert_case($countries, MB_CASE_TITLE, 'UTF-8'));
            
            // CURRENCY 'LOCATION' ELEMENT
            $currencyRate->addChild('loc', $countriesText);

        }

        // SAVING FORMATTED RATES XML FILE
        $dom = dom_import_simplexml($ratesXML)->ownerDocument;
        $dom->formatOutput = true;
        $dom->save(RATES_XML_FILE_PATH);

    }

    
    // FUNCTION FOR CHECKING IF RATES XML IS MORE THAN 2 HOURS OLD
    function checkRatesXMLMoreThanTwoHours() {

        // LOADING RATES XML FILE
        $ratesXML = simplexml_load_file(RATES_XML_FILE_PATH);

        // CURRENT TIME
        $currentTime = time();

        // LAST UPDATED TIMESTAMP OF RATES XML FILE
        $lastUpdated = (int)$ratesXML->xpath("//rates/@ts")[0];

        // CALCULATING THE TIME DIFFERENCE IN SECONDS
        $timeDifference = $currentTime - $lastUpdated;


        // CHECKING IF TIME DIFFERENCE IS MORE THAN 2 HOURS (7200 SECONDS)
        if ($timeDifference > 7200) {

            // FETCHING CURRENCIES DATA AND UPDATING RATES XML FILE
            fetchAndUpdateCurrencies();

        }

    }

?>
