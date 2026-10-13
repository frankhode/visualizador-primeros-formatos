<?php
require __DIR__.'/backend.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD']!=='GET') { http_response_code(405); exit; }
try {
    $records=[];
    foreach(db()->query('SELECT contenido FROM montadas_fichas ORDER BY posicion, identificador') as $row) {
        $r=json_decode($row['contenido'],true,512,JSON_THROW_ON_ERROR);
        if (is_array($r)) $records[]=$r;
    }
    echo json_encode(['records'=>$records],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
} catch(Throwable $e) {
    error_log('Visualizador: '.$e->getMessage()); http_response_code(503);
    echo json_encode(['error'=>'No se pudo leer la base. Iniciá MySQL y revisá config/database.php.']);
}
