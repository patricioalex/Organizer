<?php

// require_once('');
/**
 * Organizador de arquivos 
 * 
 * Basta passar o caminho que contém os arquivos que ele criará uma diretório para cada tipo e moverá para seu devido lugar.   
 */


/**
 * Interface controller
 */
interface Controller {

  public function validateArgv($argv);

  public function folderExists($dir, $extension);

  public function createFolder($dir, $extension);

  // public function deleteFolder($arg);

  public function list();

  public function checkDir($dir);

  public function checkFile($dir, $file);

  public function extension($file);

  public function noExtension($dir, $file);

  public function moveFile($file);
  
  // public function rollback($arg);

}



/**
 * Class com métodos que realizam o controle das ações do organizador
 */

 class Control implements Controller {
     
  /**
   * Atributos privates
   *
   * @var [type]
   */
   private $nameDir;
   private $extension;
   private $allFiles;
   private $noExtension;
   private $dir;

    /**
     * construct
     */
    public function __construct()
    {
      $this->setNameDir('Arquivos_');
      $this->setNoExtension('SEM_EXTENSAO');
      $this->setAllFiles(array());
      $this->setDir(array());
    }

    /**
     * @method validateArgv()
     * 
     * Valida se foi passado algum argumento
     *
     * @param [type] $argv
     * @return void
     */
    public function validateArgv($argv){
      if(count($argv) >= 2){
        return true;
      }else{
        throw new Exception("Nenhum diretório informado. Por favor, informe um diretório.");
      }
    }

    /**
     * @method nameFolder()
     * 
     * Pega o nome do diretório da extensão/tipo do arquivo
     * 
     * @return void
     */
    public function nameFolder(){
        return $this->getNameDir().$this->getExtension();
    }


    /**
     * @method folderExists()
     * 
     * Verifica se exite um diretório para a extensão/tipo de arquivo.
     * Senão exitir, ele criará um.
     
     * @param [type] $dir
     * @param [type] $extension
     * @return void
     */
    public function folderExists($dir, $extension){
      $this->setExtension(strtoupper($extension));  
      if(file_exists($dir.'/'.$this->getNameDir().strtoupper($extension))){
          return true;
      }else{
        if($this->createFolder($dir, $extension)){
          return true;
        }else{
          throw new Exception("O diretório não pode ser criado! Talvez seja permissão de pasta.");
        }  
      }
    }
   
    /**
     * @method createFolder()
     *
     * Criador de diretório para extensã/Tipo de arquivo
     * 
     * @param [type] $dir
     * @param [type] $extesion
     * @return void
     */
    public function createFolder($dir, $extension){
      if(mkdir($dir.$this->getNameDir().strtoupper($extension))){
        return true;
      }else{
        return false;
      }      
    }


    /**
     * @method deleteFolder()
     * 
     * Deleta diretório
     *
     * @param [type] $arg
     * @return void
     */
    // public function deleteFolder($arg){

    // }


  /**
   * @method list()
   * 
   * Verifica e lista todos os arquivos contidos no diretório.
   * Senão tiver arquivos no diretório, a aplicação é encerrada.
   *
   * @param [type] $dir
   * @return void
   */
    public function list(){
      /**
       * Tentar abrir o dretório para listar todos os arquivos
       * Existe o método scandir() para listar diretórios
       */
      if(opendir($this->getDir())){
        $all = array();
        while($file = readdir()){
          if($file != '.' && $file != ".."){
            $hidden = substr($file, 0, 1);
            if($hidden != '.'){
              $all[] = $file;
            }
          }
        } 
        /**
         * Verifica se tem arquivos ou não no diretório
         */
        if(count($all) != 0){
          $this->setAllFiles($all);
          return true;
        }else{
          throw new Exception('Diretótio vazio!');
        }        
      }else{
        throw new Exception("O diretório não pode ser aberto! Talvez seja permissão de pasta.");
      }
      // closedir();
    }



    /**
     * @method public checkDir()
     *
     * Checa se o diretório é valído 
     * 
     * @param [type] $dir
     * @return void
     */
    public function checkDir($dir){
      if(is_dir($dir[1])){
        $this->setDir($dir[1]);
        return true;
      }else{
        throw new Exception("O argumento passado não é um diretório!");
      }
    }

    
    /**
     * @method checkFile()
     * 
     * Checa se o argumento é um arquivo ou diretório.
     * Isso ocorre quando há dúvida se é uma arquivo ou diretório que não foi achado extensão ou o tamanho da extensão é maior que 6.
     *
     * @param [type] $dir
     * @param [type] $file
     * @return void
     */
    public function checkFile($dir, $file){
        if(is_dir($dir.$file)){
          return true;
        }else{
          return false;
        }
    }

    /**
     * @method extension()
     * 
     * Valida se extiste extensão para o arquivo.
     * Verifica se o tamanho do nome da extensão é maior que 6.
     * Senão der false nas duas verificação acima, os mesmo serão verificados para saber se são diretório ou arquivo sem extensão.
     * Verifica se existe diretório para o arquivo que foi validado nas outras etapas acima.
     * 
     *
     * @param [type] $file
     * @return void
     */
    public function extension($file){
      /**
       * Cria uma array conforme as ocorrẽncias de ponto houver
       * Se tem mais de dois index, senão, séra verificado se é uma diretório ou um arquivo sem extensão
       * Pega o ultimo valor da array
       * Se é maior que 6 caracteres
       * Se existe um diretório para extensão/tipo, senão tiver, será criado e o arquivo movido
       * Retorna TRUE para continuar e mover o arquivo
       */
      $arrayFile = explode('.', $file);
      if(count($arrayFile) >= 2){
        // if($arrayFile[0] != '.'){
          $extension = end($arrayFile);
          if($extension <= 6){
            if($this->folderExists($this->getDir(), $extension)){
              return true;
            }
          }else{
            $this->noExtension($this->getDir(), $file);
          }
        // }
      }else{
        $this->noExtension($this->getDir(), $file);
      }
      // }
    }


    /**
     * @method noExtension()
     * 
     * Aciona o método checkFile para saber se é um arquivo ou diretório.
     * Verfica se o diretório sem extensão exite. Senão exitir, o será criado e moverá o respectivo arquivo.
     * Aciona o método moveFile() se retornar true na etapa anterior.
     *
     * @param [type] $dir
     * @param [type] $file
     * @return void
     */
    public function noExtension($dir, $file){
      if(!$this->checkFile($dir, $file)){
        if($this->folderExists($dir, $this->getNoExtension())){
          $this->moveFile($file);
        }
      }   
    }
    

    /**
     * @method rollback()
     * 
     * Desfaz a operação de organizar arquivos.
     *
     * @param [type] $arg
     * @return void
     */
    // public function rollback($arg){

    // }


    /**
     * @method moveFile()
     * 
     * Move arquvios para seus respectivos diretórios.
     *
     * @param [type] $file
     * @return void
     */
    public function moveFile($file){
      rename($this->getDir().'/'.$file, $this->getDir().$this->nameFolder().'/'.$file);
    }


    /**
     * Métodos especiais Getter e Setter
     */

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

    /**
    * Get the value of dir
    */ 
    public function getDir()
    {
       return $this->dir;
    }
 
    /**
     * Set the value of dir
     *
     * @return  void
     */ 
    public function setDir($dir)
    {
       $this->dir = $dir; 
    }
 }


 /**
  * Estrutura try, catch e finally que controla a execução da aplicação
  */

$control = new Control();

try {
  if($control->validateArgv($argv)){
    if($control->checkDir($argv)){
      if($control->list()){

        /**
         * Laço para verificar cada arquivo achado no diretório
         */
        foreach ($control->getAllFiles() as $file ) {
          if($control->extension($file)){
            $control->moveFile($file);
          }
        }
      }
    }  
  }  
} catch (Exception $error) {

  echo "\n Erro: " . $error->getMessage()."\n\n";

}finally{

  echo "\n Aplicação finalizada! \n\n";
  /**
   * Testes para ver a lista de arquivos
   */
  // var_dump($control->getAllFiles());

}





