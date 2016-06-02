<?php
  

use Parse\ParseClient;


use Parse\ParseUser;

use Parse\ParseSessionStorage;
use Parse\ParseObject;

use Parse\ParseQuery;
use Parse\ParseException;

//ParseClient::setStorage( new ParseSessionStorage() );
session_start();
ParseClient::initialize('vQJ2tH0K5tQ5FTmkpx8fNNA4Bw1Ex5C8', 'dd', '8B20802adTUtbFjWh1T1WkgEA80Dwbz5');
ParseClient::setServerURL('https://requests.anyleap.com/parse');
ParseClient::setStorage( new ParseSessionStorage() );
/*
Remove Family Member API 
- Parameters 
@objectID - The user's object ID
#This API will clear the user's family associated with it. It will be used in the clients to delete or remove a member from the family.

Returned data will be in JSON format

@returns
error-message-code
error-message
success-family
*/


    try {
               $currentUser = ParseUser::getCurrentUser();
               //$currentUser = $_SESSION;
      // $out = array_values($currentUser);

if ($currentUser) {
    echo ParseUser::getCurrentUser()->getSessionToken();
      //echo $currentUser->_encode();
} else {
     $arr = array(
        'message' => 'not logged in'
        );
     echo json_encode($arr);
}
      
    } catch (ParseException $error) {
        $displayError = $error->getMessage();
        $displayCode = $error->getCode();

        $arr = array(
            'message' => $displayError,
            'code' => $displayCode
            );
        echo json_encode($arr);
        
    }







?>