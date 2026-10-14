window.ShowcaseAdmin = window.ShowcaseAdmin || {};
window.ShowcaseAdmin.add = () => ({
        selectedType: '',
        addError: '',
        async addBlock() {
            if (this.saving || !this.selectedType) return;
            this.saving = true;
            this.addError = '';
            try {
                const result = await this.request(this.$root.dataset.addUrl, { block_type: this.selectedType });
                this.$refs.deleteModal.insertAdjacentHTML('beforebegin', result.block_html);
                // Alpine's mutation observer initializes the new card's event handlers.
                bootstrap.Modal.getOrCreateInstance(this.$refs.addModal).hide();
                this.selectedType = '';
                this.message = result.message;
                this.saved = this.ids();
            } catch (error) {
                this.addError = error.message;
            } finally { this.saving = false; }
        },

});
