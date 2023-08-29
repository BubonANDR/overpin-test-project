<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use LordDashMe\SimpleCaptcha\Captcha;

require __DIR__ . '/./vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['db']['host']   = "localhost";
$config['db']['user']   = "sqluser";
$config['db']['pass']   = "password";
$config['db']['dbname'] = "pic_comments_db";


$app = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();

$container['view'] = new \Slim\Views\PhpRenderer("./templates");


$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO(
        "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'],
        $db['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/', function (Request $request, Response $response) {
 
    $response = $this->view->render($response, "mainpage.php", []);
    return $response;
});

$app->get('/comments', function (Request $request, Response $response) {

    $actions = new ActionClass($this->db);
    $response = $actions->fetchAll();
    return $response;
});

$app->get('/captcha', function (Request $request, Response $response) {

    $captcha = new Captcha();
    $captcha->code();
    $captcha->image();
    $captcha->storeSession();
    $response = json_encode(["captcha" => $captcha->getImage(), "code" => $captcha->getCode()]);
    return $response;
});


$app->post('/comment', function (Request $request, Response $response) {
    $actions = new ActionClass($this->db);
    $data = $request->getParsedBody();
    $post_id = filter_var($data['id']);
    $comment_user = filter_var($data['name']);
    $comment_content = filter_var($data['comment']);
    if ($comment_user) {
        $actions->insertComment($comment_user, $comment_content);
    } else if ($post_id) {
        $actions->deleteComment($post_id);
    }
    $response = $response->withStatus(200)->withHeader('Location', '/');
    return $response;
});


$app->run();
