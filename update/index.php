<?php 

    @date_default_timezone_set("GMT");

    require_once  '../update/updateCurrencies.php';
    require_once '../update/handleUpdateErrors.php';


    // EXTRACTING $_GET PARAMETERS INTO VARIABLES
    extract($_GET);

    // CHECKING UPDATE ERRORS
    $errorCode = checkUpdateError($cur, $action);

    if ($errorCode !== NULL) {

        // GENERATING UPDATE ERRORS IN XML FORMAT
        generateUpdateError($errorCode, $action);

    } else {

        // GENERATING UPDATED CURRENCIES IN XML FORMAT
        updateCurrencies($cur, $action);

    }
    
?>