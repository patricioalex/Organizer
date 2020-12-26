<?php


if(opendir($argv[1])){
    // $all = array();
    while($file = readdir()){
        // if($file != '.' && $file != ".."){
        $all[] = $file;
        $extension = substr($file, 0, 1);
        if($extension == '.'){
            echo $extension . "\n";
        }
        // }
    }      
}




closedir();


// var_dump($all);
// var_dump($extension);

// echo "\n\n". $primeria."\n\n";
