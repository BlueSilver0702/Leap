<?php
use Parse\ParseClient;
use Parse\ParseUser;
use Parse\ParseObject;
use Parse\ParseSessionStorage;
use Parse\ParseQuery;
use Parse\ParseException;
ParseClient::initialize('vQJ2tH0K5tQ5FTmkpx8fNNA4Bw1Ex5C8', 'dd', '8B20802adTUtbFjWh1T1WkgEA80Dwbz5');
ParseClient::setServerURL('https://requests.anyleap.com/parse');
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

$objectId = $data['objectId'];
$arr;


if ($objectId != NULL || $objectId !== "%20" || $objectId !== "") {
    try {
        $post = new ParseQuery("Photo");
        
        $post->equalTo("objectId", $objectId);
        $results = $post->find();
        
        
        for ($i = 0; $i < count($results); $i++) {
            $object = $results[$i];
            $oldFamily = $object->get("family");
            $object->delete("family");
            $object->save(true);
            $arr = array(
                'message' => 'Succesfully removed'

                
                );
            
        }
        
    } catch (ParseException $error) {
        $displayError = $error->getMessage();
        $displayCode = $error->getCode();

        $arr = array(
            'message' => $displayError,
            'code' => $displayCode
            );
        
        
    }

    
    /*$activity = new ParseObject("Activity");

    $familyPointer = $activity->dataType('pointer', array( 'Family', $oldFamily ));
    $userPointer = $activity->dataType('pointer', array( '_User', $objectId ));
    
    $obj = new ParseObject("_User");
    $obj->setAssociativeArray("_User", array('__type' => 'Pointer', 'className' => 'Family', 'objectId' => $oldFamily));

    $activity->set("type", "wasremoved");
    $activity->set("family", $familyPointer);
    $activity->set("toUser", $userPointer);
    $activity->set("fromUser", $userPointer);

    try {
      $activity->save(true);
      echo 'New object created with objectId: ' . $activity->getObjectId();

  } catch (ParseException $ex) {  
    $displayError = $ex->getMessage();
    $arr = array(
        'error' => $displayError
        );
    }*/


} else {
    $arr = array(
        'message' => 'Error processing request'
        );
    
}

if (!empty($arr)) {
   echo json_encode($arr);
} else {
  $arr = array(
    'message' => 'Something went wrong'
    );
  echo json_encode($arr);
}

?>