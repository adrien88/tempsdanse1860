<?php

class cache {

  public $ELEM = [];

  public function __construct(
      $FILENAME,
      array $OPTS = [ 'dir'=>'view/cache/','timeout' => 0 ]
    ){
    $this->ELEM['FILENAME']=$FILENAME;
    $this->ELEM = array_merge($this->ELEM, $OPTS);
  }

  public function bind(array $PARAMS = []){
    if(!empty($PARAMS)):
      $this->ELEM=array_merge($this->ELEM,$PARAMS);
    endif;
  }

  // load from data
  public function load(){
    $path = $this->ELEM['dir'].$this->ELEM['FILENAME'];
    $timeout = $this->ELEM['timeout'];
    if(
      file_exists($path) && (filemtime($path)+$timeout > time())
    ){
      return file_get_contents($path);
    }
    else{
      return false;
    }
  }

  public function save($content = ''){
    $path = $this->ELEM['dir'].$this->ELEM['FILENAME'];
    return file_put_contents($path, $content);
  }

  public function delete(){
    $path = $this->ELEM['dir'].$this->ELEM['FILENAME'];
    if(file_exist($path)){
      unlink($path);
    }
  }


}
