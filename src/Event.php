<?php declare(strict_types = 1);

namespace Netmosfera\AmpChrome;

class Event
{
    private array $_rawEvent;

    public function __construct(array $rawEvent){
        $this->_rawEvent = $rawEvent;
    }

    public function name(): string{
        return $this->_rawEvent["method"];
    }

    public function parameters(): array{
        return $this->_rawEvent["params"];
    }
}
