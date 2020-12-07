<?php declare(strict_types = 1);

namespace Netmosfera\AmpChrome\Browser;

use Amp\Promise;
use Netmosfera\AmpChrome\Connection;
use Netmosfera\AmpChrome\RequestBuilder;
use function Amp\call;

/**
 * @TODOC
 */
class Browser
{
    private Connection $_connection;

    /**
     * @param       Connection $connection
     * @TODOC
     */
    public function __construct(Connection $connection){
        $this->_connection = $connection;
    }

    public function version(): Promise{
        $request = new RequestBuilder("Browser.getVersion");
        return call(function() use($request){
            yield $this->_connection->request($request);
            // @TODO
        });
    }

    public function close(): Promise{

        //@TODO
        $request = new RequestBuilder("Browser.close");
        return call(function() use($request){
            yield $this->_connection->request($request);
        });
    }
}
