<?php

namespace CSFCloud;

use CSFCloud\Exceptions\MissingServerKeyException;

class KeyManager {

    private $server_key = null;
    private $client_id = null;
    private $client_secret = null;

    public function __construct (array $options) {
        $options = array_merge([
            "key" => null,
            "client_id" => null,
            "client_secret" => null
        ], $options);

        $this->server_key = $options["key"];
        $this->client_id = $options["client_id"];
        $this->client_secret = $options["client_secret"];
    }

    public function GetServerKey () : string {
        if (!$this->server_key) {
            throw new MissingServerKeyException();
        }
        return $this->server_key;
    }

}