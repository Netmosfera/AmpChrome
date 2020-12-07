<?php

namespace Netmosfera\AmpChrome\Process;

function chromeCommandString(string $chromeBinaries, $chromeOptions){
    // @TODO should this be escaped? surrounded by quotes?
    $command = $chromeBinaries;
    foreach($chromeOptions as $k => $v){
        $v = $v === NULL ? "" : "=" . $v;
        $command .= " --$k$v";
    }
    return $command;
}
