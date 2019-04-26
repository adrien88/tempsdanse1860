<?php

class cache {

  public $ELEM = [];

  public function __construct(
    $FILENAME,
    array $OPTS=array(
      'DIR'=>'view/cache/',
      'TIMEOUT'=>3600
    )
  ){
    extract($OPTS);
    $this->ELEM['FILENAME']=$FILENAME;
    $this->ELEM['TIMEOUT']=$TIMEOUT;
    $this->ELEM['DIR']=$DIR;
  }

  public bind(array $PARAMS = []){
    if(!empty($PARAMS)):
      $this->ELEM=array_merge($this->ELEM,$PARAMS);
    endif;
  }

  // load from data
  public function load(){
    $path = $this->ELEM['DIR'].$this->ELEM['FILENAME'];
    $timeout = $this->ELEM['TIMEOUT'];
    if(
      file_exists($path) && filemtime($path+$timeout) < time()
    ){
      return file_get_contents($path);
    }
    else{
      return false;
    }
  }

  public function save($content = ''){
    $path = $this->ELEM['DIR'].$this->ELEM['FILENAME'];
    return file_put_contents($path, $content);
  }

  public function delete(){
    $path = $this->ELEM['DIR'].$this->ELEM['FILENAME'];
    if(file_exist($path)){
      unlink($path);
    }
  }


}
