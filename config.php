<?php

    // API KEY
    define(API_KEY, 'cc6b0e4946aabf79c2bdac357ef03481');

    // RATES URL
    define(RATES_URL, "http://data.fixer.io/api/latest?access_key=" . API_KEY);
   
    // COUNTRIES URL
    define(COUNTRIES_URL, "https://www.six-group.com/dam/download/financial-information/data-center/iso-currrency/lists/list-one.xml");

    // RATES XML FILE PATH
    define(RATES_XML_FILE_PATH,  __DIR__ . '/data/rates.xml');

    // BASE CURRENCY
    define(BASE_CURRENCY, 'GBP');

    // CURRENCY CODES
    define(CURRENCY_CODES, array (
            'AUD', 'BRL', 'CAD', 'CHF', 
            'CNY', 'DKK', 'EUR', 'GBP', 
            'HKD', 'HUF', 'INR', 'JPY', 
            'MXN', 'MYR', 'NOK', 'NZD', 
            'PHP', 'RUB', 'SEK', 'SGD', 
            'THB', 'TRY', 'USD', 'ZAR'
        )
    );
    //$additionalCurrencyCodes = [];

    // CONVERSION PARAMETERS
    define(CONV_PARAMS, array('to', 'from', 'amnt', 'format'));

    // FILE FORMATS
    define(FORMATS, array('json', 'xml'));

    // ACTIONS PARAMETERS
    define(ACTION_PARAMS, array('cur', 'action'));

    // ACTIONS
    define(ACTION, array('put', 'post', 'del'));

    // ERRORS
    define(ERRORS, array(
            1000 => 'Required parameter is missing',
            1100 => 'Parameter not recognized',
            1200 => 'Currency type not recognized',
            1300 => 'Currency amount must be a decimal number',
            1400 => 'Format must be xml or json',
            1500 => 'Error in service',
            2000 => 'Action not recognized or is missing',
            2100 => 'Currency code in wrong format or is missing',
            2200 => 'Currency code not found for update',
            2300 => 'No rate listed for this currency',
            2400 => 'Cannot update base currency',
            2500 => 'Error in service'
        )
    );

?>