<?php

namespace Netmosfera\AmpChrome\Process;

use Amp\Process\Process;
use Amp\Promise;
use function Amp\call;

function extractWebsocketURLFromProcess(Process $chromeProcess): Promise{
    return call(function() use($chromeProcess){
        $regExp = '/DevTools listening on (ws:\/\/.*)\r\n/';
        while(TRUE){
            $chunk = yield $chromeProcess->getStderr()->read();
            if(preg_match($regExp, $chunk, $matches) === 1){
                return $matches[1];
            }
        }
    });
}
