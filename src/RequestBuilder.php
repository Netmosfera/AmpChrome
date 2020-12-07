<?php

namespace Netmosfera\AmpChrome;

/**
 * JSON-RPC Request builder inclusive of the CDP-specific `sessionId` field.
 */
class RequestBuilder
{
    private string $_method;

    private ?string $_sessionID;

    /** @var array<string, bool|int|float|string> */
    private array $_parameters;

    /**
     * @param       string $method
     * @TODOC
     *
     * @param       string|NULL $sessionID
     * @TODOC
     */
    public function __construct(string $method, ?string $sessionID = NULL){
        $this->_method = $method;
        $this->_sessionID = $sessionID;
        $this->_parameters = [];
    }

    public function set(string $name, $value){
        if($value === NULL){
            unset($this->_parameters[$name]);
        }else{
            $this->_parameters[$name] = $value;
        }
    }

    public function JSON(int $requestID): string{
        $request["id"] = $requestID;

        $request["method"] = $this->_method;

        if($this->_parameters !== []){
            $request["params"] = $this->_parameters;
        }

        if($this->_sessionID !== NULL){
            $request["sessionId"] = $this->_sessionID;
        }

        return json_encode($request);
    }
}
