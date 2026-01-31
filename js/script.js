// Hamburger menu toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        const navLinks = document.querySelectorAll('.nav-link');

        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Cerrar menú al hacer click en un link
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
                // Agregar clase active al link clickeado y remover de otros
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });

        // Marcar el link activo según el hash de la URL
        function updateActiveLink() {
            const currentHash = window.location.hash || '#hero';
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentHash || (href === 'index.html' && currentHash === '#hero')) {
                    link.classList.add('active');
                } else if (href === 'index.html' && currentHash === '') {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Actualizar link activo al cargar
        updateActiveLink();

        // Actualizar link activo cuando cambia el hash
        window.addEventListener('hashchange', updateActiveLink);

  // Default options with optimization-friendly defaults
  const DEFAULTS = {
    particleCount: 100,
    connectionDistance: 140,
    colorRGB: '0,240,255',
    canvasId: 'particleCanvas',
    zIndex: 0,
    gridOverlay: true,
    containerSelector: '.hero-section', // limit particles to hero by default
    maxDPR: 1.5, // clamp devicePixelRatio for performance
    density: 0.00006, // particles per px (w*h*density)
    maxParticles: 200,
    maxChecksPerParticle: 32 // safety cap for neighbors
  }

  // Inject minimal CSS for canvas (scoped to page, actual placement is inside container)
  function injectStyles(id){
    if (document.getElementById(id + '-styles')) return
    const css = []
    css.push(`#${id} { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; display: block; }`)
    const style = document.createElement('style')
    style.id = id + '-styles'
    style.textContent = css.join('\n')
    document.head.appendChild(style)
  }

  // Create canvas if missing and append to parent
  function ensureCanvas(id, parent){
    let canvas = parent.querySelector('#' + id)
    if (!canvas){
      canvas = document.createElement('canvas')
      canvas.id = id
      // ensure parent is positioned so absolute canvas fits
      const computed = window.getComputedStyle(parent)
      if (computed.position === 'static') parent.style.position = 'relative'
      parent.appendChild(canvas)
    }
    return canvas
  }

  // Optional grid overlay element (appended to parent)
  function ensureGridOverlay(id, parent){
    const ovId = id + '-grid'
    let overlay = parent.querySelector('#' + ovId)
    if (!overlay){
      overlay = document.createElement('div')
      overlay.id = ovId
      overlay.style.position = 'absolute'
      overlay.style.inset = '0'
      overlay.style.pointerEvents = 'none'
      overlay.style.opacity = '0.12'
      overlay.style.zIndex = String(-1000)
      overlay.style.backgroundImage = 'linear-gradient(rgba(0,0,0,0.18) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.18) 1px, transparent 1px)'
      overlay.style.backgroundSize = '120px 120px, 120px 120px'
      parent.appendChild(overlay)
    }
    return overlay
  }

  // Spatial hash grid helper to avoid full O(n^2) checks
  class SpatialGrid {
    constructor(width, height, cellSize){
      this.cellSize = cellSize
      this.cols = Math.max(1, Math.ceil(width / cellSize))
      this.rows = Math.max(1, Math.ceil(height / cellSize))
      this.cells = new Array(this.cols * this.rows)
      for (let i=0;i<this.cells.length;i++) this.cells[i] = []
    }

    _idx(cx, cy){ return cy * this.cols + cx }

    clear(){
      for (let i=0;i<this.cells.length;i++) this.cells[i].length = 0
    }

    insert(p, index){
      const cx = Math.min(this.cols-1, Math.max(0, Math.floor(p.x / this.cellSize)))
      const cy = Math.min(this.rows-1, Math.max(0, Math.floor(p.y / this.cellSize)))
      this.cells[this._idx(cx, cy)].push(index)
      return {cx, cy}
    }

    queryNearby(cx, cy){
      const results = []
      for (let dx=-1; dx<=1; dx++){
        const ncx = cx + dx
        if (ncx < 0 || ncx >= this.cols) continue
        for (let dy=-1; dy<=1; dy++){
          const ncy = cy + dy
          if (ncy < 0 || ncy >= this.rows) continue
          const arr = this.cells[this._idx(ncx, ncy)]
          for (let k=0;k<arr.length;k++) results.push(arr[k])
        }
      }
      return results
    }
  }

  // ParticleSystem class (self-contained) - sizes to parent, optimized
  class ParticleSystem {
    constructor(canvas, parent, opts){
      this.canvas = canvas
      this.parent = parent
      this.ctx = canvas.getContext('2d')
      this.opts = Object.assign({}, DEFAULTS, opts)
      this.particles = []
      this.particleCount = 0
      this.connectionDistance = this.opts.connectionDistance
      this._running = false

      this._resize = this.resize.bind(this)
      this._onVisibility = this._onVisibility.bind(this)
      this.resize()
      this.init()
      this.start()
      window.addEventListener('resize', this._resize)
      document.addEventListener('visibilitychange', this._onVisibility)

      // pause when the parent (hero) is not visible in viewport
      this._observer = new IntersectionObserver((entries)=>{
        entries.forEach(e=>{
          if (!e.isIntersecting) this.stop()
          else this.start()
        })
      }, {threshold: 0})
      this._observer.observe(this.parent)
    }

    _onVisibility(){
      if (document.hidden) this.stop()
      else this.start()
    }

    start(){
      if (this._running) return
      this._running = true
      this.animate()
    }

    stop(){
      this._running = false
    }

    resize(){
      const maxDPR = this.opts.maxDPR || 1.5
      const dpr = Math.min(window.devicePixelRatio || 1, maxDPR)
      const rect = this.parent.getBoundingClientRect()
      const w = Math.max(1, Math.floor(rect.width))
      const h = Math.max(1, Math.floor(rect.height))
      this.canvas.width = Math.round(w * dpr)
      this.canvas.height = Math.round(h * dpr)
      this.canvas.style.width = w + 'px'
      this.canvas.style.height = h + 'px'
      this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0)

      // adapt particle count to area (density) while staying within limits
      const area = w * h
      const desired = Math.min(this.opts.maxParticles, Math.max(10, Math.round(area * this.opts.density)))
      this.particleCount = Math.min(this.opts.particleCount, desired)
      // recreate particles if needed
      this.init()
      // rebuild grid
      this.grid = new SpatialGrid(this.canvas.width, this.canvas.height, Math.max(32, this.connectionDistance))
    }

    init(){
      this.particles = []
      for (let i=0;i<this.particleCount;i++){
        this.particles.push({
          x: Math.random() * this.canvas.width,
          y: Math.random() * this.canvas.height,
          vx: (Math.random() - 0.5) * 0.4,
          vy: (Math.random() - 0.5) * 0.4,
          radius: Math.random() * 1.8 + 0.8
        })
      }
      // prepare grid for neighbor queries
      this.grid = new SpatialGrid(this.canvas.width, this.canvas.height, Math.max(32, this.connectionDistance))
    }

    drawParticle(p){
      this.ctx.beginPath()
      this.ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2)
      this.ctx.fillStyle = `rgba(${this.opts.colorRGB}, 0.85)`
      this.ctx.fill()
      // lighter glow for performance
      this.ctx.shadowBlur = 6
      this.ctx.shadowColor = `rgba(${this.opts.colorRGB}, 0.9)`
      this.ctx.shadowOffsetX = 0
      this.ctx.shadowOffsetY = 0
    }

    drawConnectionsOptimized(){
      // clear grid and insert particle indices
      this.grid.clear()
      const positions = []
      for (let i=0;i<this.particles.length;i++){
        const p = this.particles[i]
        positions[i] = this.grid.insert(p, i)
      }

      // for each particle, query nearby cell indices and draw to them (cap checks)
      for (let i=0;i<this.particles.length;i++){
        const a = this.particles[i]
        const cell = positions[i]
        const neighbors = this.grid.queryNearby(cell.cx, cell.cy)
        let checks = 0
        for (let n=0;n<neighbors.length;n++){
          const j = neighbors[n]
          if (j <= i) continue // avoid duplicates
          const b = this.particles[j]
          const dx = a.x - b.x
          const dy = a.y - b.y
          const dist = Math.sqrt(dx*dx + dy*dy)
          if (dist < this.connectionDistance){
            const opa = 1 - dist / this.connectionDistance
            this.ctx.beginPath()
            this.ctx.strokeStyle = `rgba(${this.opts.colorRGB}, ${opa * 0.28})`
            this.ctx.lineWidth = 1
            this.ctx.moveTo(a.x, a.y)
            this.ctx.lineTo(b.x, b.y)
            this.ctx.stroke()
          }
          checks++
          if (checks >= this.opts.maxChecksPerParticle) break
        }
      }
    }

    update(){
      for (const p of this.particles){
        p.x += p.vx
        p.y += p.vy
        if (p.x < 0 || p.x > this.canvas.width) p.vx *= -1
        if (p.y < 0 || p.y > this.canvas.height) p.vy *= -1
        p.x = Math.max(0, Math.min(this.canvas.width, p.x))
        p.y = Math.max(0, Math.min(this.canvas.height, p.y))
      }
    }

    clear(){
      this.ctx.clearRect(0,0,this.canvas.width, this.canvas.height)
    }

    animate(){
      if (!this._running) return
      this.clear()
      this.drawConnectionsOptimized()
      for (const p of this.particles) this.drawParticle(p)
      this.update()
      requestAnimationFrame(()=> this.animate())
    }

    stopAll(){
      this._running = false
      window.removeEventListener('resize', this._resize)
      document.removeEventListener('visibilitychange', this._onVisibility)
      if (this._observer) this._observer.disconnect()
    }
  }

  // Public initializer
  function initConstellation(options = {}){
    const opts = Object.assign({}, DEFAULTS, options)
    const parent = document.querySelector(opts.containerSelector) || document.body
    injectStyles(opts.canvasId)
    if (opts.gridOverlay) ensureGridOverlay(opts.canvasId, parent)
    const canvas = ensureCanvas(opts.canvasId, parent)
    // place canvas behind the content inside parent
    canvas.style.zIndex = String(opts.zIndex)
    canvas.style.position = 'absolute'
    canvas.style.top = '0'
    canvas.style.left = '0'
    canvas.style.right = '0'
    canvas.style.bottom = '0'
    canvas.style.pointerEvents = 'none'

    // If already initialized, stop previous instance (safety)
    if (window.__ParticleSystemInstance && typeof window.__ParticleSystemInstance.stopAll === 'function'){
      try{ window.__ParticleSystemInstance.stopAll() }catch(e){}
    }

    window.__ParticleSystemInstance = new ParticleSystem(canvas, parent, opts)
    return window.__ParticleSystemInstance
  }

  // Auto-init on DOM ready with defaults
  if (document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', ()=> initConstellation())
  } else {
    initConstellation()
  }

  // Expose to window to allow manual init/customization
  window.initConstellation = initConstellation

  // ================================================
  // VALIDACIÓN DE FORMULARIO DE CONTACTO
  // ================================================
  
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    const formInputs = contactForm.querySelectorAll('input, textarea');
    
    // Agregar evento blur a cada campo para validar
    formInputs.forEach(input => {
      input.addEventListener('blur', () => {
        validateField(input);
      });
      
      // Remover error cuando el usuario empieza a escribir después de blur
      input.addEventListener('input', () => {
        if (input.classList.contains('invalid')) {
          validateField(input);
        }
      });
    });
    
    // Validar todos los campos al enviar
    contactForm.addEventListener('submit', (e) => {
      let isValid = true;
      formInputs.forEach(input => {
        if (!validateField(input)) {
          isValid = false;
        }
      });
      
      if (!isValid) {
        e.preventDefault();
      }
    });
  }
  
  function validateField(field) {
    const value = field.value.trim();
    const isEmpty = value === '';
    // Si el campo NO es required y está vacío, no bloquea (p.e. telefono)
    if (!field.hasAttribute('required') && isEmpty) {
      field.classList.remove('invalid');
      return true;
    }

    // Validación para campo email (si está presente debe ser válido)
    if (field.id === 'email') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (isEmpty || !emailRegex.test(value)) {
        field.classList.add('invalid');
        return false;
      } else {
        field.classList.remove('invalid');
        return true;
      }
    }

    // Para campos requeridos: no vacíos
    if (field.hasAttribute('required') && isEmpty) {
      field.classList.add('invalid');
      return false;
    }

    // Si llega aquí, es válido (campo opcional vacío o campo no-email con contenido)
    field.classList.remove('invalid');
    return true;
  }

