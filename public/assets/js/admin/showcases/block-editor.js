window.ShowcaseAdmin = window.ShowcaseAdmin || {};
window.ShowcaseAdmin.blockEditor = () => ({
    blockEditorCard: null, blockEditorId: null, blockEditorRevision: '', blockEditorTitle: '',
    blockEditorDraft: {}, blockEditorSchema: [], blockEditorOptions: {}, blockEditorActive: false,
    blockEditorReady: false, blockEditorLoading: false, blockEditorBusy: false, blockEditorDirty: false,
    blockEditorPreviewed: false, blockEditorError: '', blockEditorNotice: '',
    async openBlockEditor(event) {
        if (this.saving) return;
        this.blockEditorCard = event.target.closest('[data-block-id]');
        this.blockEditorId = Number(this.blockEditorCard.dataset.blockId);
        this.blockEditorReady = false; this.blockEditorLoading = true; this.blockEditorRevision = '';
        this.blockEditorDirty = false; this.blockEditorPreviewed = false; this.blockEditorError = ''; this.blockEditorNotice = '';
        bootstrap.Offcanvas.getOrCreateInstance(this.$refs.blockEditor).show();
        try {
            const response = await fetch(event.currentTarget.dataset.settingsUrl, { credentials: 'same-origin', headers: { Accept: 'application/json' } });
            const result = await response.json();
            if (!response.ok || result.status !== 'success') throw new Error(result.message || 'Falha ao carregar o bloco.');
            if (result.csrf) { this.$root.dataset.csrfName = result.csrf.token; this.$root.dataset.csrfHash = result.csrf.hash; }
            this.blockEditorTitle = 'Editar ' + result.name;
            this.blockEditorSchema = result.schema;
            this.blockEditorDraft = result.settings && !Array.isArray(result.settings) ? result.settings : {};
            this.blockEditorOptions = { ...result.options, publicBase: this.$root.dataset.publicBase,
                imageBase: this.$root.dataset.publicBase + 'uploads/', placeholder: this.$root.dataset.placeholderUrl };
            this.blockEditorActive = Boolean(result.is_active); this.blockEditorRevision = result.revision;
            this.blockEditorReady = true;
        } catch (error) { this.blockEditorError = error.message; }
        finally { this.blockEditorLoading = false; }
    },
    blockChanged() { this.blockEditorDirty = true; this.blockEditorNotice = ''; },
    blockPayload() { return { block_id: this.blockEditorId, revision: this.blockEditorRevision, is_active: this.blockEditorActive, settings: this.blockEditorDraft }; },
    async previewBlockEditor() {
        if (this.blockEditorBusy || !this.blockEditorReady) return;
        this.blockEditorBusy = true; this.blockEditorError = '';
        try {
            const result = await this.request(this.$root.dataset.blockPreviewUrl, this.blockPayload());
            this.blockEditorCard.querySelector('iframe').srcdoc = result.html;
            this.blockEditorPreviewed = true; this.blockEditorNotice = result.message;
        } catch (error) { this.blockEditorError = error.message; }
        finally { this.blockEditorBusy = false; }
    },
    async saveBlockEditor() {
        if (this.blockEditorBusy || !this.blockEditorReady) return;
        this.blockEditorBusy = true; this.blockEditorError = '';
        try {
            const result = await this.request(this.$root.dataset.blockSaveUrl, this.blockPayload());
            this.blockEditorRevision = result.revision; this.blockEditorDirty = false; this.blockEditorNotice = result.message;
            this.resetBlockPreview();
        } catch (error) { this.blockEditorError = error.message; }
        finally { this.blockEditorBusy = false; }
    },
    resetBlockPreview() {
        const frame = this.blockEditorCard?.querySelector('iframe');
        if (frame) { frame.removeAttribute('srcdoc'); const url = new URL(frame.src); url.searchParams.set('preview', Date.now()); frame.src = url.toString(); }
        this.blockEditorPreviewed = false;
    },
    closeBlockEditor() {
        if (this.blockEditorBusy || this.blockEditorLoading) return;
        if (this.blockEditorDirty) { bootstrap.Modal.getOrCreateInstance(this.$refs.blockDiscardModal).show(); return; }
        if (this.blockEditorPreviewed) this.resetBlockPreview();
        bootstrap.Offcanvas.getOrCreateInstance(this.$refs.blockEditor).hide();
    },
    keepEditingBlock() { bootstrap.Modal.getOrCreateInstance(this.$refs.blockDiscardModal).hide(); },
    discardBlockEditor() {
        this.blockEditorDirty = false;
        if (this.blockEditorPreviewed) this.resetBlockPreview();
        bootstrap.Modal.getOrCreateInstance(this.$refs.blockDiscardModal).hide();
        bootstrap.Offcanvas.getOrCreateInstance(this.$refs.blockEditor).hide();
    },
    async uploadBlockEditorImage(event) {
        const { event: input, values, key } = event.detail, file = input.target.files[0];
        if (!file || this.blockEditorBusy) return;
        this.blockEditorBusy = true; this.blockEditorError = '';
        const form = new FormData(); form.append('home_id', this.$root.dataset.homeId); form.append('block_id', this.blockEditorId);
        form.append('image', file); form.append(this.$root.dataset.csrfName, this.$root.dataset.csrfHash);
        try {
            const response = await fetch(this.$root.dataset.blockUploadUrl, { method: 'POST', credentials: 'same-origin', body: form });
            const result = await response.json();
            if (result.csrf) { this.$root.dataset.csrfName = result.csrf.token; this.$root.dataset.csrfHash = result.csrf.hash; }
            if (!response.ok || result.status !== 'success') throw new Error(result.message || 'Falha ao carregar a imagem.');
            values[key] = result.path; this.blockChanged(); this.blockEditorNotice = result.message;
        } catch (error) { this.blockEditorError = error.message; }
        finally { this.blockEditorBusy = false; input.target.value = ''; }
    }
});
