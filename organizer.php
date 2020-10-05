<?php

require_once('controller/Control.php');
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

$controller = new Control();

if(count($argv) >= 2){
  if(is_dir($dir = $argv[1])){
    if($controller->list($dir)){
      foreach ($controller->getAllFiles() as $file ) {
        if($file != '.' && $file != ".."){
          $arrayFile = explode('.', $file);
          if(count($arrayFile) >= 2){
            $extension = end($arrayFile);
            if($extension <= 6){
              if($controller->folderExists($dir, $extension)){
                rename($dir.$file, $dir.$controller->nameFolder().'/'.$file);
              }
            }else{
              $controller->noExtension($dir, $file);          
            }
          }else{
            $controller->noExtension($dir, $file);
          }
        }
      }
    }else{
        echo "\n\n". $controller->message($mensagem = 3) ."\n\n";
    }
    closedir();
  }else{
      echo "\n\n". $controller->message($mensagem = 2) ."\n\n";
  }    
}else{
    echo "\n\n". $controller->message($mensagem = 1) ."\n\n";
}
