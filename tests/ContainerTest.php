<?php

namespace CSFCloud\Tests;

use PHPUnit\Framework\TestCase;

use CSFCloud\KeyManager;
use CSFCloud\Containers\Manager as ContainerManager;

final class ContainerTest extends TestCase {

    public function testContainerFunctions() {
        $keymgr = new KeyManager([
            "key" => "fQJO9bjlVXnUZv0sCaQA90QsJ0lPvs1o"
        ]);
        $cntmgr = new ContainerManager($keymgr);

        $container = $cntmgr->NewContainer();
        $newid = $container->GetId();

        $containerlist = $cntmgr->ListContainers();
        $found = false;
        foreach ($containerlist as $c) {
            $found |= $c["id"] == $newid;
        }
        $this->assertEquals(true, $found);

        $this->assertEquals("<< Name >>", $container->GetContainerName());

        $container->SetContainerName("New name");
        $container->UpdateChanges();
        $container->FetchStatus();
        $this->assertEquals("New name", $container->GetContainerName());

        $demofile = __DIR__ . "/demofile.txt";
        $container->UploadFile($demofile, "testfile.txt");
        $contents = $container->GetFileContents("testfile.txt");
        $this->assertEquals(file_get_contents($demofile), $contents);
        
        $container->Delete();
    }

}