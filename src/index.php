<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$container = new \DI\Container();

$container->set(\Skulditskiy\LambdaPhp\Bootstrap\DiKeys::HTTP_CLIENT, function (\Psr\Container\ContainerInterface $container) {
    return new GuzzleHttp\Client();
});

$container->set(\Skulditskiy\LambdaPhp\Bootstrap\DiKeys::DUMMY_HANDLER, function (\Psr\Container\ContainerInterface $container) {
    return new Skulditskiy\LambdaPhp\Dummy\Handler(
        $container->get(\Skulditskiy\LambdaPhp\Bootstrap\DiKeys::HTTP_CLIENT)
    );
});

$dummyHandler = $container->get(\Skulditskiy\LambdaPhp\Bootstrap\DiKeys::DUMMY_HANDLER);

/**
 * "dummy" - is the first part of name of the handler. For example, you if you have handler "dummy.handler",
 * then function dummy will be called
 *
 * @param array $eventData
 * @param \Psr\Container\ContainerInterface $container
 * @return array
 */
function dummy(array $eventData, \Psr\Container\ContainerInterface $container)
{
    /** @var \Skulditskiy\LambdaPhp\Dummy\Handler $dummyHandler */
    $dummyHandler = $container->get(\Skulditskiy\LambdaPhp\Bootstrap\DiKeys::DUMMY_HANDLER);
    return $dummyHandler->execute($eventData);
}
