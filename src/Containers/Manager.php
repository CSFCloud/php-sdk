<?php

namespace CSFCloud\Containers;

use CSFCloud\KeyManager;
use CSFCloud\Resource;
use CSFCloud\Containers\Container;
use Httpful\Request;

class Manager extends Resource {

    public function ListContainers() : array {
        $request = Request::get("https://api.csfcloud.com/container?key=" . urlencode($this->keymanager->GetServerKey()))->expectsText()->send();
        $cntlist = json_decode($request->body, true);
        return $cntlist;
    }

    public function GetContainer(string $id) : Container {
        return new Container($this->keymanager, $id);
    }

}