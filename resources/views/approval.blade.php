<?php
use Parse\ParseClient;
use Parse\ParseUser;
use Parse\ParseObject;
use Parse\ParseSessionStorage;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseException;
ParseClient::initialize('vQJ2tH0K5tQ5FTmkpx8fNNA4Bw1Ex5C8', 'dd', '8B20802adTUtbFjWh1T1WkgEA80Dwbz5');
ParseClient::setServerURL('https://requests.anyleap.com/parse');
//use Aloha\Twilio;
/*
Approval Process GUI
*/
$password = $data['password'];

$arr;

/*
Approval process GUI 
*/

if ($password != NULL && $password == "1ftcvhaa2ffLeap") {

  echo "<a href='?spotlight'>Click here to make a group spotlight </a> <br /> " ;
  /*Block for group selection */
  if (isset($_GET['spotlight'])) {

    $query4 = new ParseQuery("Family");
    $results = $query4->find();
    echo "<p>Successfully retrieved " . count($results) . " groups.</p><small>Select a group to make it spotlight.</small><hr />";
    
    //Start--Table
echo "<h3><a href='/approval/1ftcvhaa2ffLeap'>< GO BACK </a></h3>";
    for ($i = 0; $i < count($results); $i++) {
      $object = $results[$i];
      echo "<a href='?spotlight&spotlightsubmitid=".$object->getObjectId() . "'>".$object->get('name')." - ".$object->get('joinCode')."</a><br />";
  }
  echo "<hr />";
  }

    /*
    Block for getting and displaying all public groups on the spotlight
    */
    $query = new ParseQuery("Family");
    $query->equalTo("public", true);
    $results = $query->find();
    echo "<p>Successfully retrieved " . count($results) . " spotlight groups.</p><small>Select a group to start the public approval process.</small><hr />";
    
    //Start--Table
    echo "<table style='padding:5px;'><tr><th>Object ID</th><th>Group Name</th><th>Group Description</th><th>Action</th></tr>";
    for ($i = 0; $i < count($results); $i++) {
      $object = $results[$i];
      echo "<tr><td>".$object->getObjectId() . "</td><td> <a href='?group=".$object->getObjectId()."'> <strong>" . $object->get('name')."</strong></a></td><td>".$object->get('bio')."</td><td><a href='?spotlightremoveid=".$object->getObjectId()."'>Remove Spotlight</a></td></tr>";
  }
  echo "</table><hr />";
    //End--Table

    /*
    Picture approval
    */
    if (isset($_GET['group'])) { 
        $groupid = $_GET['group']; 

        $doesNotExist = new ParseQuery("Photo");
        $doesNotExist->doesNotExist("approved");
        
        $notApproved = new ParseQuery("Photo");
        $notApproved->equalTo("approved", false);
    
         $query2 = ParseQuery::orQueries([$doesNotExist, $notApproved]); 
          $query2->equalTo("family", array("__type" => "Pointer", "className" => "Family", "objectId" => $groupid));

    $query2->descending("created_at");
    $query2->limit(50);
    $results = $query2->find();

        echo "<h4>You are currently approving a group [id=".$groupid."] </h4>";

        echo "<p>Retrieved " . count($results) . " photos in this group.</p><hr />";
    
       

        //Start--Table @PictureDisplay
        echo "<table style='padding:5px;'><tr><th>Photo</th><th>Caption</th><th>Action</th></tr>";
        for ($i = 0; $i < count($results); $i++) {
          $object = $results[$i];
          echo "<tr><td><img src='".$object->get('image')->getURL()."' height='300px' width='400px' /></td><td>" . $object->get('caption')."</td><td><a href='1ftcvhaa2ffLeap?group=".$groupid."&approve=".$object->getObjectId()."'''>Approve</a><br /><br /><a href='1ftcvhaa2ffLeap?group=".$groupid."&delete=".$object->getObjectId()."'''>Delete Forever</a></td></tr>";

      }
      echo "</table>

      <hr />";
      
      if (isset($_GET['approve'])) { 
        $photoid = $_GET['approve'];
        $query3 = new ParseQuery("Photo");
        try {
            $imageObject = $query3->get($photoid);
            $imageObject->set("approved", true);
                try {
             $imageObject->save(true);
             header("Refresh:0; url=1ftcvhaa2ffLeap?group=".$groupid."");
            } catch (ParseException $ex) {  
      
            }
        } catch (ParseException $ex) {

        }
    }
    if (isset($_GET['delete'])) { 
        $photoid = $_GET['delete'];
        $query3 = new ParseQuery("Photo");
        try {
            $imageObject = $query3->get($photoid);
            $imageObject->destroy();
                try {
             $imageObject->save(true);
             header("Refresh:0; url=1ftcvhaa2ffLeap?group=".$groupid."");
            } catch (ParseException $ex) {  
      
            }
        } catch (ParseException $ex) {

        }
    }

 
        //End--Table @PictureDisplay

  }

if (isset($_GET['spotlightsubmitid'])) { 
      echo "its set!";
        $photoid = $_GET['spotlightsubmitid'];
        echo $photoid;
        $query5 = new ParseQuery("Family");
        try {
            $imageObject2 = $query5->get($photoid);
            $imageObject2->set("public", true);
                try {
             $imageObject2->save(true);
             header("Refresh:0; url=1ftcvhaa2ffLeap");
            } catch (ParseException $ex) {  
          echo "error";
            }
        } catch (ParseException $ex) {
echo "error";
        }
    }

    if (isset($_GET['spotlightremoveid'])) { 
      echo "its set!";
        $photoid = $_GET['spotlightremoveid'];
        echo $photoid;
        $query6 = new ParseQuery("Family");
        try {
            $imageObject2 = $query6->get($photoid);
            $imageObject2->set("public", false);
                try {
             $imageObject2->save(true);
             header("Refresh:0; url=1ftcvhaa2ffLeap");
            } catch (ParseException $ex) {  
          echo "error";
            }
        } catch (ParseException $ex) {
echo "error";
        }
    }

} else {
    $arr = array(
        'message' => 'Invalid parameters'
        );
}




/*
Show response in JSON format 
*/
if (!empty($arr)) {
    echo json_encode($arr);
} else {
    $arr = array(
        'message' => 'Something went wrong'
        );
}




?>