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

  public function checkArgv();

  public function isDir($dir);

  public function listDirectoryFiles();

  public function hasFiles();

  public function checkFolderExists($dir, $fileType);

  public function createFolder($dir, $fileType);

  // public function deleteFolder($arg);

  


  public function isFile($dir, $file);

  public function fileType($file);

  public function withoutExtension($dir, $file);

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
    private $argv;
    private $nameDir;
    private $fileType;
    private $allFiles;
    private $withoutExtension;
    private $dir ;

    /**
     * construct
     */
    public function __construct($argv, string $firstFolderName = null )
    {
      $this->setArgv($argv);
      $this->setNameDir( $firstFolderName ? $firstFolderName : 'Arquivos_');
      $this->setWithoutExtension('SEM_EXTENSAO');
      $this->setAllFiles(array());
      $this->setDir(array());
    }

    /**
     * @method checkArgv()
     * 
     * Valida se foi passado algum argumento
     *
     * @param [type] $argv
     * @return void
     */
    public function checkArgv(){
        if(count($this->getArgv()) >= 2){
          return true;
        }
        throw new Exception("Nenhum diretório informado. Por favor, informe um diretório.");
    }

    /**
     * @method public isDir()
     *
     * Checa se o diretório é valído 
     * 
     * @param [type] $dir
     * @return void
     */
    public function isDir($dir){
      if(is_dir($dir[1])){
        $this->setDir($dir[1]);
        return true;
      }
        throw new Exception("O argumento passado não é um diretório!");
    }


  /**
   * @method listDirectoryFiles()
   * 
   * Verifica e listDirectoryFilesa todos os arquivos contidos no diretório.
   * Senão tiver arquivos no diretório, a aplicação é encerrada.
   *
   */
  public function listDirectoryFiles(){
    /**
     * Tentar abrir o dretório para listDirectoryFilesar todos os arquivos
     * Existe o método scandir() para listDirectoryFilesar diretórios
     */
    if(opendir($this->getDir())){
      $all = array();
      while($file = readdir()){
        if($file != '.' && $file != ".."){
          $hidden = substr($file, 0, 1);
          if($hidden != '.'){
          array_push($all, $file);
          }
        }
      } 
      $this->setAllFiles($all);     
    }else{
      throw new Exception("O diretório não pode ser aberto! Talvez seja permissão de pasta.");
    }
    closedir();
  }

    public function hasFiles(){
      if(!empty($this->getAllFiles())){
        return true;
      }
        throw new Exception('Diretótio vazio!');
    }

    /**
     * @method createFolderName()
     * 
     * Pega o nome do diretório da extensão/tipo do arquivo
     * 
     * @return void
     */
    public function createFolderName(){
        return $this->getNameDir().$this->getfileType();
    }


    /**
     * @method checkFolderExists()
     * 
     * Verifica se exite um diretório para a extensão/tipo de arquivo.
     * Senão exitir, ele criará um.
     
     * @param [type] $dir
     * @param [type] $fileType
     * @return void
     */
    public function checkFolderExists($dir, $fileType){
      $this->setfileType(strtoupper($fileType));  
      if(file_exists($dir.'/'.$this->getNameDir().strtoupper($fileType))){
          return true;
      }else{
        if($this->createFolder($dir, $fileType)){
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
    public function createFolder($dir, $fileType){
      if(mkdir($dir.$this->getNameDir().strtoupper($fileType))){
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
     * @method isFile()
     * 
     * Checa se o argumento é um arquivo ou diretório.
     * Isso ocorre quando há dúvida se é uma arquivo ou diretório que não foi achado extensão ou o tamanho da extensão é maior que 6.
     *
     * @param [type] $dir
     * @param [type] $file
     * @return void
     */
    public function isFile($dir, $file){
        if(is_dir($dir.$file)){
          return true;
        }else{
          return false;
        }
    }

    /**
     * @method fileType()
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
    public function fileType($file){
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
          $fileType = end($arrayFile);
          if($fileType <= 6){
            if($this->checkFolderExists($this->getDir(), $fileType)){
              return true;
            }
          }else{
            $this->withoutExtension($this->getDir(), $file);
          }
        // }
      }else{
        $this->withoutExtension($this->getDir(), $file);
      }
      // }
    }


    /**
     * @method withoutExtension()
     * 
     * Aciona o método isFile para saber se é um arquivo ou diretório.
     * Verfica se o diretório sem extensão exite. Senão exitir, o será criado e moverá o respectivo arquivo.
     * Aciona o método moveFile() se retornar true na etapa anterior.
     *
     * @param [type] $dir
     * @param [type] $file
     * @return void
     */
    public function withoutExtension($dir, $file){
      if(!$this->isFile($dir, $file)){
        if($this->checkFolderExists($dir, $this->getwithoutExtension())){
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
      rename($this->getDir().'/'.$file, $this->getDir().$this->createFolderName().'/'.$file);
    }

    /**
     * Get atributos privates
     *
     */ 
    public function getArgv()
    {
        return $this->argv;
    }

    /**
     * Set atributos privates
     *     *
     */ 
    public function setArgv($argv)
    {
        $this->argv = $argv;
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
    * Get the value of fileType
    */ 
   public function getfileType()
   {
      return $this->fileType;
   }

   /**
    * setfileType
    *
    * @param [type] $fileType
    * @return void
    */
   public function setfileType($fileType)
   {
      $this->fileType = $fileType;
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
    * Get the value of withoutExtension
    */ 
   public function getwithoutExtension()
   {
      return $this->withoutExtension;
   }

  /**
   * Undocumented function
   *
   * @param [type] $withoutExtension
   * @return void
   */
   public function setWithoutExtension($withoutExtension)
   {
      $this->withoutExtension = $withoutExtension;
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

$control = new Control($argv);

try {
  $control->checkArgv();
  $control->isDir($argv);
  $control->listDirectoryFiles();
  $control->hasFiles();

  //       foreach ($control->getAllFiles() as $file ) {
  //         if($control->fileType($file)){
  //           $control->moveFile($file);
  //         }
  //       }
} catch (Exception $error) {

  echo "\n Erro: " . $error->getMessage()."\n\n";

}finally{

  // echo "\n Aplicação finalizada! \n\n";
// echo __DIR__."\n\n\n";
  /**
   * Testes para ver a listDirectoryFilesa de arquivos
   */
  var_dump($control->getAllFiles());

}





