<?php

namespace CSFCloud\Tests;

use PHPUnit\Framework\TestCase;
use CSFCloud\RecursiveFileListing;

use CSFCloud\KeyManager;
use CSFCloud\Exceptions\MissingServerKeyException;

final class KeyManagerTest extends TestCase {

    /**
     * @expectedException CSFCloud\Exceptions\MissingServerKeyException
     */
    public function testMissingKeyException() {
        $mgr = new KeyManager([]);
        $mgr->GetServerKey();
    }
    
    public function testExistingKey() {
        $mgr = new KeyManager([
            "key" => "mykey"
        ]);

        $this->assertEquals("mykey", $mgr->GetServerKey());
    }

}