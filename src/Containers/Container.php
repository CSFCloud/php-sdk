<?php

namespace CSFCloud\Containers;

use CSFCloud\KeyManager;
use CSFCloud\Resource;
use Httpful\Request;

class Container extends Resource {

    private $containerId;
    private $statusCache;

    public function __construct(KeyManager $km, string $id) {
        parent::__construct($km);
        $this->containerId = $id;
        $this->UpdateStatus();
    }

    private function UpdateStatus() {
        $request = Request::get("https://api.csfcloud.com/container/" . urlencode($this->containerId) . "?key=" . urlencode($this->keymanager->GetServerKey()))->send();
        $this->statusCache = json_decode($request->body, true);
    }

    public function GetConfiguration() : array {
        return $this->statusCache["configuration"];
    }

    public function SetConfiguration(array $cnf) {
        $this->statusCache["configuration"] = $cnf;
    }

    public function UpdateChanges() {
        Request::put("https://api.csfcloud.com/container/" . urlencode($this->containerId) . "?key=" . urlencode($this->keymanager->GetServerKey()))
            ->sendsJson()->body(json_encode($this->statusCache["configuration"]))->send();
    }

    public function GetContainerName() : string {
        return $this->statusCache["configuration"]["name"];
    }

    public function SetContainerName(string $newname) {
        $this->statusCache["configuration"]["name"] = $newname;
    }

}