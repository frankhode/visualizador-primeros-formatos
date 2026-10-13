# Visualizador de primeros formatos · Colección HLP

Aplicación local para XAMPP (Windows). Consulta las fichas existentes del repositorio `relevamiento-montadas-mysql` y muestra las imágenes según sus datos. No escribe en la base ni altera originales.

## Instalación

1. Descargar el repositorio (Code → Download ZIP) y extraerlo como `C:\xampp\htdocs\visualizador-primeros-formatos`.
2. Iniciar Apache y MySQL en XAMPP.
3. Abrir **http://localhost/visualizador-primeros-formatos/**.
4. Elegir la unidad donde se encuentra **Coleccion HLP**, por ejemplo `U:`.

Lee por defecto `relevamiento_montadas`, con usuario `root`, contraseña vacía y puerto 3306. Si el relevamiento usa otra conexión, copiar sus valores a `config/database.php`. También se puede crear `config/database.local.php` con el mismo formato: este archivo se ignora en Git. No importar otro SQL ni reemplazar la base existente. Requiere PHP 7.3+ y PDO MySQL.

## Rutas

La ruta se arma con **unidad + Coleccion HLP + sourcePath + frontName/backName**. Por ejemplo:

```text
U:\Coleccion HLP\114 - CDV Argentina y Temas\cdv sin categoria\archivo.tif
```

`sourcePath` puede incluir o no `Coleccion HLP`. Las barras `/`, `\` y duplicadas se normalizan al consultar, sin cambiar los registros. No se adivinan carpetas faltantes ni se reasignan archivos por nombre. La carpeta `Coleccion HLP` debe estar directamente en la unidad elegida; las carpetas inferiores deben coincidir con la ficha.

Apache debe tener acceso a esa unidad. Si Apache se ejecuta como servicio y no ve una unidad de red mapeada, ejecutarlo desde el panel de XAMPP en la sesión de Windows que tiene acceso a la unidad.

## Uso

- Búsqueda en todos los campos; admite varias palabras e ignora acentos y mayúsculas.
- Filtros combinados por fotógrafo, lugar, formato, procedimiento, clase de imagen, personas, objetos, carpeta y estado de la ficha.
- Galería paginada (24 fichas), con imágenes que se cargan al entrar en pantalla.
- Visor de frente, dorso o ambos; zoom con botones o rueda, arrastre y giro independiente por imagen. Flechas del teclado para cambiar de ficha y Escape para cerrar.
- Ficha completa, rutas y enlace al archivo original.
- **Actualizar datos** consulta los cambios guardados en el relevamiento.

La selección de unidad se recuerda en este navegador. JPG, PNG, WebP y GIF se visualizan directamente. Los TIFF se decodifican sin Internet con el mismo `tiff-decoder.js` del relevamiento. Las vistas TIFF se reducen a 2400 píxeles en su lado mayor; el original no se modifica. Archivos TIFF de más de 80 millones de píxeles se abren mediante el enlace al original para limitar el consumo de memoria. Se procesan hasta dos imágenes simultáneamente y se conserva una caché acotada de vistas previas.

## Alcance y verificación

Uso exclusivamente en localhost. GitHub guarda el código; GitHub Pages no ejecuta PHP/MySQL. No incluye imágenes ni datos de la colección, y no necesita Node ni instalar paquetes en XAMPP.

Se verificaron sintaxis JavaScript, normalización de rutas, filtros y lectura de campos. La prueba completa con la base real y las unidades de Windows debe hacerse en el XAMPP local: el entorno de preparación no tiene esa base ni esas imágenes.

El decodificador TIFF se toma de la aplicación de relevamiento, conservando su aviso de licencia incluido.
