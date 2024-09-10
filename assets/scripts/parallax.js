import gsap from 'gsap'
import Rellax from 'rellax'

const mouseParallax = () => {
    const elements = document.querySelectorAll('.mouseparallax')
    document.addEventListener('mousemove', (e) => {
        const x = e.clientX / window.innerWidth
        const y = e.clientY / window.innerHeight

        elements.forEach((layer) => {
            const mouseX = (x - 0.5) * -40
            const mouseY = (y - 0.5) * -40

            // layer.style.transform = `translate(${xMove}px, ${yMove}px) scale(1.25)`
            gsap.to(layer, 3, { x: mouseX, y: mouseY })

        })
    })
}

document.addEventListener('DOMContentLoaded', mouseParallax)

document.addEventListener('DOMContentLoaded', () => {

    // Parallax Animation
    Rellax('[data-parallax]', {
        // center: false,
        breakpoints: [640, 980, 1440],
    })
})