<?php
/**
 * Routes for Xhgui
 */

use Slim\App;
use Slim\Views\Twig;
use XHGui\Controller\ImportController;
use XHGui\ServiceContainer;
use XHGui\Twig\TwigExtension;

/** @var App $app */
/** @var ServiceContainer $di */

$app->error(static function (Exception $e) use ($di, $app) {
    /** @var Twig $view */
    $view = $di['view'];
    $view->parserOptions['cache'] = false;
    $view->parserExtensions = [
        new TwigExtension($app),
    ];

    // Remove the controller so we don't render it.
    unset($app->controller);

    $app->view($view);
    $app->render('error/view.twig', [
        'message' => $e->getMessage(),
        'stack_trace' => $e->getTraceAsString(),
    ]);
});

// Profile Runs routes
$app->get('/', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->index();
})->setName('home');

$app->get('/run/view', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->view();
})->setName('run.view');

$app->get('/run/delete', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->deleteForm();
})->setName('run.delete.form');

$app->post('/run/delete', static function () use ($di, $app) {
    $di['runController']->deleteSubmit();
})->setName('run.delete.submit');

$app->get('/run/delete_all', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->deleteAllForm();
})->setName('run.deleteAll.form');

$app->post('/run/delete_all', static function () use ($di, $app) {
    $di['runController']->deleteAllSubmit();
})->setName('run.deleteAll.submit');

$app->get('/url/view', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->url();
})->setName('url.view');

$app->get('/run/compare', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->compare();
})->setName('run.compare');

$app->get('/run/symbol', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->symbol();
})->setName('run.symbol');

$app->get('/run/symbol/short', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->symbolShort();
})->setName('run.symbol-short');

$app->get('/run/callgraph', static function () use ($di, $app) {
    $app->controller = $di['runController'];
    $app->controller->callgraph();
})->setName('run.callgraph');

$app->get('/run/callgraph/data', static function () use ($di, $app) {
    $di['runController']->callgraphData();
})->setName('run.callgraph.data');

$app->get('/run/callgraph/dot', static function () use ($di, $app) {
    $di['runController']->callgraphDataDot();
})->setName('run.callgraph.dot');

// Import route
$app->post('/run/import', static function () use ($di, $app) {
    /** @var ImportController $controller */
    $controller = $di['importController'];
    $controller->import();
})->setName('run.import');

// Watch function routes.
$app->get('/watch', static function () use ($di, $app) {
    $app->controller = $di['watchController'];
    $app->controller->get();
})->setName('watch.list');

$app->post('/watch', static function () use ($di) {
    $di['watchController']->post();
})->setName('watch.save');

// Custom report routes.
$app->get('/custom', static function () use ($di, $app) {
    $app->controller = $di['customController'];
    $app->controller->get();
})->setName('custom.view');

$app->get('/custom/help', static function () use ($di, $app) {
    $app->controller = $di['customController'];
    $app->controller->help();
})->setName('custom.help');

$app->post('/custom/query', static function () use ($di) {
    $di['customController']->query();
})->setName('custom.query');

// Waterfall routes
$app->get('/waterfall', static function () use ($di, $app) {
    $app->controller = $di['waterfallController'];
    $app->controller->index();
})->setName('waterfall.list');

$app->get('/waterfall/data', static function () use ($di) {
    $di['waterfallController']->query();
})->setName('waterfall.data');

// Metrics
$app->get('/metrics', static function () use ($di, $app) {
    $di['metricsController']->metrics();
})->setName('metrics');
