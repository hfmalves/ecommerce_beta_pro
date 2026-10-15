document.addEventListener('alpine:init', () => {
    Alpine.data('showcasePreviewFrame', () => ({
        init() {
            this.receiveHeight = event => {
                if (event.origin !== window.location.origin || event.source !== this.$el.contentWindow
                    || event.data?.type !== 'showcase-preview-height' || !Number.isFinite(event.data.height)) return;
                this.$el.style.height = Math.max(100, Math.min(5000, event.data.height)) + 'px';
            };
            window.addEventListener('message', this.receiveHeight);
        },
        destroy() { window.removeEventListener('message', this.receiveHeight); }
    }));
});
