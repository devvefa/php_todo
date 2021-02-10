<?php


class Config
{
    public $appName = 'Todo';
    protected $appVersion = 'v1.0';

    public function setConf()
    {

    }

    public function getConf()
    {

    }

}

class  Dbconfig extends Config
{

    private $dbPath = 'data'.DIRECTORY_SEPARATOR;
    private $dbFile;

    public function __construct( string $dbFile)
    {
        $this->dbFile = $dbFile;
        $dirChack=is_dir($this->dbPath);
        $fileCheck = file_exists($this->dbPath . $this->dbFile . '.json');
        if (!$dirChack){
            $this->dirCreate();
        }
        if (!$fileCheck){
            $this->dbCreate($dbFile);
        }
    }

    private function dirCreate(): bool
    {
        return mkdir($this->dbPath);
    }
    private function dbCreate(string $dbName):bool {
        // '
        return  file_put_contents($this->dbPath.$dbName.'.json',json_encode([]));
    }
    public function getDbFile(): string {
        return $this->dbPath.$this->dbFile.'.json';
    }

}