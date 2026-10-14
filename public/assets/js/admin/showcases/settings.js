document.addEventListener('alpine:init', () => {
    Alpine.data('showcaseSettings', () => ({
        form: {},
        loading: false,
        init() {
            this.form = JSON.parse(this.$root.dataset.settings);
        },
        async submit() {
            if (this.loading) return;
            if (!this._formHandler) {
                this._formHandler = formHandler(this.$root.dataset.saveUrl, {}, { resetOnSuccess: false });
            }
            this._formHandler.form = JSON.parse(JSON.stringify(this.form));
            this.loading = true;
            try {
                await this._formHandler.submit();
            } catch (error) {
                showToast('Falha de comunicação com o servidor.', 'error');
            } finally {
                this.loading = false;
            }
        }
    }));
});
