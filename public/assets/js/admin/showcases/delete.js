window.ShowcaseAdmin = window.ShowcaseAdmin || {};
window.ShowcaseAdmin.delete = () => ({
        pendingDelete: null,
        deleteName: '',
        deleteError: '',
        requestDelete(event) {
            if (this.saving || this.draggedEl) return;
            const block = event.target.closest('[data-block-id]');
            if (!block) return;
            this.pendingDelete = block;
            this.deleteName = block.querySelector('.showcase-drag__handle strong').textContent;
            this.deleteError = '';
            bootstrap.Modal.getOrCreateInstance(this.$refs.deleteModal).show();
        },
        cancelDelete() {
            if (this.saving) return;
            bootstrap.Modal.getOrCreateInstance(this.$refs.deleteModal).hide();
            this.pendingDelete = null;
            this.deleteError = '';
        },
        async confirmDelete() {
            if (this.saving || !this.pendingDelete) return;
            const block = this.pendingDelete;
            this.saving = true;
            this.deleteError = '';
            this.failed = false;
            this.message = 'A eliminar o bloco…';
            try {
                await this.request(this.$root.dataset.deleteUrl, { block_id: Number(block.dataset.blockId) });
                block.remove();
                this.saved = this.ids();
                this.message = 'Bloco eliminado.';
                bootstrap.Modal.getOrCreateInstance(this.$refs.deleteModal).hide();
                this.pendingDelete = null;
            } catch (error) { this.deleteError = error.message; }
            finally { this.saving = false; }
        },

});
