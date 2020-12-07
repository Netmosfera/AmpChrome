<?php

namespace Netmosfera\AmpChrome;

class Result
{
    /** @var array<string, bool|int|float|string|NULL> */
    private array $_result;

    /**
     * @param array<string, bool|int|float|string|NULL> $result
     */
    public function __construct(array $result){
        $this->_result = $result;
    }

    public function data(): array{
        return $this->_result;
    }
}
