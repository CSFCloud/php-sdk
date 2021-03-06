<?php

namespace CSFCloud\Containers;

use CSFCloud\KeyManager;
use CSFCloud\Resource;
use CSFCloud\Files\RecursiveFileListing;
use Httpful\Request;

use Exception;

class Container extends Resource {

    private $containerId;
    private $statusCache;

    public function __construct (KeyManager $km, string $id) {
        parent::__construct($km);
        $this->containerId = $id;
        $this->FetchStatus();
    }

    private function BuildUrl (string $api, array $query = []) : string {
        $query["key"] = $this->keymanager->GetServerKey();
        $url = "https://api.csfcloud.com/container/" . urlencode($this->containerId) . $api . "?" . http_build_query($query);
        //echo "URL: " . $url . PHP_EOL;
        return $url;
    }

    public function FetchStatus () {
        $request = Request::get($this->BuildUrl(""))->expectsText()->send();
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
        Request::put($this->BuildUrl(""))
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
        $response = Request::get($this->BuildUrl("/build"))->expectsJson()->send();
        if (!isset($response->body->run_id)) {
            var_dump($response->body);
            throw new Exception("Missing run_id in response");
        }
        $this->statusCache["last_log"] = $response->body->run_id;
        return $this->statusCache["last_log"];
    }

    public function Run () : string {
        $response = Request::get($this->BuildUrl("/run"))->expectsJson()->send();
        if (!isset($response->body->run_id)) {
            var_dump($response->body);
            throw new Exception("Missing run_id in response");
        }
        $this->statusCache["last_log"] = $response->body->run_id;
        return $this->statusCache["last_log"];
    }

    public function Stop () {
        Request::get($this->BuildUrl("/stop"))->expectsJson()->send();
        $this->statusCache["last_log"] = null;
    }

    public function Delete () {
        Request::delete($this->BuildUrl(""))->send();
    }

    private function GetFileUrl(string $name) : string {
        return $this->BuildUrl("/files", [
            "filename" => $name
        ]);
    }

    public function UploadFile (string $file, string $name) {
        Request::post($this->GetFileUrl($name))->body(file_get_contents($file))->send();
    }

    public function DownloadFile (string $name, string $file) {
        shell_exec("wget " . escapeshellarg($this->GetFileUrl($name)) . " -O " . escapeshellarg($file));
    }

    public function GetFileContents (string $name) : string {
        $response = Request::get($this->GetFileUrl($name))->send();
        return $response->body;
    }

    public function DeleteFile (string $name) {
        $url = $this->BuildUrl("/files", [
            "filename" => $name
        ]);

        Request::delete($url)->send();
    }

    public function UploadDirectory (string $localdir, string $remotedir) {
        $this->DeleteFile($remotedir);

        $localdir = realpath($localdir);
        $filelister = new RecursiveFileListing($localdir);
        $files = $filelister->scan();

        foreach ($files as $file) {
            $relativefile = str_replace($localdir, "", $file);
            $this->UploadFile($relativefile, $file);
        }
    }

}