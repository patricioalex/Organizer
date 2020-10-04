<?php

  require_once('Controller.php');
/**
 * Class com métodos que realizam o controle das ações do organizador
 */

 class Control implements Controller {
     
   private $nameDir;
   private $extension;
   private $allFiles;
   private $noExtension;

    public function __construct()
    {
      $this->setNameDir('Arquivos_');
      $this->setNoExtension('sem_extensao');
      $this->setAllFiles(array());
    }


    // public function readDir($dir){
        // if(opendir($dir)){
        //   while
        // }
    // }

    /**
     * @method nameFolder()
     *
     * @return void
     */
    public function nameFolder(){
        return $this->getNameDir().$this->getExtension();
    }


    /**
     * folderExists
     *
     * @param [type] $dir
     * @param [type] $extension
     * @return void
     */
    public function folderExists($dir, $extension){
      $this->setExtension(strtoupper($extension));   
      $this->list($dir);
      if(in_array($this->getNameDir().strtoupper($extension), $this->getAllFiles())){
          return true;
      }else{
        try {
          $this->createFolder($dir, $extension);
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
    public function createFolder($dir, $extension){
      if(mkdir($dir.$this->getNameDir().strtoupper($extension))){
        return true;
      }else{
        throw new Exception($this->message($message = 4));
      }      
    }

    public function deleteFolder($arg){

    }

  /**
   * list
   *
   * @param [type] $dir
   * @return void
   */
    public function list($dir){
      if(opendir($dir)){
        while($file = readdir()){
          $all[] = $file;
        } 
        closedir();
        $this->setAllFiles($all);
        return true;
      }else{
        return false;
      }
    }
    
    /**
     * check
     *
     * @param [type] $dir
     * @param [type] $file
     * @return void
     */
    public function check($dir, $file){
        if(is_dir($dir.$file)){
          return true;
        }else{
          return false;
        }
    }

    public function noExtension($dir, $file){
      if(!$this->check($dir, $file)){
        if($this->folderExists($dir, $this->getNoExtension())){
          rename($dir.$file, $dir.$this->nameFolder().'/'.$file);
        }
      }   
    }
    
    public function rollback($arg){

    }

    public function message($status){

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
   public function getNameDir()
   {
      return $this->nameDir;
   }

   /**
    * setNameDir
    *
    * @param [type] $nameDir
    * @return void
    */
   public function setNameDir($nameDir)
   {
      $this->nameDir = $nameDir;

   }

   /**
    * Get the value of extension
    */ 
   public function getExtension()
   {
      return $this->extension;
   }

   /**
    * setExtension
    *
    * @param [type] $extension
    * @return void
    */
   public function setExtension($extension)
   {
      $this->extension = $extension;
   }

   /**
    * Get the value of allFiles
    */ 
   public function getAllFiles()
   {
      return $this->allFiles;
   }

   /**
    * Set the value of allFiles
    *
    * @return  void
    */ 
   public function setAllFiles($allFiles)
   {
      $this->allFiles = $allFiles;
   }

   /**
    * Get the value of noExtension
    */ 
   public function getNoExtension()
   {
      return $this->noExtension;
   }

  /**
   * Undocumented function
   *
   * @param [type] $noExtension
   * @return void
   */
   public function setNoExtension($noExtension)
   {
      $this->noExtension = $noExtension;
   }
 }