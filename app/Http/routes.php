<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
	
    return "Give me data or give me death";
});

Route::get('invitation/{calltoaction}/{leapcode}/{username}/{groupname}', function ($calltoaction, $leapcode, $username, $groupname) {
	$data = array(
        'calltoaction'  => $calltoaction,
        'leapcode'  => $leapcode,
        'username'  => $username,
        'groupname'  => $groupname
        
        );
    return view('invitation')->with('data',$data);
});

Route::get('removefamily/{objectId}', function ($objectId) {
    $data = array(
        
        'objectId'  => $objectId

        );
    return view('removefamily')->with('data',$data);
});
Route::get('removephoto/{objectId}/{familyId}/{userId}', function ($objectId, $familyId, $userId) {
    $data = array(
        
        'objectId'  => $objectId,
         'familyId'  => $familyId,
          'userId'  => $userId

        );
    return view('removephoto')->with('data',$data);
});
Route::get('waitlistinvite/{calltoaction}', function ($calltoaction) {
    $data = array(
        
        'calltoaction'  => $calltoaction

        );
    return view('waitlistinvite')->with('data',$data);
});
Route::get('approval/{password}', function ($password) {
    $data = array(
        
        'password'  => $password

        );
    return view('approval')->with('data',$data);
});
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('webapp/login/{user}/{pass}', function ($user, $pass) {
        // Uses Auth Middleware
        $data = array(
      
        'pass'  => $pass,
        'user'  => $user

        );
         return view('webapp.app')->with('data',$data);
    });
      Route::get('webapp/auth', function () {
        // Uses Auth Middleware
      
         return view('webapp.auth');
    });
        Route::get('webapp/logout', function () {
    
         return view('webapp.logout');
    });
             Route::get('webapp/session', function () {
    
         return view('webapp.session');
    });
    //
});
