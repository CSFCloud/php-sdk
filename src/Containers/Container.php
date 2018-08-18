<?php

namespace CSFCloud\Containers;

use CSFCloud\KeyManager;
use CSFCloud\Resource;
use Httpful\Request;

class Container extends Resource {

    private $containerId;
    private $statusCache;

    public function __construct (KeyManager $km, string $id) {
        parent::__construct($km);
        $this->containerId = $id;
        $this->UpdateStatus();
    }

    private function UpdateStatus () {
        $request = Request::get("https://api.csfcloud.com/container/" . urlencode($this->containerId) . "?key=" . urlencode($this->keymanager->GetServerKey()))->expectsText()->send();
        $this->statusCache = json_decode($request->body, true);
    }

    public function GetId () : string {
        return $this->containerId;
    }

    public function GetConfiguration () : array {
        return $this->statusCache["configuration"];
    }

    public function SetConfiguration (array $cnf) {
        $this->statusCache["configuration"] = $cnf;
    }

    public function UpdateChanges () {
        Request::put("https://api.csfcloud.com/container/" . urlencode($this->containerId) . "?key=" . urlencode($this->keymanager->GetServerKey()))
            ->sendsJson()->body(json_encode($this->statusCache["configuration"]))->send();
    }

    public function GetContainerName () : string {
        return $this->statusCache["configuration"]["name"];
    }

    public function SetContainerName (string $newname) {
        $this->statusCache["configuration"]["name"] = $newname;
    }

    public function IsRunning () : bool {
        return $this->statusCache["running"];
    }

    public function GetLastLogId () : ?string {
        return $this->statusCache["last_log"];
    }

    public function GetLastLogWebSocketUrl () : string {
        return "wss://dashboard-logs.csfcloud.com/log?id=" . $this->statusCache["last_log"];
    }

    public function Build () : string {
        $response = Request::get("https://api.csfcloud.com/container/" . urlencode($this->containerId) . "/build?key=" . urlencode($this->keymanager->GetServerKey()))->expectsJson()->send();
        $this->statusCache["last_log"] = $response->body->run_id;
        return $this->statusCache["last_log"];
    }

    public function Run () : string {
        $response = Request::get("https://api.csfcloud.com/container/" . urlencode($this->containerId) . "/run?key=" . urlencode($this->keymanager->GetServerKey()))->expectsJson()->send();
        $this->statusCache["last_log"] = $response->body->run_id;
        return $this->statusCache["last_log"];
    }

    public function Delete () {
        Request::delete("https://api.csfcloud.com/container/" . urlencode($this->containerId) . "?key=" . urlencode($this->keymanager->GetServerKey()))->send();
    }

}