<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Predis\Client;

// Routes that map to Controllers
$app->post('/users', function (Request $request, Response $response, array $args) {
    $userBody = $request->getParsedBody();
    $userRepository = new UserRepository();
    $user = $userRepository->create($userBody['name'], $userBody['phone'], $userBody['pass']);
    return $user;
});

$app->get('/users', function (Request $request, Response $response, array $args) {
    $userRepository = new UserRepository();
    return json_encode($userRepository->fetchAll());
});

$app->get('/example', function (Request $request, Response $response, array $args) {

    $client = new Client();
    $sessionID = $request->getParam('session');
    $password = $request->getParam('password');

    $isAuth = $client->sismember('authenticatedUsers', $sessionID);

    if ($isAuth) {
        //Do stuff with him
    } else {
        $passwordAuthentication = false;
        if ($passwordAuthentication) {
            $client->sadd('authenticatedUsers', $sessionID);
            // Do stuff with thim
        } else {
            //sorry not authorized
        }
    }
    // CHeck if that person has a valid Account


    return $request->getQueryParam('phone');
});

// Model
class User
{
    private $name;
    private $phone;
    private $pass;

    public function __construct($name, $phone, $pass)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->pass = $pass;
    }

    public function __toString()
    {
        $user['name'] = $this->name;
        $user['phone'] = $this->phone;
        $user['pass'] = $this->pass;

        return json_encode($user);
    }
}

// Repository
class UserRepository
{
    private $client;

    function __construct()
    {
        $this->client = $client = new Client();
    }

    function create($name, $phone, $pass)
    {
        $user = new User($name, $phone, $pass);
        $this->client->sadd('users', $user);
        return $this->client->smembers('users')[0];
    }

    function fetchAll()
    {
        return $this->client->smembers('users');
    }
}