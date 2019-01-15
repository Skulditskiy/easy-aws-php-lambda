<?php

namespace Skulditskiy\LambdaPhp;

interface HandlerInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array;
}
