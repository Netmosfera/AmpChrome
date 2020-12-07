<?php declare(strict_types = 1);

namespace Netmosfera\AmpChromeTests\Page;

use Netmosfera\AmpChrome\Browser\Browser;
use Netmosfera\AmpChrome\Connection;
use Netmosfera\AmpChromeTests\AmpChromeTest;

class SimpleTest extends AmpChromeTest
{
    public function testSomething(){
        $connection = yield $this->chromeConnection();
        /** @var Connection $connection */
        $connection->run();

        // rather than failing the test, it keeps going forever
        self::assertSame("foo", "bar");
        // if you comment ^ this line, it will work correctly

        $browser = new Browser($connection);
        yield $browser->close();

        $connection->stop();
        $this->_chromeProcess = NULL;
    }
}
