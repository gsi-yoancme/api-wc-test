<?php

use Illuminate\Http\Request;
use Intercom\IntercomClient;
use GuzzleHttp\Client;
use Mockery\CountValidator\Exception;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->post('/webhook', function(Request $request) use($router) { 

    $client = new Intercom\IntercomClient(env('INTERCOM_TOKEN'));
    /**
     * Reply to a user's last conversation
     * See more options here: https://developers.intercom.com/reference#replying-to-users-last-conversation
     */

    $client->conversations->replyToLastConversation([
        "email" => "test@example.com",
        "body" => "Thanks :)",
        "type" => "user",
        "message_type" => "comment"
    ]);
});

$router->get('/drift', function(Request $request) use($router) { 
    try {
        //Verify head e9DhvzkPQsM1cQ6yZbGJ6IZDaCb7QgKZ
        $client = new Client();

        $res = $client->request('POST', 'https://driftapi.com/oauth2/token', [
        'form_params' => [
            'client_id' => 'acjCMiayzuPbzNZgt5DkYDKjcm44ZJq1',
            'client_secret' => 'i17bfZv9xw7SG2buXfFhQGJw5DHT5qaJ',
            'code' => $request->get('code'),
            'grant_type' => 'authorization_code',
        ]
    ]);

        if ($res->getStatusCode() == 200) { // 200 OK
            $response_data = $res->getBody()->getContents();
            putenv('DRIFT_REFRESH_TOKEN', $response_data['refresh_token']);
            putenv('DRIFT_ACCESS_TOKEN', $response_data['access_token']);
            putenv('DRIFT_TOKEN_TYPE', $response_data['token_type']);
            putenv('DRIFT_EXPIRES_IN', $response_data['expires_in']);
            putenv('DRIFT_EXPIRES_IN', $response_data['expires_in']);
        }
        return $response_data;
    }
    catch(Exception $ex){
        print_r($ex);
    }
});

$router->post('/drift', function(Request $request) use($router) { 
    try {
        //Verify head e9DhvzkPQsM1cQ6yZbGJ6IZDaCb7QgKZ
        $client = new Client();

        $res = $client->request('POST', 'https://driftapi.com/oauth2/token', [
        'form_params' => [
            'client_id' => 'acjCMiayzuPbzNZgt5DkYDKjcm44ZJq1',
            'client_secret' => 'i17bfZv9xw7SG2buXfFhQGJw5DHT5qaJ',
            'code' => $request->query->get('code'),
            'grant_type' => 'authorization_code',
        ]
    ]);

        if ($res->getStatusCode() == 200) { // 200 OK
            $response_data = $res->getBody()->getContents();
            if(!is_array($response_data)) {
                $response_data = json_decode($response_data);
            }
            putenv('DRIFT_REFRESH_TOKEN', $response_data['refresh_token']);
            putenv('DRIFT_ACCESS_TOKEN', $response_data['access_token']);
            putenv('DRIFT_TOKEN_TYPE', $response_data['token_type']);
            putenv('DRIFT_EXPIRES_IN', $response_data['expires_in']);
            putenv('DRIFT_EXPIRES_IN', $response_data['expires_in']);
        }
        return $response_data;
    }
    catch(Exception $ex){
        print_r($ex);
    }
});