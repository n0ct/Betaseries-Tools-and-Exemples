<?php 
//coding: utf-8  (Scite configuration)

/**
* Classe cache
* classe de gestion de cache
* 
* Exemple d'utilisation :
* <code>
* <?php
* include('path/cache.class.php');
* try{
*       $cache=new cache($cache_name);
*       $cache->initCache(3600);
* } catch (Exeption $e){
*       echo $e->getMessage();
* }
* 
* 
* //script de la page
*
*
* ?>
* </code>
* 
*
*
* @author Joris Mulliez
* @package Cache
*/ 
class cache {


        /**
        *  Extension des fichiers
        */
        const EXTENSION='.tmp';

        /**
        *  Dossier où l'on stocke les fichiers de cache
        */
        private $dossier='./cache/';

        /**
        *  Variable contenant le nom du fichier
        */
        private $file=null;


        /**
        * cache::__construct
        *
        * Initialise la variable $this->file et calcul le realpath de $this->dossier
        * 
        * @access   public
        * @param    string    $file           nom du fichier de cache
        * @param    bool      $cache_date     ajouter ou non la date en fin de fichier cache
        * @return   none  
        */
        public function __construct($file='',$path=''){
                if(empty($file)){
                        $this->file=$this->clearUri($_SERVER['REQUEST_URI']);
                } else {
                        $this->file=$this->clearUri($file);
                }

                if(!empty($path) && is_dir($path)){
                        $this->dossier=realpath($path);
                } else {
                        throw new Exception('Le dossier '.$path.' n\'existe pas.');
                }
        }

        /**
        * cache::clearUri
        *
        * Réduit la valeur de $uri aux simples caractères autorisé
        *
        * @access   private
        * @param    string    $uri     url du fichier à simplifier
        * @return   string  
        */
        private function clearUri($uri){
                return preg_replace('#[^a-zA-Z0-9_-]#','',$uri);
        }

        /**
        * cache::initCache
        *
        * Si le fichier de cache correspondant à $this->file existe et n'est pas périmé on l'inclu, et on arrête tout
        * sinon on démarre la temporisation...
        *
        * @access   public
        * @param    mixed  $time  temps de validité du fichier de cache
        * @param    string  $mod   type de comparaison de temps
        * @return   none  
        */
        public function initCache($time=0){
                if(file_exists($this->dossier.'/'.$this->file.self::EXTENSION)){
                        if(is_numeric($time) && $time >= 0){
                                if($time==0 || (time()-filemtime($this->dossier.'/'.$this->file.self::EXTENSION))<$time){
                                        readfile($this->dossier.'/'.$this->file.self::EXTENSION);
                                        exit();
                                } else {
                                        ob_start(array($this, 'ob_end'));
                                }
                        } elseif($time=='ONEDAY'){
                                if(date('Ymd',filemtime($this->dossier.'/'.$this->file.self::EXTENSION))==date('Ymd')){
                                        readfile($this->dossier.'/'.$this->file.self::EXTENSION);
                                        exit();
                                } else {
                                        ob_start(array($this, 'ob_end'));
                                }
                        }
                } else {
                        ob_start(array($this, 'ob_end'));
                }
        }

        /**
        * cache::clearCache
        * supprime le contenu du dossier $dir
        *
        * @access   public
        * @param    string    $dir     nom du dossier de cache
        * @return   none  
        */
        static function clearCache($dir){
                if(is_dir($dir)){
                        $d= dir($dir);
                        while (false !== ($entry = $d->read())) {
                                if($entry!=='..' && $entry !=='.'){
                                        if(!unlink($dir.$entry)){
                                                throw new Exception('Le fichier suivant '.$dir.$entry.' n\'a pas pu être supprimé.');
                                        }
                                }
                        }
                } else {
                        throw new Exception('Le dossier'.$dir.'n\'existe pas.');
                }
        }
        
        /**
        * cache::clearFileCache
        * supprime le fichier de cache, si le caractère * est trouvé dans l'url,
        * fonctionnera comme un masque, exemple photos_*.html supprimera tout les
        * fichiers photos_1.html,photos_2.html, etc...
        *
        * @access   public
        * @param    string    $file     nom du fichier de cache
        * @return   none  
        */
        public function clearFileCache($file){
                if(strpos($file,'*')!==false){
                        $files=glob($this->dossier.'/'.$file.self::EXTENSION);
                } else {
                        $files=array($this->dossier.'/'.$file.self::EXTENSION);
                }
                foreach($files as $file){
                        if(file_exists($file)){
                                if(!unlink($file)){
                                        throw new Exception('Le fichier suivant '.$file.' n\'a pas pu être supprimé.');
                                }
                        }
                }
        }

        /**
        * cache::ob_end
        * 
        * fonction appellée à la fin de la bufferisation
        *
        * @access  public
        * @param   string    $content     contenu du buffer
        * @return  string    $content
        */
        public function ob_end($content){
                $file=$this->dossier.'/'.$this->file.self::EXTENSION;
                if(!file_put_contents($file,$content))
                        throw new Exception('Le fichier suivant '.$this->dossier.'/'.$this->file.self::EXTENSION.' n\'a pas pu être créé ou n\'est pas ouvert à l\'écriture.');

                return $content;

        } 

}
?>