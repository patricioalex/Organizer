<?php

// require_once('');
/**
 * Organizador de arquivos 
 * 
 * Basta passar o caminho que contém os arquivos que ele criará uma pasta para cada tipo e adicionará cada um no seu devido lugar.
 * 
 * 1° Se algum argumento foi passado verificando se tem dois index.
 * 2° Se o argumento do index 1 é um diretório.
 * 3° Chama um método do controller para listar todos os arquivos contidos no dirétorio e caso tenha algum erro será apresentado. 
 * 4° chama um método Looping com a lista de arquivos que existem no diretório.
 * 5° Ignorando os pontos dos diretórios especiais.
 *    $arrayFile: Um array é criado com index para cada ponto achado no nome do arquivo.
 * 6° Se o atributo $arrayFile tem mais de dois index para sabermos depois se é uma arquivo sem extensão ou um diretório.
 *    $extension: Pegando a extensão que sempre será o ultimo valor da array.
 * 7° se o nome da extensão do arquivo é maior ou igual a 6 para descartamos arquivos sem extensão e tratarmos separado.
 * 8° Aciona uma método que verifica se existe um diretório, caso não existir é criado e adicionado no respectivo diretório.
 *   Rename(): O método além de alterar nome de arquivos também serve para mover arquivos para outros diretórios.
 *   noExtension(): Método que cria e move arquivos sem extensão para seu respectivo diretório. *    
 */

// $controller = new Control();

// if(count($argv) >= 2){
//   if(is_dir($dir = $argv[1])){
//     if($controller->list($dir)){
//       foreach ($controller->getAllFiles() as $file ) {
//         if($file != '.' && $file != ".."){
//           $arrayFile = explode('.', $file);
//           if(count($arrayFile) >= 2){
//             $extension = end($arrayFile);
//             if($extension <= 6){
//               if($controller->folderExists($dir, $extension)){
//                 rename($dir.$file, $dir.$controller->nameFolder().'/'.$file);
//               }
//             }else{
//               $controller->noExtension($dir, $file);          
//             }
//           }else{
//             $controller->noExtension($dir, $file);
//           }
//         }
//       }
//     }else{
//         echo "\n\n". $controller->message($mensagem = 3) ."\n\n";
//     }
//     closedir();
//   }else{
//       echo "\n\n". $controller->message($mensagem = 2) ."\n\n";
//   }    
// }else{
//     echo "\n\n". $controller->message($mensagem = 1) ."\n\n";
// }



/**
 * Interface control
 */
interface Controller {

  public function validateArgv($argv);

  public function folderExists($dir, $extension);

  public function createFolder($dir, $extension);

  public function deleteFolder($arg);

  public function list();

  public function checkDir($dir);

  public function checkFile($dir, $file);

  public function extension($file);

  public function noExtension($dir, $file);

  public function moveFile($file);
  
  public function rollback($arg);

}



/**
 * Class com métodos que realizam o controle das ações do organizador
 */

 class Control implements Controller {
     
   private $nameDir;
   private $extension;
   private $allFiles;
   private $noExtension;
   private $dir;

    public function __construct()
    {
      $this->setNameDir('Arquivos_');
      $this->setNoExtension('sem_extensao');
      $this->setAllFiles(array());
      $this->setDir(array());
    }


    public function validateArgv($argv){
      if(count($argv) >= 2){
        return true;
      }else{
        throw new Exception("Nenhum diretório informado. Por favor, informe um diretório.");
      }
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
      if(in_array($this->getNameDir().strtoupper($extension), $this->getAllFiles())){
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

    public function deleteFolder($arg){

    }

  /**
   * list
   *
   * @param [type] $dir
   * @return void
   */
    public function list(){
      /**
       * Tentar abrir o dretório para listar todos os arquivos
       */
      if(opendir($this->getDir())){
        $all = array();
        while($file = readdir()){
          if($file != '.' && $file != ".."){
            $all[] = $file;
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
     * check
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
     * extension
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
        $extension = end($arrayFile);
        if($extension <= 6){
          if($this->folderExists($this->getDir(), $extension)){
            return true;
          }
        }else{
          $this->noExtension($this->getDir(), $file);
        }
      }else{
        $this->noExtension($this->getDir(), $file);
      }
    }


    /**
     * noExtension
     *
     * @param [type] $dir
     * @param [type] $file
     * @return void
     */
    public function noExtension($dir, $file){
      if(!$this->checkFile($dir, $file)){
        if($this->folderExists($dir, $this->getNoExtension())){
          rename($dir.$file, $dir.$this->nameFolder().'/'.$file);
        }
      }   
    }
    
    public function rollback($arg){

    }

    public function moveFile($file){
      rename($this->getDir().$file, $this->getDir().$this->nameFolder().'/'.$file);
    }


    // public function message($status){

    //   switch($status){
    //     case 0:
    //       return "Pasta(s) criada e arquivos alocados.";
    //     case 1: 
    //       return "Erro! Nenhum diretório informado. Por favor, informe um diretório.";
    //     case 2: 
    //       return "O argumento passado não é um diretório!";
    //     case 3:
    //       return "O diretório não pode ser aberto! Talvez seja permissão de pasta.";
    //     case 4: 
    //       return "O diretório não pode ser criado! Talvez seja permissão de pasta.";          
    //   }         
    // }



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
  * Estrutura tray, catch e finally que controla a execução do aplicativo
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

}

// finally{

//   echo "\n Aplicação finalizada! \n\n";

// }





