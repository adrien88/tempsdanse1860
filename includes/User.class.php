<?php

session_start();

class User {

    private static $USER = null;
    private $data = [];

    public function __construct(){

        self::$USER = new Data('users');

        if (!$this->isLogged()){
            $_SESSION['UID'] = 0;
            $_SESSION['logintry'] = 3;
            $_SESSION['login'] = 'guest';
        }
    }


    public function unconnect() : void
    {
        unset($_SESSION);
        header('location:./');
        exit;
    }

    public function connect($login,$passwd) : void
    {
        $return = [
            'mssg' => '',
            'logintry' => $_SESSION['logintry'],
            'statut' => 0
        ];

        if($_SESSION['logintry'] > 0){
            if(self::$USER->isset($login)){
                $data = unserialize(self::$USER->get($login));
                if($data['passwd'] == crypt($passwd, md5($data['mail']))){
                    $return['mssg'] = 'Bienvenue '.$login.' !';
                    $return['statut'] = 1;
                }
                else {
                    $return['mssg'] = 'Mauvais mot de passe.';
                    $_SESSION['logintry']--;
                }
            }
            else {
                $return['mssg'] = 'Cet utilisateur n\'existe pas.';
                $_SESSION['logintry']--;
            }
        }
        else {
            $return['mssg'] = 'Rejet automatique, patientez 30 minutes et ré-essayez.';
        }
        echo json_encode($return);
    }


    public function edit(string $login,string $passwd,string $email) : void
    {
        $return = [
            'mssg' => '',
            'statut' => 0
        ];

        self::$USER->set($login, serialize([
            'login'=>$login,
            'passwd'=>crypt($passwd, md5($email)),
            'email'=>$email,
        ]));
        self::$USER->save();
        echo json_encode($return);
    }


    public function isLogged(){
        if (!isset($_SESSION['UID']) or $_SESSION['UID'] == 0){
            return false;
        }
        return true;
    }





}
