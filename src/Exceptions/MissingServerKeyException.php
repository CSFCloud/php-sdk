<?php

namespace CSFCloud\Exceptions;

use CSFCloud\Exceptions\CSFCloudException;

class MissingServerKeyException extends CSFCloudException {

    public function __construct() {
        parent::__construct("Missing server key");
    }

}