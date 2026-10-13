<?php
require __DIR__.'/backend.php';
function failImage($status,$message) { http_response_code($status); header('Content-Type: text/plain; charset=utf-8'); exit($message); }
if ($_SERVER['REQUEST_METHOD']!=='GET') failImage(405,'Método no permitido');
$drive=strtoupper($_GET['drive'] ?? '');
if (!preg_match('/^[A-Z]$/',$drive)) failImage(400,'Seleccioná una unidad');
$side=$_GET['side'] ?? 'front';
if (!in_array($side,['front','back'],true)) failImage(400,'Cara inválida');
try {
    $stmt=db()->prepare('SELECT contenido FROM montadas_fichas WHERE identificador=?');
    $stmt->execute([$_GET['id'] ?? '']); $json=$stmt->fetchColumn();
    if ($json===false) failImage(404,'Ficha inexistente');
    $relative=imageRelativePath(json_decode($json,true,512,JSON_THROW_ON_ERROR),$side);
    if (!$relative) failImage(404,'Sin archivo asignado o ruta inválida');
    $root=realpath($drive.':'.DIRECTORY_SEPARATOR.'Coleccion HLP');
    if ($root===false) failImage(404,'No se encuentra Coleccion HLP en la unidad seleccionada');
    $path=realpath($drive.':'.DIRECTORY_SEPARATOR.$relative);
    if ($path===false || !is_file($path)) failImage(404,'Archivo no encontrado en la unidad seleccionada');
    if (strncasecmp($path,$root.DIRECTORY_SEPARATOR,strlen($root)+1)!==0) failImage(403,'Ruta fuera de la colección');
    $types=['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp','tif'=>'image/tiff','tiff'=>'image/tiff','gif'=>'image/gif'];
    $ext=strtolower(pathinfo($path,PATHINFO_EXTENSION));
    if (!isset($types[$ext])) failImage(415,'Formato no compatible');
    header('Content-Type: '.$types[$ext]); header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, max-age=300'); header('Content-Length: '.filesize($path));
    readfile($path);
} catch(Throwable $e) { error_log('Visualizador imagen: '.$e->getMessage()); failImage(503,'No se pudo leer la imagen'); }
