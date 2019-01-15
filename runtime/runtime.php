<?php

do {
    $request = getNextRequest();
    $handlerFunction = explode('.', $_ENV['_HANDLER'])[0];
    require_once $_ENV['LAMBDA_TASK_ROOT'] . '/src/index.php';
    $response = $handlerFunction($request['payload'], $container);
    sendResponse($request['invocationId'], $response);
} while (true);

function sendResponse($invocationId, $response)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://' . $_ENV['AWS_LAMBDA_RUNTIME_API'] . '/2018-06-01/runtime/invocation/' . $invocationId . '/response');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($response));
    curl_exec($curl);
}

function getNextRequest()
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://' . $_ENV['AWS_LAMBDA_RUNTIME_API'] . '/2018-06-01/runtime/invocation/next');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);

    $response = curl_exec($curl);

    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

    $invocationId = '';
    $headers = explode("\n", substr($response, 0, $headerSize));

    foreach ($headers as $header) {
        $headerData = explode(':', $header);
        if ($headerData[0] === 'Lambda-Runtime-Aws-Request-Id') {
            $invocationId = trim($headerData[1]);
        }
    }

    $body = substr($response, $headerSize);

    return [
        'invocationId' => $invocationId,
        'payload' => json_decode($body, JSON_OBJECT_AS_ARRAY)
    ];
}
