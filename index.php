<?php

    @date_default_timezone_set("GMT");
    
    require_once 'generateConversionRates.php';
    require_once 'handleConversionErrors.php';


    // CHECKING IF FORMAT IS GIVEN OR EMPTY
    if (!isset($_GET['format']) || empty($_GET['format'])) {
        $_GET['format'] = 'xml';
    }

    // EXTRACTING $_GET PARAMETERS INTO VARIABLES
    extract($_GET);
    
    // CHECKING CONVERSION ERRORS
    $errorCode = checkConversionError($from, $to, $amnt, $format);

    if ($errorCode !== NULL) {

        // GENERATING CONVERSION ERRORS IN XML OR JSON FORMAT
        generateConversionError($errorCode, $format);

    } else {

        // GENERATING CONVERSION RATES IN XML OR JSON FORMAT
        generateConversionRates($from, $to, $amnt, $format);

    }

?>