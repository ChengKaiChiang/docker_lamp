<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//login
$app->post('/api/login', function (Request $request, Response $response) {
    $get_data = $request->getParsedBody();
    $acc_id = $get_data['acc'];
    $pwd_data = $get_data['pwd'];
    $jdata = array();


    $login = new Account();
    // 登入驗證
    $data = $login->loginAccount($acc_id, $pwd_data);

    $jdata[] = array("data" => $data);
    echo json_encode($jdata);
});

//insert user
$app->post('/api/create', function(Request $request, Response $response){
    $get_data = $request->getParsedBody();
    $acc_id = $get_data['acc'];
    $pwd_data = $get_data['pwd'];

    $create = new Account();
    $create -> createAccount($acc_id,$pwd_data);
});