const content = document.getElementById('showcase-preview-content');
const instances = [];
function initializeSliders(root) {
    root.querySelectorAll('.js-swiper-slider').forEach(element => {
        if (element.swiper) { element.swiper.update(); return; }
        const settings = JSON.parse(element.dataset.settings || '{}');
        const count = element.querySelectorAll('.swiper-slide').length;
        settings.loop = count > 1 && Boolean(settings.loop);
        settings.fadeEffect = { crossFade: true };
        if (count < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) settings.autoplay = false;
        if (settings.pagination) settings.pagination.el = element.parentElement.querySelector(settings.pagination.el);
        if (settings.navigation) {
            settings.navigation.nextEl = element.parentElement.querySelector(settings.navigation.nextEl);
            settings.navigation.prevEl = element.parentElement.querySelector(settings.navigation.prevEl);
        }
        instances.push(new Swiper(element, settings));
    });
}
function notifyHeight() {
    window.parent.postMessage({ type: 'showcase-preview-height', height: Math.ceil(content.getBoundingClientRect().height) + 4 }, window.location.origin);
}
initializeSliders(content);
new ResizeObserver(notifyHeight).observe(content);
window.addEventListener('load', notifyHeight);
document.addEventListener('shown.bs.tab', () => { instances.forEach(instance => instance.update()); notifyHeight(); });
document.addEventListener('click', event => {
    const link = event.target.closest('a');
    if (link && !link.hasAttribute('data-bs-toggle')) event.preventDefault();
});
document.addEventListener('submit', event => event.preventDefault());
document.querySelectorAll('img').forEach(image => image.addEventListener('load', notifyHeight));
