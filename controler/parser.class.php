<?php
  class parser{


    private $ELEM = [];
    private $PARAMS = [];

    public function __construct(){

    }

    /*
       Automatique assembly mod :
       @folder : assembly automaticaly
    */
    public static function auto($folder,array $data,array $opts){
      $tpl=self::assembler($folder.'/index.tpl', [
        'DIR'=>$folder,
        'clean'=>null
        ]);
      // print_r($tpl);
      if($tpl !== false){
        return self::parserHTML($tpl, $data, $opts);
      }
    }

    /*
        BINDING parameter
    */
    public function bind($parametre,$value) {
      $this->PARAMS[$parametre]=$value;
    }

    /*
        ASSEMBLING
    */
    public static function assembler($tpl, array $opts){
      extract($opts);

      if(file_exists($tpl)){
        $tpl=file_get_contents($tpl);
      }
      $out=$tpl;
      preg_match_all('#%([a-z0-9._-]/?)+#i',$tpl,$matches);
      // if matches
      if(isset($matches) && !empty($matches)){
        // browse matches
        foreach($matches[0] as $patt){
          $path = $DIR.substr($patt,1);

          if(file_exists($path))
          {
            $loaded = self::assembler($path,$opts);
            $out = str_replace($patt,$loaded,$out);
          }
        }
        return $out;
      }
    }



    public static function parserHTML($tpl, array $data, array $opts){
      extract($opts);


      // get all occurences
      preg_match_all('#\$[a-z0-9_-]+#i',$tpl,$matches);
      // echo ' <pre> '.print_r($matches,1).'</pre>';

      if(isset($matches) && !empty($matches)){
        //  browse matches
        foreach($matches[0] as $patt){
          $key = substr($patt,1);
          // replace content
          if(isset($data[$key])){
            $tpl=str_replace($patt,$data[$key],$tpl);
          }
          // cleanning the hood
          elseif(isset($clean)){
            $tpl=str_replace($patt,'',$tpl);
          }
        }
      }

      return $tpl;
    }

    public static function parserFUNC($tpl, array $data, array $opts){
      extract($opts);

      // get all occurences
      preg_match_all('#\#[a-z0-9_-](\(([a-z0-9_-]+,?)?\))+#i',$tpl,$matches);

      if(isset($matches) && !empty($matches)){
        //  browse matches
        foreach($matches[0] as $patt){
          $patt = substr($patt,1);
          // execute content
          if(
            ($fname=preg_replace('#([a-z0-9_-]+)(\(([a-z0-9_-]+,?)?\))+#i','$1',$patt))
            && function_exists($fname)
          ){
            $tpl=exec($patt);
          }
          // cleanning the hood
          elseif(isset($clean)){
            $tpl=str_replace($patt,'',$tpl);
          }
        }
      }

      return $tpl;
    }







    //
    //
    // public static function auto($tplfile, array $data, array $opts){
    //
    //   if (isset($opts)){
    //     extract($opts);
    //   }
    //
    //   $tpl='';
    //   if(file_exists($tplfile))
    //   {
    //     $tpl=file_get_contents($tplfile);
    //     $dir=dirname($tplfile).'/';
    //
    //     preg_match_all('#\$[a-z0-9_-]+#i',$tpl,$matches);
    //     // echo ' <pre> '.print_r($matches,1).'</pre>';
    //
    //     if(isset($matches) && !empty($matches)){
    //       foreach($matches[0] as $patt){
    //         $key = substr($patt,1);
    //
    //         if(isset($data[$key])){
    //           if(file_exists($dir.$data[$key]) && is_file($dir.$data[$key])){
    //             $subtpl=self::auto($dir.$data[$key],$data,$opts);
    //             $tpl=str_replace($patt,$subtpl,$tpl);
    //           }
    //           else{
    //             $tpl=str_replace($patt,$data[$key],$tpl);
    //           }
    //         }
    //
    //         elseif(isset($clean)){
    //           $tpl=str_replace($patt,'',$tpl);
    //         }
    //
    //
    //       }
    //     }
    //   }
    //   return $tpl;
    // }
  }

 ?>
