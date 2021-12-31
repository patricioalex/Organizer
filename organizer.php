<?php

/**
 * Organizador de arquivos 
 * 
 * Basta passar o caminho que contém os arquivos que ele criará um diretório para cada tipo e moverá para seu devido lugar.   
 */


/**
 * Interface controller
 */
interface OrganizerController {

  public function checkArgv();

  public function isDir();

  public function listDirectoryFiles();

  public function hasFiles();

  public function checkFolderExists($fileType);

  public function createFolder($fileType);

  public function isFile($file);

  public function fileType($file);

  public function withoutExtension($file);

  public function moveFile($file);

  public function fileProcessing();


  
  // public function deleteFolder($arg);

  // public function rollback($arg);

}



/**
 * Class  que implementa um contrato com métodos que realizam o controle das ações do organizador
 */

class Organizer implements OrganizerController {

    
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
     * @return void
     */
    public function checkArgv(){
      $greaterThanTow = 2;
        if(count($this->getArgv()) >= $greaterThanTow){
          return true;
        }
        throw new Exception("Nenhum diretório informado. Por favor, informe um diretório como parâmetro.");
        return false;
    }

    /**
     * @method public isDir()
     *
     * Checa se o diretório é valído 
     * 
     * @return void
     */
    public function isDir(){
      if(is_dir($this->getArgv()[1])){
        $this->setDir($this->getArgv()[1].'/');
        return true;
      }
        throw new Exception("O argumento passado não é um diretório!");
        return false;
    }


  /**
   * @method listDirectoryFiles()
   * 
   * Verifica e listDirectoryFilesa todos os arquivos contidos no diretório.
   * Sen ão tiver arquivos no diretório, a aplicação é encerrada.
   *
   */
  public function listDirectoryFiles(){
    //Existe o método scandir() nativo do PHP para listar diretórios
    if(opendir($this->getDir())){
      $all = array();
      while($file = readdir()){
        if($file != '.' && $file != ".."){
            array_push($all, $file);
        }
      } 
      $this->setAllFiles($all);     
    }else{
      throw new Exception("O diretório não pode ser aberto! Talvez seja permissão de pasta.");
      return false;
    }
      closedir();
  }

  /*
  * verifica se i duretório está vázio.
  */
  public function hasFiles(){
    if(!empty($this->getAllFiles())){
      return true;
    }
      throw new Exception('Diretótio vazio!');
      return false;
  }

  /**
   * @method checkFolderExists()
   * 
   * Verifica se exite um diretório para a extensão/tipo de arquivo.
   * Se não exitir, ele criará um.
   */
  public function checkFolderExists($fileType){
    $this->setfileType(strtoupper($fileType));  
    if(file_exists($this->getDir().$this->getNameDir().strtoupper($fileType))){
        return true;
    }else if($this->createFolder($fileType)){
        return true;
    }
      throw new Exception("O diretório não existe e não foi possível criar! Talvez seja permissão de pasta. Por favor, verifique.");
      return false;
  }

  /**
   * @method createFolderName()
   * 
   * Pega o nome do diretório da extensão/tipo do arquivo
   * 
   */
  public function createFolderName(){
      return $this->getNameDir().$this->getfileType();
  }
  
  /**
   * @method createFolder()
   *
   * Criador de diretório para extensã/Tipo de arquivo
   */
  public function createFolder($fileType){
    return mkdir($this->getDir() . $this->getNameDir() . strtoupper($fileType));
  }


    
  /**
   * @method isFile()
   * 
   * Checa se o argumento é um arquivo ou diretório.
   * Isso ocorre quando há dúvida se é uma arquivo ou diretório que não foi achado extensão ou o tamanho da extensão é maior que 6.
   *
   */
  public function isFile($file){
      return is_dir($this->getDir().$file);
  }

  /**
   * @method fileType()
   * 
   * Valida se extiste extensão para o arquivo.
   * Verifica se o tamanho do nome da extensão é maior que 6.
   * Verifica se existe diretório para o arquivo que foi validado nas outras etapas acima.
   */
  public function fileType($file){
    $arrayFile = explode('.', $file);
    $fileType = end($arrayFile);
    $greaterThanTow = 2 ;
    $lessThanOrEqualSix = 6;
    if($this->checkFolderExists($fileType) && count($arrayFile) >= $greaterThanTow && $fileType <= $lessThanOrEqualSix){
        return true;
    }
      $this->withoutExtension($file);
      return false;
  }


  /**
   * @method withoutExtension()
   * 
   * Aciona o método isFile para saber se é um arquivo ou diretório.
   * Verfica se o diretório sem extensão exite. Senão exitir, será criado e moverá o respectivo arquivo.
   * Aciona o método moveFile() se retornar true na etapa anterior.
   *
   */
  public function withoutExtension($file){
    if(!$this->isFile($file)){
      if($this->checkFolderExists($this->getwithoutExtension())){
        $this->moveFile($file);
      }
    }   
  }
  

  /**
   * @method moveFile()
   * 
   * Move arquvios para seus respectivos diretórios.
   */
  public function moveFile($file){
    rename($this->getDir().$file, $this->getDir().$this->createFolderName().'/'.$file);
  }

  public function fileProcessing(){
      foreach ($this->getAllFiles() as $file ) {
        if($this->fileType($file)){
          $this->moveFile($file);
        }
      }
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
  * setNameDir function
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
   * setWithoutExtension function
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
  * Estrutura de exceções que inicia a operação do organizador
  */

  $organizer = new Organizer($argv);

try {
  $organizer->checkArgv();
  $organizer->isDir();
  $organizer->listDirectoryFiles();
  $organizer->hasFiles();
  $organizer->fileProcessing();

} catch (Exception $error) {
  echo "\n Erro de Execução: " . $error->getMessage()."\n\n";
  // echo "\n File do Erro de Execução: " . $error->getFile()."\n\n";
  // echo "\n Line do Erro ocorrido: " . $error->getLine()."\n\n";
}finally{
  // var_dump($organizer->getAllFiles());
}





