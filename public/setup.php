<?php
/**
 * GesTaller - Script de instalación inicial
 * ==========================================
 * 1. Visita https://jcaradeux.com/setup.php
 * 2. Espera a que aparezca "¡Instalación completada!"
 * 3. ELIMINA ESTE ARCHIVO INMEDIATAMENTE después
 *    (desde cPanel → File Manager → public_html/setup.php → Eliminar)
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/ges_taller/vendor/autoload.php';

$app = require_once __DIR__.'/ges_taller/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo '<!DOCTYPE html><html><head><meta charset="utf-8">
<title>GesTaller Setup</title>
<style>body{font-family:monospace;background:#0d1117;color:#e6edf3;padding:2rem;max-width:800px;margin:0 auto;}
h2{color:#58a6ff;} .ok{color:#3fb950;} .err{color:#f85149;} .section{background:#161b22;border:1px solid #30363d;border-radius:8px;padding:1rem;margin:1rem 0;}
</style></head><body>';

echo '<h2>🔧 GesTaller — Instalación</h2>';

// 1. Migraciones
echo '<div class="section"><b>1. Ejecutando migraciones...</b><br><pre>';
try {
    $kernel->call('migrate', ['--force' => true]);
    echo $kernel->output();
    echo '<span class="ok">✅ Migraciones OK</span>';
} catch (Exception $e) {
    echo '<span class="err">❌ Error: ' . $e->getMessage() . '</span>';
}
echo '</pre></div>';

// 2. Seeders
echo '<div class="section"><b>2. Ejecutando datos iniciales...</b><br><pre>';
try {
    $kernel->call('db:seed', ['--force' => true]);
    echo $kernel->output();
    echo '<span class="ok">✅ Datos iniciales OK</span>';
} catch (Exception $e) {
    echo '<span class="err">❌ Error: ' . $e->getMessage() . '</span>';
}
echo '</pre></div>';

// 3. Cache
echo '<div class="section"><b>3. Optimizando...</b><br><pre>';
$kernel->call('config:cache');  echo $kernel->output();
$kernel->call('route:cache');   echo $kernel->output();
$kernel->call('view:cache');    echo $kernel->output();
echo '<span class="ok">✅ Caché OK</span>';
echo '</pre></div>';

echo '<div class="section" style="border-color:#f85149;">
<b style="color:#f85149;">⚠️ IMPORTANTE — ELIMINA ESTE ARCHIVO AHORA</b><br><br>
cPanel → File Manager → public_html → setup.php → Eliminar<br><br>
<b>Acceso al sistema:</b><br>
URL: <a href="https://jcaradeux.com" style="color:#58a6ff;">https://jcaradeux.com</a><br>
Email: admin@gestaller.cl<br>
Password: admin123<br>
</div>';

echo '</body></html>';
