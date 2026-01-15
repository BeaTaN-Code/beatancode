<?php
// procesar_contacto.php
// Lee las variables de .env, conecta a la BD y guarda el mensaje del formulario

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
$tableName = $env['DB_TABLE'] ?? 'contact_messages';

try {
    if ($dbType === 'postgres' || $dbType === 'pgsql') {
        $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
    } else {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    }
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    error_log('DB connection error: ' . $e->getMessage());
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json', true, 500);
        echo json_encode(['success' => false, 'error' => 'No se pudo conectar a la base de datos.']);
    } else {
        header('Location: index.html#contacto?success=0');
    }
    exit;
}

// Si el usuario no indicó una tabla y la tabla por defecto no existe, creamos una tabla mínima.
try {
    $checkTbl = $pdo->prepare('SELECT 1 FROM ' . $tableName . ' LIMIT 1');
    $checkTbl->execute();
} catch (Exception $e) {
    // La tabla no existe — crear sólo si se usa el nombre por defecto
    if ($tableName === 'contact_messages') {
        try {
            if ($dbType === 'postgres' || $dbType === 'pgsql') {
                $createSql = "CREATE TABLE IF NOT EXISTS contact_messages (
                    id SERIAL PRIMARY KEY,
                    nombre VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    telefono VARCHAR(50),
                    asunto VARCHAR(255),
                    mensaje TEXT,
                    estado VARCHAR(50) DEFAULT 'PENDIENTE',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );";
            } else {
                $createSql = "CREATE TABLE IF NOT EXISTS contact_messages (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    telefono VARCHAR(50),
                    asunto VARCHAR(255),
                    mensaje TEXT,
                    estado VARCHAR(50) DEFAULT 'PENDIENTE',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            }
            $pdo->exec($createSql);
        } catch (Exception $ex) {
            error_log('DB create table error: ' . $ex->getMessage());
        }
    }
}

// Obtener datos del formulario y construir INSERT dinámico según columnas reales
$post = array_map(function($v){ return is_string($v) ? trim($v) : $v; }, $_POST);

// Detectar columnas de la tabla para construir un INSERT compatible con el esquema real.
try {
    if ($dbType === 'postgres' || $dbType === 'pgsql') {
        $colStmt = $pdo->prepare("SELECT column_name FROM information_schema.columns WHERE table_catalog = :db AND table_name = :table");
        $colStmt->execute([':db' => $dbName, ':table' => $tableName]);
    } else {
        $colStmt = $pdo->prepare('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table');
        $colStmt->execute([':db' => $dbName, ':table' => $tableName]);
    }
    $columns = $colStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $columns = [];
}

$fieldCandidates = ['nombre','email','telefono','asunto','mensaje','estado'];
$insertCols = [];
$placeholders = [];
$params = [];

foreach ($fieldCandidates as $field) {
    $found = false;
    foreach ($columns as $colname) {
        if (strtolower($colname) === $field) { $found = true; break; }
    }
    if (!$found) continue;

    if (isset($post[$field]) && $post[$field] !== '') {
        $insertCols[] = $field;
        $placeholders[] = ':' . $field;
        $params[':' . $field] = $post[$field];
    } else {
        if ($field === 'estado') {
            $insertCols[] = 'estado';
            $placeholders[] = ':estado';
            $params[':estado'] = 'PENDIENTE';
        }
    }
}

if (empty($insertCols)) {
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json', true, 400);
        echo json_encode(['success' => false, 'error' => 'Campos requeridos faltantes.']);
    } else {
        header('Location: index.html#contacto?success=0');
    }
    exit;
}

try {
    $sql = 'INSERT INTO ' . $tableName . ' (' . implode(', ', $insertCols) . ') VALUES (' . implode(', ', $placeholders) . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        header('Location: index.html#contacto?success=1');
    }
    exit;
} catch (Exception $e) {
    error_log('DB insert error: ' . $e->getMessage());
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json', true, 500);
        echo json_encode(['success' => false, 'error' => 'Error al guardar el mensaje.']);
    } else {
        header('Location: index.html#contacto?success=0');
    }
    exit;
}

?>