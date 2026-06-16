<?php
/**
 * Convierte archivos .php a .html y reescribe los enlaces internos.
 */

$sourceDir = dirname(__DIR__);
$distDir = $sourceDir . '/dist';

echo "=== Iniciando Compilación Estática ===\n";

// 1. Crear el directorio de salida (dist)
if (!is_dir($distDir)) {
    if (!mkdir($distDir, 0755, true)) {
        fwrite(STDERR, "Error: No se pudo crear la carpeta dist/\n");
        exit(1);
    }
}

// 2. Buscar todos los archivos .php en la raíz
$phpFiles = glob($sourceDir . '/*.php');
if ($phpFiles === false) {
    $phpFiles = [];
}

foreach ($phpFiles as $file) {
    $filename = basename($file);

    // Ignorar archivos de plantilla o configuración (que empiecen por guion bajo) y scripts de build
    if (str_starts_with($filename, '_') || $filename === 'build.php') {
        continue;
    }

    echo "Procesando PHP: $filename...\n";

    // Ejecutar el archivo PHP capturando su salida en búfer
    ob_start();
    try {
        include $file;
        $htmlContent = ob_get_clean();
    } catch (Throwable $e) {
        ob_end_clean();
        fwrite(STDERR, "Error compilando $filename: " . $e->getMessage() . "\n");
        exit(1);
    }

    // Reemplazar enlaces internos .php por .html (ej. href="contacto.php" -> href="contacto.html")
    // Ignora enlaces externos (http, https, //)
    $htmlContent = preg_replace_callback('/href=["\']([^"\']+\.php)([^"\']*)["\']/i', function ($matches) {
        $link = $matches[1];
        $rest = $matches[2];
        if (preg_match('/^(https?:)?\/\//i', $link)) {
            return $matches[0];
        }
        $newLink = substr($link, 0, -4) . '.html';
        return 'href="' . $newLink . $rest . '"';
    }, $htmlContent);

    // Guardar el archivo en la carpeta dist/
    $outFilename = pathinfo($filename, PATHINFO_FILENAME) . '.html';
    file_put_contents($distDir . '/' . $outFilename, $htmlContent);
}

// 3. Función auxiliar para copiar directorios recursivamente
function copyDirectory($src, $dst)
{
    if (!is_dir($src)) {
        return;
    }
    @mkdir($dst, 0755, true);
    $dir = opendir($src);
    if (!$dir) {
        return;
    }
    while (($file = readdir($dir)) !== false) {
        if ($file !== '.' && $file !== '..') {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// 4. Copiar directorios estáticos si existen
$staticDirs = ['style', 'styles', 'media', 'views', 'js'];
foreach ($staticDirs as $dir) {
    if (is_dir($sourceDir . '/' . $dir)) {
        echo "Copiando carpeta estática: $dir/...\n";
        copyDirectory($sourceDir . '/' . $dir, $distDir . '/' . $dir);
    }
}

// 5. Copiar archivos .html de la raíz si no tienen un equivalente en .php
$htmlFiles = glob($sourceDir . '/*.html');
if ($htmlFiles === false) {
    $htmlFiles = [];
}

foreach ($htmlFiles as $file) {
    $filename = basename($file);
    $phpEquivalent = str_replace('.html', '.php', $file);

    // Solo copiamos el HTML original si no existe una plantilla PHP que lo reemplace
    if (!file_exists($phpEquivalent)) {
        echo "Copiando archivo HTML estático: $filename...\n";
        $htmlContent = file_get_contents($file);

        // También adaptamos enlaces .php por si acaso
        $htmlContent = preg_replace_callback('/href=["\']([^"\']+\.php)([^"\']*)["\']/i', function ($matches) {
            $link = $matches[1];
            $rest = $matches[2];
            if (preg_match('/^(https?:)?\/\//i', $link)) {
                return $matches[0];
            }
            $newLink = substr($link, 0, -4) . '.html';
            return 'href="' . $newLink . $rest . '"';
        }, $htmlContent);

        file_put_contents($distDir . '/' . $filename, $htmlContent);
    }
}

echo "=== Compilación Completada con Éxito (dist/) ===\n";
