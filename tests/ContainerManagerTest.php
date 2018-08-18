<?php

namespace CSFCloud\Tests;

use PHPUnit\Framework\TestCase;
use CSFCloud\RecursiveFileListing;

use CSFCloud\KeyManager;
use CSFCloud\Containers\Manager as ContainerManager;

final class ContainerManagerTest extends TestCase {

    public function testContainerListing() {
        $keymgr = new KeyManager([
            "key" => "fQJO9bjlVXnUZv0sCaQA90QsJ0lPvs1o"
        ]);
        $cntmgr = new ContainerManager($keymgr);

        $containers = $cntmgr->ListContainers();
    }

}