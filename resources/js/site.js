/*
 * Shared Alpine bootstrap for template front-ends. Each template's js/app.js
 * imports this and may register extra components before Alpine starts.
 */
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('siteHeader', () => ({
    mobileOpen: false,
    scrolled: false,
    init() {
        const update = () => (this.scrolled = window.scrollY > 10);
        update();
        window.addEventListener('scroll', update, { passive: true });
        this.$watch('mobileOpen', (open) => {
            document.body.classList.toggle('overflow-hidden', open);
        });
    },
}));

Alpine.data('accordion', (open = null) => ({
    open,
    toggle(key) {
        this.open = this.open === key ? null : key;
    },
    isOpen(key) {
        return this.open === key;
    },
}));

Alpine.data('gallery', (images = []) => ({
    images,
    index: 0,
    get current() {
        return this.images[this.index] ?? null;
    },
    next() {
        this.index = (this.index + 1) % this.images.length;
    },
    prev() {
        this.index = (this.index - 1 + this.images.length) % this.images.length;
    },
}));

export function startAlpine() {
    Alpine.start();
}

export default Alpine;