// Mostrar mensaje de éxito/error luego del envío (procesar_contacto.php redirige con ?success=1 o ?success=0)
function showContactResultFromQuery() {
  try {
    const url = new URL(window.location.href);
    const success = url.searchParams.get('success');
    if (success === null) return;
    const container = document.getElementById('contactMessage');
    if (!container) return;
    container.style.marginBottom = '1rem';
    container.style.padding = '0.75rem 1rem';
    container.style.borderRadius = '6px';
    container.style.fontWeight = '600';
    if (success === '1') {
      container.style.background = 'rgba(46, 204, 113, 0.12)';
      container.style.color = '#2ecc71';
      container.textContent = 'Mensaje enviado correctamente. ¡Gracias!';
    } else {
      container.style.background = 'rgba(231, 76, 60, 0.08)';
      container.style.color = '#e74c3c';
      container.textContent = 'Ocurrió un error al enviar. Por favor inténtalo de nuevo.';
    }

    // Remover el parámetro de la URL para evitar mostrarlo nuevamente al recargar
    url.searchParams.delete('success');
    history.replaceState(null, '', url.pathname + url.hash);
  } catch (e) {
    // no bloquear si hay error
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', showContactResultFromQuery);
} else {
  showContactResultFromQuery();
}

/*LIBRERIA INTL-TEL-INPUT PARA EL INDICATIVO DE CELULAR DEL FORMULARIO DE CONTACTO*/
document.addEventListener("DOMContentLoaded", function () {
  const phoneInput = document.querySelector("#telefono");
  const error = document.querySelector(".telefono-group .error-message");
  const form = document.getElementById("contactForm");
  const hiddenIndicativo = document.querySelector("#indicativo");
  const phoneGroup = phoneInput.closest(".telefono-group");

  if (phoneInput) {
    window.iti = window.intlTelInput(phoneInput, {
      initialCountry: "auto",
      geoIpLookup: function(callback) {
        fetch("https://ipapi.co/json")
          .then(res => res.json())
          .then(data => callback(data.country_code))
          .catch(() => callback("co"));
      },
      preferredCountries: ["co", "mx", "es", "us"],
      separateDialCode: true,
      nationalMode: false,
      autoPlaceholder: "off",
      utilsScript:
        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });
  }

  function updateIndicativo() {
    hiddenIndicativo.value =
      "+" + iti.getSelectedCountryData().dialCode;
      console.log("indicativo: " + hiddenIndicativo.value);
  }

  phoneInput.addEventListener("input", () => {
    if (phoneInput.value.trim() !== "") {
      phoneGroup.classList.add("has-value");
      updateIndicativo();
    } else {
      phoneGroup.classList.remove("has-value");
      hiddenIndicativo.value = "";
    }
  });

  phoneInput.addEventListener("countrychange", () => {
    if (phoneInput.value.trim() !== "") {
      updateIndicativo();
    }
  });

  function validarTelefono() {
    const countryData = iti.getSelectedCountryData();
    const digits = phoneInput.value.replace(/\D/g, "");

    if (digits.length === 0) {
      phoneInput.classList.add("invalid");
      error.textContent = "Este campo es requerido";
      error.style.display = "block";
      return false;
    }

    if (countryData.dialCode === "57" && digits.length !== 10) {
      phoneInput.classList.add("invalid");
      error.textContent = "El número debe tener 10 dígitos";
      error.style.display = "block";
      return false;
    }

    phoneInput.classList.remove("invalid");
    error.style.display = "none";
    return true;
  }

  phoneInput.addEventListener("input", () => {
    const countryData = iti.getSelectedCountryData();
    let digits = phoneInput.value.replace(/\D/g, "");

    if (countryData.dialCode === "57" && digits.length > 10) {
      phoneInput.value = digits.slice(0, 10);
    }

    error.style.display = "none";
    phoneInput.classList.remove("invalid");
  });

  phoneInput.addEventListener("blur", validarTelefono);

  form.addEventListener("submit", function (e) {
    if (!validarTelefono()) {
      e.preventDefault();
    }
  });

   // CAMBIO LABEL EMPRESA / EMPRENDIMIENTO
  const selectTipo = document.getElementById("despemp");
  const labelNomEmp = document.getElementById("labelNomEmp");

  if (selectTipo && labelNomEmp) {
    selectTipo.addEventListener("change", () => {
      labelNomEmp.textContent =
        selectTipo.value === "Emprendimiento"
          ? "Nombre del emprendimiento"
          : "Nombre de la empresa";
    });
  }
});