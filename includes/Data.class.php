<?php

class Data {

    private $IDX = [];
    private $path = '';


    public function __construct(string $filename){

        $this->path = 'data/'.$filename;

        # try to build table
        if (!file_exists($this->path) and (!mkdir($this->path) or !is_writable($this->path))) {
            exit('Error, PHP can\'t create/read/write in <b>'.$this->path.'</b>.<br>');
        }

        if (file_exists($this->path.'/index.ini')){
            $this->IDX = parse_ini_file($this->path.'/index.ini',1);
        }
        else {
            $this->CreateIdx();
        }
    }

    public function CreateIdx() {
        foreach(glob($this->path.'/*.dat') as $file ){
            $key = str_replace('.dat','',basename($file));
            $this->IDX[$key] = [
                'path' => $file,
            ];
        }
    }

    public function get (string $key)
    {
        if(!isset($this->IDX[$key]['cache']) or empty($this->IDX[$key]['cache'])){
            $path = $this->IDX[$key]['path'];
            if ( filesize($path) < 250 ){
                $data = file_get_contents($path);
                $this->IDX[$key]['cache'] = $data;
                return $data;
            }
            else {
                if (file_exists($path)){
                    return file_get_contents($path);
                }
            }
        }
        else {
            return $this->IDX[$key]['cache'];
        }
    }


    public function isset(string $key) : bool
    {
        return isset($this->IDX[$key]);
    }

    public function setMeta (string $key, array $meta = [])
    {
        if ($this->isset($key)){
            $this->IDX[$key] = array_merge($meta, $this->IDX[$key]);
        }
    }

    public function unsetMeta (string $key, string $meta)
    {
        if ($this->isset($key)){
            unset($this->IDX[$key][$meta]);
        }
    }



    public function set (string $key, string $value = null)
    {
        if(preg_match('#[a-z0-9]#',$key))
        {
            if (strlen($value) >= 250) {
                $path = $this->path.'/'.md5($key).'.dat';
                file_put_contents($path, $value);
                $this->IDX[$key]['path'] = $path;
                unset($this->IDX[$key]['cache']);
            }
            else {
                $this->IDX[$key]['cache'] = $value;
            }
        }
    }


    public function search(array $pattern) : array
    {
        $matches=[];
        foreach($this->IDX as $key => $meta){
            foreach($pattern as $pkey => $pmeta){
                if (array_key_exists($pkey, $meta) or in_array($pmeta,$meta)){
                    $matches[$key] = [$pkey => $pmeta];
                }
            }
        }
        return $matches;
    }

    public function count() : int {
        return count($this->IDX);
    }

    public function delete (string $key) : void
    {
        if (isset($this->IDX[$key])) {
            if (isset($this->IDX[$key]['path']) && file_exists($this->IDX[$key]['path'])){
                unlink($this->IDX[$key]['path']);
            }
            unset($this->IDX[$key]);
        }
    }

    public function drop() : void
    {
        unset($this->IDX);
        foreach(glob($this->path.'/*') as $file){
            unlink($file);
        }
        rmdir($this->path);
    }


    public function save()
    {
        if (isset($this->IDX) && !empty($this->IDX)){

            foreach($this->IDX as $key => $array){
                if(isset($array['cache']) and !empty($array['cache'])){
                    $path = $this->path.'/'.md5($key).'.dat';
                    if (file_put_contents($path, $array['cache'])){
                        unset($this->IDX[$key]['cache']);
                        $this->IDX[$key]['path']=$path;
                    }
                }
            }

            save_ini_file($this->path.'/index.ini', $this->IDX);
        }
    }


}
