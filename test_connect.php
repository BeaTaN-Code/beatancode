<?php
// test_connect.php — prueba rápida de conexión usando .env
function parse_env($path) {
    $env = [];
    if (!file_exists($path)) return $env;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $env[$name] = $value;
    }
    return $env;
}

$env = parse_env(__DIR__ . '/.env');
$dbType = isset($env['DB_TYPE']) ? strtolower($env['DB_TYPE']) : 'mysql';
$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? ($dbType === 'postgres' ? '5432' : '3306');
$dbName = $env['DB_NAME'] ?? '';
$dbUser = $env['DB_USER'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';
$table = $env['DB_TABLE'] ?? 'FORMULARIO';

header('Content-Type: text/plain; charset=utf-8');
echo "Intentando conectar usando las variables de .env\n";
echo "DB_TYPE={$dbType}\n";
echo "DB_HOST={$dbHost}\n";
echo "DB_PORT={$dbPort}\n";
echo "DB_NAME={$dbName}\n";
echo "DB_USER={$dbUser}\n";

echo "\nConectando...\n";
try {
    if ($dbType === 'postgres' || $dbType === 'pgsql') {
        $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
    } else {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    }
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Conexión OK\n";
    try {
        $ver = $pdo->query($dbType === 'mysql' ? 'SELECT VERSION()' : 'SELECT version()')->fetchColumn();
        echo "Versión servidor: " . ($ver ?: 'desconocida') . "\n";
    } catch (Exception $e) {
        // ignore
    }
    // probar si la tabla existe
    try {
        $stmt = $pdo->prepare('SELECT 1 FROM ' . $table . ' LIMIT 1');
        $stmt->execute();
        echo "La tabla '{$table}' existe o es accesible.\n";
    } catch (Exception $e) {
        echo "No se pudo acceder a la tabla '{$table}': " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "ERROR al conectar: " . $e->getMessage() . "\n";
    echo "Recomendaciones:\n";
    echo " - Verifica que 'DB_HOST' sea el host correcto (a menudo 'localhost' o el host que te dio tu proveedor).\n";
    echo " - Asegúrate de que 'DB_USER' y 'DB_PASSWORD' estén correctos.\n";
    echo " - Si el proveedor utiliza un puerto distinto, añade DB_PORT en .env.\n";
}

?>