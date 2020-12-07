<?php declare(strict_types = 1);

namespace Netmosfera\AmpChromeTests;

use RecursiveDirectoryIterator as RDI;
use RecursiveIteratorIterator as RII;

function emptyDirectory(string $directory){
    $deleteFiles = new RII(new RDI($directory, RDI::SKIP_DOTS), RII::CHILD_FIRST);
    foreach ($deleteFiles as $deleteFile) {
        $todo = $deleteFile->isDir() ? 'rmdir' : 'unlink';
        $todo($deleteFile->getPathname());
    }
}
