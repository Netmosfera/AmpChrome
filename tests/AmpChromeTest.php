<?php declare(strict_types = 1);

namespace Netmosfera\AmpChromeTests;

use Amp\PHPUnit\AsyncTestCase;
use Amp\Process\Process;
use Amp\Promise;
use Amp\Websocket\Client\Connection as WebsocketConnection;
use Exception;
use Netmosfera\AmpChrome\Connection;
use Netmosfera\AmpChrome\Process\DevSettings;
use function Amp\asyncCall;
use function Amp\call;
use function Amp\Websocket\Client\connect;
use function Netmosfera\AmpChrome\Process\chromeCommandString;
use function Netmosfera\AmpChrome\Process\extractWebsocketURLFromProcess;

abstract class AmpChromeTest extends AsyncTestCase
{
    protected ?Process $_serverProcess = NULL;

    protected string $_chromeProcessProfileDir = __DIR__ . "/../tmp/tests_profile";

    protected ?Process $_chromeProcess = NULL;

    /** @return Promise<void> */
    protected function runServer(string $CWD): Promise{
        return call(function() use($CWD){
            $command = PHP_BINARY . " -S localhost:8000";
            $this->_serverProcess = new Process($command, $CWD);
            yield $this->_serverProcess->start();
            asyncCall(function(){
                while($this->_serverProcess !== NULL){
                    echo yield $this->_serverProcess->getStdout()->read();
                }
            });
            asyncCall(function(){
                while($this->_serverProcess !== NULL){
                    echo yield $this->_serverProcess->getStderr()->read();
                }
            });
        });
    }

    /** @return Promise<Connection> */
    protected function chromeConnection(): Promise{
        return call(function(){
            if($this->_chromeProcess !== NULL){
                throw new Exception("The previous Chrome process is still running");
            }

            @mkdir($this->_chromeProcessProfileDir);
            emptyDirectory($this->_chromeProcessProfileDir);

            $chromeBinary = "C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe";
            $chromeOptions = DevSettings::get();
            $chromeOptions["user-data-dir"] = $this->_chromeProcessProfileDir;
            $chromeCommand = chromeCommandString($chromeBinary, $chromeOptions);

            $this->_chromeProcess = new Process($chromeCommand);
            yield $this->_chromeProcess->start();
            $websocketURL = yield extractWebsocketURLFromProcess($this->_chromeProcess);

            $websocketConnection = yield connect($websocketURL);
            /** @var WebsocketConnection $websocketConnection */
            return new Connection($websocketConnection);
        });
    }

    public function tearDownAsync(){
        echo "KILLED";
        $this->_chromeProcess = NULL;
        $this->_serverProcess = NULL;
    }
}
