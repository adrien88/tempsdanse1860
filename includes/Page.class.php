<?php

class Page {

    private $timeout = 0;
    private static $DataInstances = [];

    private $PageContent = [
        'url' => '',
        'content' => '',
    ];

    private $PageMeta= [
        'url' => '',
        'draft' => 0,
        'title' => '',
        'content' => '',
        'thumbnail' => '',
        'desc' => '',
        'author' => '',
        'ctime' => '',
        'mtime' => '',
    ];

    private $PageCache = [
        'timeout' => 0,
        'content' => '',
    ];

    public function __construct(string $url = 'defaut')
    {
        $this->setUrl($url);
    }

    public function setMeta(array $meta = []){
        $this->PageMeta = array_merge($this->PageMeta, $meta);
    }

    public function unsetMeta(string $meta){
        unset($this->PageMeta[$meta]);
    }

    public function setContent(string $content){
        $this->PageContent['content'] = $content;
    }

    public function setUrl(string $url){
        $this->PageContent['url'] = $url;
        $this->setMeta(['url' => $url]);
        $this->PageCache['url'] = $url;
    }

    public static function getInstance(string $instance){
        if(!isset(self::$DataInstances[$instance])){
            self::$DataInstances[$instance] = new Data($instance);
        }
        return self::$DataInstances[$instance];
    }

    public static function load (string $url) : void
    {
        if(self::getInstance('Pages_content')->isset($url)){
            $this->PageContent = self::getInstance('Pages_content')->get($url);
        }
        if(self::getInstance('Pages_meta')->isset($url)){
            $this->PageMeta = self::getInstance('Pages_meta')->get($url);
        }
    }

    public function save () : void
    {
        $url = $this->PageMeta['url'];
        self::getInstance('Pages_content')->set($url, serialize($this->PageContent));
        self::getInstance('Pages_meta')->set($url, serialize($this->PageMeta));
        self::getInstance('Pages_content')->save();
        self::getInstance('Pages_meta')->save();
    }




    public static function run() : void
    {
        self::getInstance('Pages_cache');
        $PAGE = '';
        if(self::getInstance('Pages_cache')->isset($_GET['page'])){
            $page=unserialize(self::getInstance('Pages_cache')->get($_GET['page']));
            if ($page['timeout'] >= time()){
                echo $page['content'];
            }
        }
        else {
            
        }
        if ($_GET['page']){
            if(self::getInstance('Pages_meta')->isset($_GET['page'])){
                $PAGE_META = unserialize(self::getInstance('Pages_meta')->get($_GET['page']));
                if ($PAGE_META['draft'] == 0){
                    if(self::getInstance('Pages_content')->isset($_GET['page'])){
                        $PAGE = self::getInstance('Pages_content')->get($_GET['page']);
                    }
                }
                else {
                    $PAGE = self::getInstance('Pages_content')->get('403.html');
                }
            }
            else {
                $PAGE = self::getInstance('Pages_content')->get('404.html');
            }
        }
        $echo = unserialize($PAGE);
        echo $echo['content'];

    }
}
