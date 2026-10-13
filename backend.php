<?php
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1','::1'], true)) {
    http_response_code(403); exit('Visualizador disponible en localhost.');
}
ini_set('display_errors','0');
function db() {
    $local=__DIR__.'/config/database.local.php';
    $c=require (is_file($local)?$local:__DIR__.'/config/database.php');
    return new PDO('mysql:host='.$c['host'].';port='.$c['port'].';dbname='.$c['database'].';charset=utf8mb4',$c['user'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
}
function imageRelativePath($record,$side) {
    $name=$record[$side==='back'?'backName':'frontName'] ?? '';
    if (!is_string($name) || $name==='' || preg_match('~[/\\\\:]~',$name)) return null;
    $parts=preg_split('~[/\\\\]+~',(string)($record['sourcePath'] ?? ''),-1,PREG_SPLIT_NO_EMPTY);
    foreach ($parts as $i=>$part) if (strcasecmp($part,'Coleccion HLP')===0) { $parts=array_slice($parts,$i+1); break; }
    $parts=array_merge(['Coleccion HLP'],$parts,[$name]);
    foreach ($parts as $part) if ($part==='.' || $part==='..' || preg_match('/[:\x00-\x1f]/',$part)) return null;
    return implode(DIRECTORY_SEPARATOR,$parts);
}
