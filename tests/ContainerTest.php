<?php

namespace CSFCloud\Tests;

use PHPUnit\Framework\TestCase;

use CSFCloud\KeyManager;
use CSFCloud\Containers\Manager as ContainerManager;

final class ContainerManagerTest extends TestCase {

    public function testContainerFunctions() {
        $keymgr = new KeyManager([
            "key" => "fQJO9bjlVXnUZv0sCaQA90QsJ0lPvs1o"
        ]);
        $cntmgr = new ContainerManager($keymgr);

        $container = $cntmgr->NewContainer();
        $this->assertEquals("<< Name >>", $container->GetContainerName());

        $container->SetContainerName("New name");
        $container->UpdateChanges();
        $container->FetchStatus();
        $this->assertEquals("New name", $container->GetContainerName());
        
        $container->Delete();
    }

}