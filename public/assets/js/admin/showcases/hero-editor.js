window.ShowcaseAdmin = window.ShowcaseAdmin || {};
window.ShowcaseAdmin.hero = () => ({
    heroCard: null, heroBlockId: null, heroRevision: '', heroSlides: [], heroKey: 0,
    heroActive: false, heroAutoplay: true, heroSeconds: 5,
    heroLoading: false, heroBusy: false, heroDirty: false, heroPreviewed: false,
    heroError: '', heroNotice: '',
    async openHero(event) {
        if (this.saving) return;
        this.heroCard = event.target.closest('[data-block-id]');
        this.heroBlockId = Number(this.heroCard.dataset.blockId);
        this.heroRevision = '';
        this.heroDirty = false;
        this.heroPreviewed = false;
        this.heroError = '';
        this.heroNotice = '';
        this.heroSlides = [];
        this.heroLoading = true;
        bootstrap.Offcanvas.getOrCreateInstance(this.$refs.heroEditor).show();
        try {
            const response = await fetch(event.currentTarget.dataset.settingsUrl, {
                credentials: 'same-origin', headers: { Accept: 'application/json' }
            });
            const result = await response.json();
            if (!response.ok || result.status !== 'success') throw new Error(result.message || 'Não foi possível carregar o bloco.');
            if (result.csrf) {
                this.$root.dataset.csrfName = result.csrf.token;
                this.$root.dataset.csrfHash = result.csrf.hash;
            }
            const settings = result.settings || {};
            this.heroSlides = (Array.isArray(settings.slides) ? settings.slides : []).map(slide => ({
                key: ++this.heroKey, title: slide.title || '', description: slide.description || '',
                button_text: slide.button_text || '', button_link: slide.button_link || '',
                background_image: slide.background_image || ''
            }));
            this.heroActive = Boolean(result.is_active);
            this.heroAutoplay = settings.config?.autoplay !== false;
            this.heroSeconds = Number(settings.config?.autoplay_delay ?? 5000) / 1000;
            this.heroRevision = result.revision;
        } catch (error) { this.heroError = error.message; }
        finally { this.heroLoading = false; }
    },
    heroChanged() { this.heroDirty = true; this.heroNotice = ''; },
    addHeroSlide() {
        if (this.heroSlides.length >= 20) return;
        this.heroSlides.push({ key: ++this.heroKey, title: '', description: '', button_text: '', button_link: '', background_image: '' });
        this.heroChanged();
    },
    moveHeroSlide(index, direction) {
        const destination = index + direction;
        if (destination < 0 || destination >= this.heroSlides.length) return;
        const [slide] = this.heroSlides.splice(index, 1);
        this.heroSlides.splice(destination, 0, slide);
        this.heroChanged();
    },
    removeHeroSlide(index) { this.heroSlides.splice(index, 1); this.heroChanged(); },
    heroImage(slide) {
        const path = slide.background_image;
        if (!path || path.includes('..') || path.includes(':')) return this.$root.dataset.placeholderUrl;
        const encoded = path.split('/').map(encodeURIComponent).join('/');
        return path.startsWith('uploads/') ? this.$root.dataset.publicBase + encoded : this.$root.dataset.heroImageBase + encoded;
    },
    heroImageError(event) {
        if (event.target.src !== this.$root.dataset.placeholderUrl) event.target.src = this.$root.dataset.placeholderUrl;
    },
    heroPayload() {
        return {
            block_id: this.heroBlockId, revision: this.heroRevision, is_active: this.heroActive,
            settings: {
                autoplay: this.heroAutoplay, delay: Math.round(Number(this.heroSeconds) * 1000),
                slides: this.heroSlides.map(slide => ({ title: slide.title, description: slide.description,
                    background_image: slide.background_image, button_text: slide.button_text, button_link: slide.button_link }))
            }
        };
    },
    async previewHero() {
        if (this.heroBusy || this.heroLoading) return;
        this.heroBusy = true;
        this.heroError = '';
        try {
            const result = await this.request(this.$root.dataset.heroPreviewUrl, this.heroPayload());
            this.heroCard.querySelector('iframe').srcdoc = result.html;
            this.heroPreviewed = true;
            this.heroNotice = result.message;
        } catch (error) { this.heroError = error.message; }
        finally { this.heroBusy = false; }
    },
    async saveHero() {
        if (this.heroBusy || this.heroLoading) return;
        this.heroBusy = true;
        this.heroError = '';
        try {
            const result = await this.request(this.$root.dataset.heroSaveUrl, this.heroPayload());
            this.heroRevision = result.revision;
            this.heroDirty = false;
            this.heroNotice = result.message;
            this.resetHeroPreview();
        } catch (error) { this.heroError = error.message; }
        finally { this.heroBusy = false; }
    },
    resetHeroPreview() {
        const frame = this.heroCard?.querySelector('iframe');
        if (frame) {
            frame.removeAttribute('srcdoc');
            const url = new URL(frame.src);
            url.searchParams.set('preview', Date.now());
            frame.src = url.toString();
        }
        this.heroPreviewed = false;
    },
    closeHero() {
        if (this.heroBusy || this.heroLoading) return;
        if (this.heroDirty) { bootstrap.Modal.getOrCreateInstance(this.$refs.heroDiscardModal).show(); return; }
        if (this.heroPreviewed) this.resetHeroPreview();
        bootstrap.Offcanvas.getOrCreateInstance(this.$refs.heroEditor).hide();
    },
    keepEditingHero() { bootstrap.Modal.getOrCreateInstance(this.$refs.heroDiscardModal).hide(); },
    discardHero() {
        this.heroDirty = false;
        if (this.heroPreviewed) this.resetHeroPreview();
        bootstrap.Modal.getOrCreateInstance(this.$refs.heroDiscardModal).hide();
        bootstrap.Offcanvas.getOrCreateInstance(this.$refs.heroEditor).hide();
    },
    async uploadHeroImage(event, index) {
        const file = event.target.files[0];
        if (!file || this.heroBusy) return;
        this.heroBusy = true;
        this.heroError = '';
        const form = new FormData();
        form.append('home_id', this.$root.dataset.homeId);
        form.append('block_id', this.heroBlockId);
        form.append('image', file);
        form.append(this.$root.dataset.csrfName, this.$root.dataset.csrfHash);
        try {
            const response = await fetch(this.$root.dataset.heroUploadUrl, { method: 'POST', credentials: 'same-origin', body: form });
            const result = await response.json();
            if (result.csrf) { this.$root.dataset.csrfName = result.csrf.token; this.$root.dataset.csrfHash = result.csrf.hash; }
            if (!response.ok || result.status !== 'success') throw new Error(result.message || 'Falha ao carregar a imagem.');
            this.heroSlides[index].background_image = result.path;
            this.heroChanged();
            this.heroNotice = result.message;
        } catch (error) { this.heroError = error.message; }
        finally { this.heroBusy = false; event.target.value = ''; }
    }
});
