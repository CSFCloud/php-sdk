<?php

namespace CSFCloud\Tests;

use PHPUnit\Framework\TestCase;

use CSFCloud\KeyManager;
use CSFCloud\Containers\Manager as ContainerManager;

final class ContainerManagerTest extends TestCase {

    public function testContainerListing() {
        $keymgr = new KeyManager([
            "key" => "fQJO9bjlVXnUZv0sCaQA90QsJ0lPvs1o"
        ]);
        $cntmgr = new ContainerManager($keymgr);

        $containers = $cntmgr->ListContainers();

        $this->assertEquals(true, is_array($containers));

        foreach ($containers as $cnt) {
            $this->assertEquals(true, is_string($cnt["id"]));
            $this->assertEquals(true, is_string($cnt["name"]));
        }
    }

}