<?php

require_once('controller/Control.php');
/**
 * Organizador de arquivos 
 * 
 * Basta passar o caminho que contém os arquivos que ele criará uma pasta para cada tipo e adicionar cada um no seu devido lugar
 * 
 * 1° Se alguma argumento foi passado
 * 2° Se o argumento é um diretório
 * 3° Se o diretório pode ser aberto
 * 4° Looping para cada tipo de arquivo que for achado
 * 5° Ignorando os pontos do sistema 
 *    $arrayFile: Atributos recebendo uma array do nome do arquivo separado para cada ponto achado
 * 6° Se o atributo $arrayFile tem dois index na array para sabermos depois se é uma arquivo sem extensão ou um diretório
 *    $extension: Pegando a extensão que sempre será o ultimo do index da array
 * 
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
