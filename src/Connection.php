<?php declare(strict_types = 1);

namespace Netmosfera\AmpChrome;

use Amp\CancellationTokenSource;
use Amp\Deferred;
use Amp\Loop;
use function Amp\Promise\first;
use Amp\Promise;
use Amp\Websocket\Client\Connection as WebsocketConnection;
use Amp\Websocket\Message;
use Closure;
use Exception;
use function Amp\asyncCall;
use function Amp\call;
use function is_float;
use const JSON_THROW_ON_ERROR as JTOE;

class Connection
{
    private WebsocketConnection $_connection;

    /** @var array<int, Deferred<Result>> */
    private array $_deferredResponses;

    /** @var array<int, Closure> */
    private array $_eventListeners;

    public function __construct(WebsocketConnection $connection){
        $this->_connection = $connection;
        $this->_deferredResponses = [];
        $this->_eventListeners = [];
    }

    private int $lastRequestID = 1;

    /** @return Promise<Result> */
    public function request(RequestBuilder $request): Promise{
        return call(function() use($request){
            $previewRequestID = $this->lastRequestID + 1;

            $this->lastRequestID = is_float($previewRequestID) ? 1 : $previewRequestID;

            yield $this->_connection->send($request->JSON($this->lastRequestID));

            assert(!isset($this->_deferredResponses[$this->lastRequestID]));

            $deferred = new Deferred();
            $this->_deferredResponses[$this->lastRequestID] = $deferred;
            return $deferred->promise();
        });
    }

    public function addEventListener(Closure $listener){
        $this->_eventListeners[] = $listener;
    }

    private function dispatchEvent(Event $event){
        foreach($this->_eventListeners as $eventListener){
            $eventListener($event);
        }
    }

    public function stop(){
        $this->_connection->close();
    }

    public function run(){
        $ka = Loop::repeat(1000, function(){
            // keeps connection alive... @TODO find a better solution
            $this->_connection->send('{"id": 0, "method" : "Browser.getVersion"}');
        });

        asyncCall(function() use($ka){
            RECEIVE_MESSAGE:

            $message = yield $this->_connection->receive();

            if($message === NULL){
                Loop::cancel($ka);
                return;
            }

            /** @var Message $message */
            $JSONFullMessage = yield $message->buffer();
            $fullMessage = json_decode($JSONFullMessage, TRUE, 512, JTOE);
            $isResponse = !key_exists("method", $fullMessage);

            if($isResponse && $fullMessage["id"] === 0){
                // ignore "keep alive" responses @TODO find better solution
            }elseif($isResponse){
                $result = new Result($fullMessage["result"]);
                $this->_deferredResponses[$fullMessage["id"]]->resolve($result);
            }else{
                $this->dispatchEvent(new Event($fullMessage));
            }

            goto RECEIVE_MESSAGE;
        });
    }
}
