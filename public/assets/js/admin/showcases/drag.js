window.ShowcaseAdmin = window.ShowcaseAdmin || {};
window.ShowcaseAdmin.drag = () => ({
        draggedEl: null,
        targetEl: null,
        after: false,
        before: [],
        saved: [],
        init() { this.saved = this.ids(); },
        blocks() { return Array.from(this.$root.querySelectorAll('[data-block-id]')); },
        ids() { return this.blocks().map(el => Number(el.dataset.blockId)); },
        clearTargets() {
            this.blocks().forEach(el => el.classList.remove('drop-before', 'drop-after'));
            this.targetEl = null;
        },
        dragStart(event) {
            if (this.saving) { event.preventDefault(); return; }
            this.before = this.ids();
            this.draggedEl = event.currentTarget.closest('[data-block-id]');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', this.draggedEl.dataset.blockId);
            event.dataTransfer.setDragImage(this.draggedEl.querySelector('.showcase-drag__handle'), 20, 15);
            this.draggedEl.classList.add('is-dragging');
            this.message = 'Escolha a posição e solte o bloco. Esc para cancelar.';
        },
        dragOver(event) {
            if (!this.draggedEl || this.saving) return;
            event.dataTransfer.dropEffect = 'move';
            const block = event.target.closest('[data-block-id]');
            this.clearTargets();
            if (!block || block === this.draggedEl || !this.$root.contains(block)) return;
            const rect = block.getBoundingClientRect();
            this.after = event.clientY > rect.top + rect.height / 2;
            this.targetEl = block;
            block.classList.add(this.after ? 'drop-after' : 'drop-before');
            if (event.clientY < 100) window.scrollBy(0, -22);
            else if (event.clientY > window.innerHeight - 100) window.scrollBy(0, 22);
        },
        async drop() {
            if (!this.draggedEl || !this.targetEl) return;
            const moved = this.draggedEl;
            const target = this.targetEl;
            target.parentNode.insertBefore(moved, this.after ? target.nextSibling : target);
            this.dragEnd();
            if (JSON.stringify(this.before) !== JSON.stringify(this.ids())) await this.save();
        },
        dragEnd() {
            if (this.draggedEl) this.draggedEl.classList.remove('is-dragging');
            this.draggedEl = null;
            this.clearTargets();
            if (!this.saving && !this.failed) this.message = 'Arraste um bloco para alterar a ordem.';
        },
        cancel() { if (this.draggedEl) { this.dragEnd(); this.message = 'Movimento cancelado.'; } },
        async move(event, direction) {
            if (this.saving) return;
            const block = event.currentTarget;
            const blocks = this.blocks();
            const index = blocks.indexOf(block);
            const target = blocks[index + direction];
            if (!target) return;
            block.parentNode.insertBefore(block, direction < 0 ? target : target.nextSibling);
            block.querySelector('.showcase-drag__handle').focus();
            await this.save();
        },
        async save() {
            if (this.saving) return;
            this.saving = true;
            this.failed = false;
            this.message = 'A guardar a ordem…';
            const order = this.ids();
            try {
                const response = await fetch(this.$root.dataset.saveUrl, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ home_id: Number(this.$root.dataset.homeId), block_ids: order,
                        [this.$root.dataset.csrfName]: this.$root.dataset.csrfHash })
                });
                const result = await response.json();
                if (result.csrf) {
                    this.$root.dataset.csrfName = result.csrf.token;
                    this.$root.dataset.csrfHash = result.csrf.hash;
                }
                if (!response.ok || result.status !== 'success') throw new Error(result.message || 'Falha ao guardar.');
                this.saved = order;
                this.message = '✓ Ordem guardada.';
            } catch (error) {
                this.failed = true;
                this.message = 'Ordem não confirmada. ' + error.message;
            } finally { this.saving = false; }
        }

});
