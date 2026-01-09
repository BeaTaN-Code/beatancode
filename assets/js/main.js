// Funcionalidad principal del portafolio

document.addEventListener("DOMContentLoaded", () => {
  // Inicializar funcionalidades
  initNavbar()
  initForm()
  showMessages()
})

// Navbar scroll effect y mobile toggle
function initNavbar() {
  const navbar = document.getElementById("navbar")
  const navToggle = document.getElementById("navToggle")
  const navMenu = document.getElementById("navMenu")

  // Efecto de scroll
  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled")
    } else {
      navbar.classList.remove("scrolled")
    }
  })

  // Toggle menú móvil
  if (navToggle) {
    navToggle.addEventListener("click", () => {
      navMenu.classList.toggle("active")
      navToggle.classList.toggle("active")
    })
  }

  // Cerrar menú al hacer clic fuera
  document.addEventListener("click", (e) => {
    if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
      navMenu.classList.remove("active")
      navToggle.classList.remove("active")
    }
  })

  // Highlight active nav link
  const sections = document.querySelectorAll("section[id]")
  const navLinks = document.querySelectorAll(".nav-link")

  window.addEventListener("scroll", () => {
    let current = ""

    sections.forEach((section) => {
      const sectionTop = section.offsetTop
      const sectionHeight = section.clientHeight
      if (window.scrollY >= sectionTop - 100) {
        current = section.getAttribute("id")
      }
    })

    navLinks.forEach((link) => {
      link.classList.remove("active")
      if (link.getAttribute("href") === `#${current}`) {
        link.classList.add("active")
      }
    })
  })
}

// Validación y manejo del formulario
function initForm() {
  const form = document.getElementById("contactForm")

  if (form) {
    form.addEventListener("submit", (e) => {
      if (!validateForm(form)) {
        e.preventDefault()
        return false
      }
    })

    // Validación en tiempo real
    const inputs = form.querySelectorAll("input, textarea")
    inputs.forEach((input) => {
      input.addEventListener("blur", () => {
        validateField(input)
      })

      input.addEventListener("input", () => {
        if (input.classList.contains("error")) {
          validateField(input)
        }
      })
    })
  }
}

// Validar campo individual
function validateField(field) {
  const value = field.value.trim()
  let isValid = true
  let errorMessage = ""

  // Limpiar errores previos
  removeFieldError(field)

  // Validación según tipo de campo
  if (field.hasAttribute("required") && value === "") {
    isValid = false
    errorMessage = "Este campo es requerido"
  } else if (field.type === "email" && value !== "") {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(value)) {
      isValid = false
      errorMessage = "Email no válido"
    }
  }

  if (!isValid) {
    showFieldError(field, errorMessage)
  }

  return isValid
}

// Validar formulario completo
function validateForm(form) {
  const fields = form.querySelectorAll("input[required], textarea[required]")
  let isValid = true

  fields.forEach((field) => {
    if (!validateField(field)) {
      isValid = false
    }
  })

  return isValid
}

// Mostrar error en campo
function showFieldError(field, message) {
  field.classList.add("error")

  const formGroup = field.closest(".form-group")
  let errorElement = formGroup.querySelector(".error-message")

  if (!errorElement) {
    errorElement = document.createElement("span")
    errorElement.className = "error-message"
    formGroup.appendChild(errorElement)
  }

  errorElement.textContent = message
}

// Remover error de campo
function removeFieldError(field) {
  field.classList.remove("error")

  const formGroup = field.closest(".form-group")
  const errorElement = formGroup.querySelector(".error-message")

  if (errorElement) {
    errorElement.remove()
  }
}

// Mostrar mensajes de notificación
function showMessages() {
  const urlParams = new URLSearchParams(window.location.search)
  const mensaje = urlParams.get("mensaje")

  if (mensaje) {
    let messageText = ""
    let messageType = ""

    switch (mensaje) {
      case "success":
        messageText = "¡Mensaje enviado exitosamente! Te contactaré pronto."
        messageType = "success"
        break
      case "error":
        messageText = "Hubo un error al enviar el mensaje. Por favor intenta de nuevo."
        messageType = "error"
        break
      case "validation_error":
        messageText = "Por favor completa todos los campos correctamente."
        messageType = "error"
        break
    }

    if (messageText) {
      showNotification(messageText, messageType)

      // Limpiar URL
      window.history.replaceState({}, document.title, window.location.pathname)
    }
  }
}

// Mostrar notificación
function showNotification(message, type) {
  const notification = document.createElement("div")
  notification.className = `notification ${type}`
  notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close">&times;</button>
    `

  document.body.appendChild(notification)

  // Animar entrada
  setTimeout(() => {
    notification.classList.add("show")
  }, 100)

  // Cerrar al hacer clic
  notification.querySelector(".notification-close").addEventListener("click", () => {
    closeNotification(notification)
  })

  // Auto cerrar después de 5 segundos
  setTimeout(() => {
    closeNotification(notification)
  }, 5000)
}

// Cerrar notificación
function closeNotification(notification) {
  notification.classList.remove("show")
  setTimeout(() => {
    notification.remove()
  }, 300)
}

// Lazy loading para imágenes
if ("IntersectionObserver" in window) {
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target
        img.src = img.dataset.src
        img.classList.add("loaded")
        observer.unobserve(img)
      }
    })
  })

  document.querySelectorAll("img[data-src]").forEach((img) => {
    imageObserver.observe(img)
  })
}

// Añadir estilos para notificaciones y errores
const style = document.createElement("style")
style.textContent = `
    .notification {
        position: fixed;
        top: 100px;
        right: 20px;
        max-width: 400px;
        padding: 1rem 1.5rem;
        background: var(--dark-secondary);
        border-radius: 8px;
        border: 2px solid;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        transform: translateX(450px);
        transition: transform 0.3s ease;
        z-index: 9999;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.5);
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification.success {
        border-color: var(--primary-cyan);
        box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
    }
    
    .notification.error {
        border-color: #ff4444;
        box-shadow: 0 0 20px rgba(255, 68, 68, 0.3);
    }
    
    .notification-close {
        background: none;
        border: none;
        color: var(--text-primary);
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-fast);
    }
    
    .notification-close:hover {
        color: var(--primary-cyan);
    }
    
    .form-group input.error,
    .form-group textarea.error {
        border-color: #ff4444;
    }
    
    .error-message {
        display: block;
        color: #ff4444;
        font-size: 0.85rem;
        margin-top: 0.3rem;
        margin-left: 0.5rem;
    }
`
document.head.appendChild(style)
