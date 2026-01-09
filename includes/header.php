<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BeaTaN CODE - Portafolio de desarrollo web profesional. Diseño y desarrollo de aplicaciones web modernas.">
    <meta name="keywords" content="desarrollo web, php, javascript, portafolio, diseño web">
    <meta name="author" content="BeaTaN CODE">
    <title><?php echo isset($page_title) ? $page_title : 'BeaTaN CODE'; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="nav-brand">
                <img src="assets/images/logo.png" alt="BeaTaN CODE" class="nav-logo">
                <span class="nav-title">BeaTaN CODE</span>
            </div>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="nav-link <?php echo ($current_page == 'home') ? 'active' : ''; ?>">Inicio</a></li>
                <li><a href="#sobre-mi" class="nav-link">Sobre Mí</a></li>
                <li><a href="#servicios" class="nav-link">Servicios</a></li>
                <li><a href="#proyectos" class="nav-link">Proyectos</a></li>
                <li><a href="#contacto" class="nav-link">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <!-- Particle Background -->
    <canvas id="particleCanvas"></canvas>
