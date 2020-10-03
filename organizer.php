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
 */

$dir = $argv[1];

// if(isset($dir)){
//     if(is_dir($dir)){
//         if(opendir($dir)){
//             while($file = readdir()){
//                 $arrayFile = explode('.', $file);
//                 $extension = end($arrayFiles);
//                 if(control::folderExists($dir, $extension)){
//                     foreach ($arrayFiles as $file ) {
//                         # code...
//                     }                  
//                 }
//             }
//         }else{
//             echo "\n\n". Control::message($mensagem = 3) ."\n\n";
//         }
//     }else{
//         echo "\n\n". Control::message($mensagem = 2) ."\n\n";
//     }    
// }else{
//     echo "\n\n". Control::message($mensagem = 1) ."\n\n";
// }




// $extesion = 'png';
// mkdir($dir.'Arquivos_'.strtoupper($extesion))
// positive limit
// $extesion = explode('.', $str, 2);
// $resultado = end($extesion);
// print_r(mkdir($dir.'Arquivos_'.strtoupper($extesion)));
// print_r(end($resultado));

// $os = array("Mac", "NT", "Irix", "Linux"); 
// if (in_array("Irix", $os)) { 
//     echo "Tem Irix";
// }
// if (in_array("mac", $os)) { 
//     echo "Tem mac";
// }
// $all = array();
opendir($dir);
while($file = readdir()){
    $all[] = $file;
  }
  closedir($dir);
  $teste[] = $all;
  print_r($teste);