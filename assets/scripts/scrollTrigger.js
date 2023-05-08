import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export default () => ({
    removeBackdropBlur() {
        const mainNavigation = document.querySelector('.mainNavBlock');
        ScrollTrigger.getAll().find(trigger => trigger.isActive).trigger.dataset?.navstyle?.includes('blur') && mainNavigation.classList.remove('backdrop-blur-xl');
    },
    addBackdropBlur() {
        const mainNavigation = document.querySelector('.mainNavBlock');
        ScrollTrigger.getAll().find(trigger => trigger.isActive).trigger.dataset?.navstyle?.includes('blur') && mainNavigation.classList.add('backdrop-blur-xl');
    }
})