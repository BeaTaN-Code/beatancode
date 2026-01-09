<?php
$page_title = "BeaTaN CODE - Portafolio de Desarrollo Web";
$current_page = "home";
include 'includes/header.php';
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="circuit-bg"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="glitch-text" data-text="BeaTaN CODE">PRUEBA DE SUBIDA</h1>
                <p class="hero-subtitle typewriter">Desarrollador Web Full Stack</p>
                <p class="hero-description">Creando experiencias digitales innovadoras con código limpio y diseño cutting-edge</p>
                <div class="cta-buttons">
                    <a href="#proyectos" class="btn btn-primary">Ver Proyectos</a>
                    <a href="#contacto" class="btn btn-secondary">Contactar</a>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <span></span>
        </div>
    </section>

    <!-- Sobre Mí Section -->
    <section id="sobre-mi" class="about-section">
        <div class="container">
            <h2 class="section-title">Sobre Mí</h2>
            <div class="about-content">
                <div class="about-text">
                    <p class="lead">Soy un desarrollador web apasionado por crear soluciones digitales innovadoras que combinan diseño estético con funcionalidad robusta.</p>
                    <p>Con experiencia en desarrollo full stack, me especializo en crear aplicaciones web modernas, responsivas y optimizadas. Mi enfoque se centra en escribir código limpio, mantenible y escalable.</p>
                    <div class="skills-highlight">
                        <div class="skill-badge">PHP</div>
                        <div class="skill-badge">JavaScript</div>
                        <div class="skill-badge">MySQL</div>
                        <div class="skill-badge">HTML5/CSS3</div>
                        <div class="skill-badge">React</div>
                        <div class="skill-badge">Node.js</div>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number counter" data-target="50">0</div>
                        <div class="stat-label">Proyectos Completados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number counter" data-target="5">0</div>
                        <div class="stat-label">Años de Experiencia</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number counter" data-target="100">0</div>
                        <div class="stat-label">Clientes Satisfechos</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="services-section">
        <div class="container">
            <h2 class="section-title">Servicios</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M16 18L22 12L16 6M8 6L2 12L8 18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Desarrollo Web</h3>
                    <p>Sitios web modernos y aplicaciones web a medida con las últimas tecnologías.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="2" y="3" width="20" height="14" rx="2" stroke-width="2"/>
                            <path d="M8 21H16M12 17V21" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>Diseño UI/UX</h3>
                    <p>Interfaces intuitivas y atractivas centradas en la experiencia del usuario.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
                            <path d="M3 9H21M9 21V9" stroke-width="2"/>
                        </svg>
                    </div>
                    <h3>Backend & APIs</h3>
                    <p>Desarrollo de sistemas backend robustos y APIs RESTful escalables.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M4 7V4H20V7M9 20H15M10 7L8 20M16 7L14 20" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>E-commerce</h3>
                    <p>Tiendas online completas con sistemas de pago y gestión de inventario.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="2"/>
                            <path d="M12 12H12.01M6 12H6.01M18 12H18.01" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>Mantenimiento</h3>
                    <p>Soporte continuo y actualizaciones para mantener tu sitio siempre optimizado.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M21 16V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V16C3 17.1046 3.89543 18 5 18H19C20.1046 18 21 17.1046 21 16Z" stroke-width="2"/>
                            <path d="M10 12L14 12" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>Optimización SEO</h3>
                    <p>Mejora la visibilidad de tu sitio en motores de búsqueda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Proyectos Section -->
    <section id="proyectos" class="projects-section">
        <div class="container">
            <h2 class="section-title">Proyectos Destacados</h2>
            <div class="projects-grid">
                <?php
                $proyectos = [
                    [
                        'titulo' => 'E-commerce Tech Store',
                        'descripcion' => 'Tienda online completa con sistema de pagos, carrito de compras y panel de administración.',
                        'tecnologias' => ['PHP', 'MySQL', 'JavaScript', 'Stripe'],
                        'imagen' => 'proyecto1.jpg',
                        'link' => '#'
                    ],
                    [
                        'titulo' => 'Sistema de Gestión CRM',
                        'descripcion' => 'Aplicación web para gestión de clientes, ventas y reportes en tiempo real.',
                        'tecnologias' => ['PHP', 'Laravel', 'Vue.js', 'PostgreSQL'],
                        'imagen' => 'proyecto2.jpg',
                        'link' => '#'
                    ],
                    [
                        'titulo' => 'Portfolio Interactivo',
                        'descripcion' => 'Portafolio personal con animaciones 3D y efectos visuales impactantes.',
                        'tecnologias' => ['React', 'Three.js', 'Node.js'],
                        'imagen' => 'proyecto3.jpg',
                        'link' => '#'
                    ],
                    [
                        'titulo' => 'API RESTful de Reservas',
                        'descripcion' => 'Sistema backend para gestión de reservas con autenticación JWT.',
                        'tecnologias' => ['Node.js', 'Express', 'MongoDB'],
                        'imagen' => 'proyecto4.jpg',
                        'link' => '#'
                    ],
                    [
                        'titulo' => 'Dashboard Analytics',
                        'descripcion' => 'Panel de control con gráficos interactivos y análisis de datos en tiempo real.',
                        'tecnologias' => ['React', 'D3.js', 'Firebase'],
                        'imagen' => 'proyecto5.jpg',
                        'link' => '#'
                    ],
                    [
                        'titulo' => 'App de Chat en Tiempo Real',
                        'descripcion' => 'Aplicación de mensajería instantánea con WebSockets y notificaciones push.',
                        'tecnologias' => ['Socket.io', 'Node.js', 'React'],
                        'imagen' => 'proyecto6.jpg',
                        'link' => '#'
                    ]
                ];

                foreach ($proyectos as $proyecto): ?>
                    <div class="project-card">
                        <div class="project-image">
                            <img src="assets/images/<?php echo $proyecto['imagen']; ?>" alt="<?php echo $proyecto['titulo']; ?>">
                            <div class="project-overlay">
                                <a href="<?php echo $proyecto['link']; ?>" class="view-project">Ver Proyecto</a>
                            </div>
                        </div>
                        <div class="project-content">
                            <h3><?php echo $proyecto['titulo']; ?></h3>
                            <p><?php echo $proyecto['descripcion']; ?></p>
                            <div class="project-tech">
                                <?php foreach ($proyecto['tecnologias'] as $tech): ?>
                                    <span class="tech-tag"><?php echo $tech; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contacto Section -->
    <section id="contacto" class="contact-section">
        <div class="container">
            <h2 class="section-title">Contacto</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Hablemos de tu proyecto</h3>
                    <p>¿Tienes una idea? Me encantaría escucharla y ayudarte a hacerla realidad.</p>
                    <div class="contact-details">
                        <div class="contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>contacto@beatancode.com</span>
                        </div>
                        <div class="contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 5C3 3.89543 3.89543 3 5 3H8.27924C8.70967 3 9.09181 3.27543 9.22792 3.68377L10.7257 8.17721C10.8831 8.64932 10.6694 9.16531 10.2243 9.38787L7.96701 10.5165C9.06925 12.9612 11.0388 14.9308 13.4835 16.033L14.6121 13.7757C14.8347 13.3306 15.3507 13.1169 15.8228 13.2743L20.3162 14.7721C20.7246 14.9082 21 15.2903 21 15.7208V19C21 20.1046 20.1046 21 19 21H18C9.71573 21 3 14.2843 3 6V5Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>+52 123 456 7890</span>
                        </div>
                        <div class="contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M17.657 16.657L13.414 20.9C12.2426 22.0714 10.3284 22.0714 9.157 20.9L4.914 16.657C2.36441 14.1074 2.36441 9.89262 4.914 7.343L7.343 4.914C9.89262 2.36441 14.1074 2.36441 16.657 4.914L20.9 9.157C22.0714 10.3284 22.0714 12.2426 20.9 13.414L16.657 17.657Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 11C15 12.6569 13.6569 14 12 14C10.3431 14 9 12.6569 9 11C9 9.34315 10.3431 8 12 8C13.6569 8 15 9.34315 15 11Z" stroke-width="2"/>
                            </svg>
                            <span>México, CDMX</span>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="#" class="social-icon" aria-label="GitHub">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="Twitter">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <form class="contact-form" id="contactForm" method="POST" action="procesar_contacto.php">
                    <div class="form-group">
                        <input type="text" name="nombre" id="nombre" required>
                        <label for="nombre">Nombre</label>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="asunto" id="asunto" required>
                        <label for="asunto">Asunto</label>
                    </div>
                    <div class="form-group">
                        <textarea name="mensaje" id="mensaje" rows="5" required></textarea>
                        <label for="mensaje">Mensaje</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
