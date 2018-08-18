<?php

namespace CSFCloud;

use CSFCloud\KeyManager;

class Resource {

    protected $keymanager;

    public function __construct(KeyManager $km) {
        $this->keymanager = $km;
    }

}