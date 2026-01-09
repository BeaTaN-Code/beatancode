// Sistema de Partículas Cyberpunk
class ParticleSystem {
  constructor() {
    this.canvas = document.getElementById("particleCanvas")
    this.ctx = this.canvas.getContext("2d")
    this.particles = []
    this.particleCount = 100
    this.connectionDistance = 150

    this.resize()
    this.init()
    this.animate()

    window.addEventListener("resize", () => this.resize())
  }

  resize() {
    this.canvas.width = window.innerWidth
    this.canvas.height = window.innerHeight
  }

  init() {
    this.particles = []
    for (let i = 0; i < this.particleCount; i++) {
      this.particles.push({
        x: Math.random() * this.canvas.width,
        y: Math.random() * this.canvas.height,
        vx: (Math.random() - 0.5) * 0.5,
        vy: (Math.random() - 0.5) * 0.5,
        radius: Math.random() * 2 + 1,
      })
    }
  }

  drawParticle(particle) {
    this.ctx.beginPath()
    this.ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2)
    this.ctx.fillStyle = "rgba(0, 240, 255, 0.8)"
    this.ctx.fill()

    // Glow effect
    this.ctx.shadowBlur = 10
    this.ctx.shadowColor = "rgba(0, 240, 255, 0.8)"
  }

  drawConnections() {
    for (let i = 0; i < this.particles.length; i++) {
      for (let j = i + 1; j < this.particles.length; j++) {
        const dx = this.particles[i].x - this.particles[j].x
        const dy = this.particles[i].y - this.particles[j].y
        const distance = Math.sqrt(dx * dx + dy * dy)

        if (distance < this.connectionDistance) {
          const opacity = 1 - distance / this.connectionDistance
          this.ctx.beginPath()
          this.ctx.strokeStyle = `rgba(0, 240, 255, ${opacity * 0.3})`
          this.ctx.lineWidth = 1
          this.ctx.moveTo(this.particles[i].x, this.particles[i].y)
          this.ctx.lineTo(this.particles[j].x, this.particles[j].y)
          this.ctx.stroke()
        }
      }
    }
  }

  update() {
    for (const particle of this.particles) {
      particle.x += particle.vx
      particle.y += particle.vy

      // Bounce off edges
      if (particle.x < 0 || particle.x > this.canvas.width) {
        particle.vx *= -1
      }
      if (particle.y < 0 || particle.y > this.canvas.height) {
        particle.vy *= -1
      }

      // Keep particles in bounds
      particle.x = Math.max(0, Math.min(this.canvas.width, particle.x))
      particle.y = Math.max(0, Math.min(this.canvas.height, particle.y))
    }
  }

  animate() {
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)

    this.drawConnections()

    for (const particle of this.particles) {
      this.drawParticle(particle)
    }

    this.update()

    requestAnimationFrame(() => this.animate())
  }
}

// Inicializar cuando el DOM esté listo
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    new ParticleSystem()
  })
} else {
  new ParticleSystem()
}
