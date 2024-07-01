<?php

    @date_default_timezone_set("GMT");

    require_once '../config.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Interface</title>
        <link rel="stylesheet" href="../style/style.css">
    </head>
    <body>
        
        <!-- CONTAINER -->
        <div class="container">
            
            <!-- ACTIONS FORM -->
            <form class="actions-form">

                <!-- HEADING -->
                <h2 class="form-heading">Form Interface for POST, PUT & DELETE</h2>


                <!-- ACTIONS INPUT -->
                <div class="actions-input">
                    <p class="action-input-label">Action:</p>
                
                    <input type="radio" id="put" name="action" value="put">
                    <label for="put">PUT</label>

                    <input type="radio" id="post" name="action" value="post">
                    <label for="post">POST</label>

                    <input type="radio" id="del" name="action" value="del">
                    <label for="del">DEL</label>
                </div>
                

                <!-- CURRENCY INPUT -->
                <div class="currency-input">
                    <label for="currency-codes" class="currency-input-label">Currency:</label>
                    <select name="currency-codes" id="currency-codes">
                        <option value="">Select Currency Code</option>

                        <?php

                            $currenciesXML = simplexml_load_file(RATES_XML_FILE_PATH);
                            $currencies = $currenciesXML->xpath("//currency/code");
                            foreach($currencies as $currency) {
                                echo "<option value='$currency'>$currency</option>";
                            }

                        ?>

                    </select>
                </div>


                <!-- SUBMIT BUTTON  -->
                <button type="button" name="submit" id="submit">Submit</button>
                

                <!-- XML RESPONSE TEXTAREA -->
                <div class="response-textarea">
                    <label for="response" class="response-textarea-label">Response XML:</label>
                    <textarea id="response" name="response" rows="20" cols="60"></textarea>
                </div>   

            </form>

        </div>


        <script>
            
            // SUBMIT BUTTON EVENT LISTENER
            document.getElementById('submit').addEventListener('click', updateCurrencies);
        

            // FUNCTION FOR UPDATING CURRENCIES
            function updateCurrencies() {

                // ACTION INPUT VALUE
                var checkedActionInput = document.querySelector('input[name="action"]:checked');
                if (checkedActionInput) {
                    action = checkedActionInput.value;
                } else {
                    action = null;
                }

                // CURRENCY INPUT VALUE
                var currency = document.getElementById("currency-codes").value;

                // MAKING NEW XML HTTP REQUEST
                var xhr = new XMLHttpRequest();
                xhr.open('GET', "index.php?cur=" + currency + "&action=" + action, true);
                
                // READY STATE CHANGE EVENT LISTENER
                xhr.onreadystatechange = () => {
                    if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                        var xmlResponse = xhr.responseXML;
                        var text = new XMLSerializer().serializeToString(xmlResponse);
                        document.getElementById('response').innerText = text;
                    }
                };

                xhr.send();
            }; 

        </script>

    </body>
</html>