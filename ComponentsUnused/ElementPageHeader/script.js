import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default (header) => {
    const refs = buildRefs(header)

    const shapeElement = refs.shape
    const mouse = { x: window.innerWidth / 2, y: window.innerHeight / 10 };
    // const scroll = { x: 0, y: 0 };

    const shapeRotation = { x: 0, y: 0 };

    // const distance = { y: 0 };

    // Update mouse position on the 'mousemove' event
    window.addEventListener('mousemove', (e) => {
        mouse.x = e.x;
        mouse.y = e.y;
    });

    // Update scroll distance on the 'scroll' event
    // window.addEventListener('scroll', () => {
    //     scroll.y = window.scrollY;
    // });

    // Smoothing factor for cursor movement speed (0 = smoother, 1 = instant)
    const speed = 0.005;

    // Start animation
    const tick = () => {
        shapeRotation.x += (mouse.x / 40 - shapeRotation.x) * speed;
        shapeRotation.y += (mouse.y / 40 - shapeRotation.y) * speed;
        const translateTransform = `rotateY(${-1 * shapeRotation.x + 20}deg) rotateX(${shapeRotation.y + 15}deg)`;
        shapeElement.style.transform = `${translateTransform}`;

        // distance.y += (scroll.y / 400 + distance.y) * speed
        // shapeElement.style.fontSize = `${distance.y + 2}rem`;

        // Request the next frame to continue the animation
        window.requestAnimationFrame(tick);
    }

    // Start the animation loop
    tick();
}
