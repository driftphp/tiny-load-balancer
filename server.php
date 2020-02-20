<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use React\EventLoop\Factory;
use React\Http\Server;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

require __DIR__ . "/vendor/autoload.php";

$inputDefinition = (new InputDefinition([
    new InputArgument('port', InputArgument::REQUIRED)
]));
$input = new ArgvInput($argv, $inputDefinition);

echo 'Going to instance the loop : ' . PHP_EOL;
$loop = Factory::create();
echo 'Loop instanced : ' . get_class($loop) . PHP_EOL;

$loop->addSignal(SIGINT, $func = function ($signal) use ($loop, &$func) {
    $loop->stop();
});

$server = new Server(function (ServerRequestInterface $request) {
    echo '.';
    return new Response(
        200,
        array(
            'Content-Type' => 'text/plain'
        ),
        "Hello World!\n"
    );
});

$port = $input->getArgument('port');
$socket = new React\Socket\Server("0.0.0.0:$port", $loop);
$server->listen($socket);

try {
    $loop->run();
} catch (\Throwable $trowable) {
    echo $trowable->getMessage();
}