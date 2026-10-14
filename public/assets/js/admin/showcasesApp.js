document.addEventListener('alpine:init', () => {
    Alpine.data('showcasesApp', () => ({
        saving: false,
        failed: false,
        message: 'Arraste um bloco para alterar a ordem.',
        ...window.ShowcaseAdmin.request(),
        ...window.ShowcaseAdmin.drag(),
        ...window.ShowcaseAdmin.add(),
        ...window.ShowcaseAdmin.delete()
    }));
});