<?php

/**
 * Class com métodos que realizam o controle das ações do organizador
 */

 class Control {
     
   private static $nameDir;
   private static $extension;

    public function __construct()
    {
      self::setNameDir('Arquivos_');
    }

    /**
     * @method nameFolder()
     *
     * @return void
     */
    public static function nameFolder(){
        return self::getNameDir().self::getExtension();
    }


    /**
     * @method folderExists()
     *
     * @param array $dir
     * @param [type] $extesion
     * @return void
     */
    public static function folderExists($dir, $extension){
      self::setExtension($extension);
      // $all = array();
      $all = self::list($dir);      
      if(in_array(self::getNameDir().strtoupper($extension), $all)){
          return true;
      }else{
        try {
          self::createFolder($dir, $extension);
          return true;
        } catch (Exception $error) {
            return "Exception: " . $error->getMessage();
        }          
      }
    }
   
    /**
     * @method createFolder()
     *
     * @param [type] $dir
     * @param [type] $extesion
     * @return void
     */
    public static function createFolder($dir, $extension){
      if(mkdir($dir.self::getNameDir().strtoupper($extension))){
        return true;
      }else{
        throw new Exception(self::message($message = 4));
      }
        
        
    }

    public static function deleteFolder($arg){

    }

    /**
     * @method list()
     *
     * @param [type] $dir
     * @return void
     */
    public static function list($dir){
      // $all = array();
      opendir($dir);
        while($file = readdir()){
          $all[] = $file;
        } 
      closedir($dir);
      return $all;
    }

    public static function checkFileExtension($arg){

    }
    
    public static function rollback($arg){

    }

    public static function message($status){

      switch($status){
        case 0:
          return "Pasta(s) criada e arquivos alocados.";
        case 1: 
          return "Erro! Nenhum diretório informado. Por favor, informe um diretório.";
        case 2: 
          return "O argumento passado não é um diretório!";
        case 3:
          return "O diretório não pode ser aberto! Talvez seja permissão de pasta.";
        case 4: 
          return "O diretório não pode ser criado! Talvez seja permissão de pasta.";          
      }         
    }



   /**
    * Get the value of nameDir
    */ 
   public static function getNameDir()
   {
      return self::$nameDir;
   }

   /**
    * Set the value of nameDir
    *
    * @return  self
    */ 
   public static function setNameDir($nameDir)
   {
      self::$nameDir = $nameDir;

   }

   /**
    * Get the value of extension
    */ 
   public static function getExtension()
   {
      return self::$extension;
   }

   /**
    * Set the value of extension
    *
    * @return  self
    */ 
   public static function setExtension($extension)
   {
      self::$extension = $extension;
   }
 }