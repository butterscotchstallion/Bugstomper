<?php
/**
 * Bugstomper - a rudimentary bug tracker just for fun
 * @author PrgmrBill <bill@prgmrbill.com>
 *
 */
use Symfony\Component\HttpFoundation\Request,
    Bugstomper\Model\Issue,
    Bugstomper\Model\User;

ob_start();
session_start();
error_reporting(-1);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

require '../src/Bugstomper/config.php';
require '../vendor/autoload.php';

$app          = new Silex\Application();
$app['debug'] = true;

// Get DB connection
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options'   => array(
        'driver'   => 'pdo_mysql',
        'host'     => DB_HOST,
        'dbname'   => DB_NAME,
        'user'     => DB_USER,
        'password' => DB_PASSWORD
    )
));

// Register Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => realpath('../src/Bugstomper/View')
));

// Add issue model
$app['issueModel'] = $app->share(function(Silex\Application $app) {
    return new Issue($app['db']);
});

// Add user model
$app['userModel'] = $app->share(function(Silex\Application $app) {
    return new User($app['db']);
});

// List issues
$app->get('/', function(Silex\Application $app) {
    $issues = $app['issueModel']->getIssues();
    
    return $app['twig']->render('Issue/List.twig', array(
        'issues' => $issues
    ));
});

// A specific issue
$app->get('/i/{id}', function(Silex\Application $app, Request $req, $id = 0) {
    $issue = $app['issueModel']->getIssueByID($id);
    
    return $app['twig']->render('Issue/Issue.twig', array(
        'issue' => $issue
    ));
    
})->assert('id', '\d+');

// User profile
$app->get('/u/{id}', function(Silex\Application $app, Request $req, $id = 0) {
    $user = $app['userModel']->getUserByID($id);
    
    return $app['twig']->render('User/User.twig', array(
        'user' => $user
    ));
    
})->assert('id', '\d+');

// User list
$app->get('/u', function(Silex\Application $app, Request $req, $id = 0) {
    $users = $app['userModel']->getUsers();
    
    return $app['twig']->render('User/List.twig', array(
        'users' => $users
    ));
    
});

$app->run();





