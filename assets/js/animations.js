// Intersection Observer para animaciones al hacer scroll
const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -50px 0px",
}

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("fade-in")
      observer.unobserve(entry.target)
    }
  })
}, observerOptions)

// Observar elementos cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
  // Observar secciones
  document.querySelectorAll("section").forEach((section) => {
    observer.observe(section)
  })

  // Observar cards de servicio
  document.querySelectorAll(".service-card").forEach((card, index) => {
    card.classList.add("stagger-fade-in")
    observer.observe(card)
  })

  // Observar cards de proyecto
  document.querySelectorAll(".project-card").forEach((card, index) => {
    card.classList.add("stagger-fade-in")
    observer.observe(card)
  })

  // Contador animado
  animateCounters()
})

// Función para animar contadores
function animateCounters() {
  const counters = document.querySelectorAll(".counter")

  const observerCounter = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const counter = entry.target
          const target = Number.parseInt(counter.getAttribute("data-target"))
          const duration = 2000 // 2 segundos
          const increment = target / (duration / 16) // 60fps
          let current = 0

          const updateCounter = () => {
            current += increment
            if (current < target) {
              counter.textContent = Math.floor(current) + "+"
              requestAnimationFrame(updateCounter)
            } else {
              counter.textContent = target + "+"
            }
          }

          updateCounter()
          observerCounter.unobserve(counter)
        }
      })
    },
    { threshold: 0.5 },
  )

  counters.forEach((counter) => {
    observerCounter.observe(counter)
  })
}

// Smooth scroll con offset para navbar
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault()
    const target = document.querySelector(this.getAttribute("href"))

    if (target) {
      const offset = 80 // Altura del navbar
      const targetPosition = target.offsetTop - offset

      window.scrollTo({
        top: targetPosition,
        behavior: "smooth",
      })

      // Cerrar menú móvil si está abierto
      const navMenu = document.getElementById("navMenu")
      if (navMenu.classList.contains("active")) {
        navMenu.classList.remove("active")
      }
    }
  })
})

// Parallax effect en hero
let lastScrollY = window.scrollY

window.addEventListener("scroll", () => {
  const scrolled = window.scrollY
  const hero = document.querySelector(".hero-section")

  if (hero) {
    const offset = scrolled * 0.5
    hero.style.transform = `translateY(${offset}px)`
  }

  lastScrollY = scrolled
})
