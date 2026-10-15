<div class="offcanvas offcanvas-end showcase-hero-editor" tabindex="-1" x-ref="heroEditor" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="heroEditorTitle">
    <div class="offcanvas-header border-bottom"><div><h5 id="heroEditorTitle">Editar slides</h5><small class="text-muted">Conteúdo e opções deste bloco</small></div><button type="button" class="btn-close" @click="closeHero()" :disabled="heroBusy" aria-label="Fechar"></button></div>
    <div class="offcanvas-body">
        <p x-show="heroLoading" role="status">A carregar os slides…</p>
        <p class="text-danger" x-show="heroError" x-text="heroError" role="alert"></p>
        <p class="text-success" x-show="heroNotice" x-text="heroNotice" role="status"></p>
        <fieldset :disabled="heroBusy || heroLoading || !heroRevision">
            <label class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" x-model="heroActive" @change="heroChanged()"><span>Bloco ativo</span></label>
            <div class="row mb-4"><div class="col-6"><label class="form-check form-switch"><input class="form-check-input" type="checkbox" x-model="heroAutoplay" @change="heroChanged()"><span>Reprodução automática</span></label></div><div class="col-6"><label class="form-label">Intervalo (segundos)</label><input class="form-control" type="number" min="1" max="30" x-model.number="heroSeconds" @input="heroChanged()"></div></div>
            <template x-for="(slide, index) in heroSlides" :key="slide.key">
                <section class="showcase-hero-slide">
                    <header><strong x-text="'Slide ' + (index + 1)"></strong><div>
                        <button type="button" class="btn btn-light btn-sm" @click="moveHeroSlide(index, -1)" :disabled="index === 0" aria-label="Subir slide">↑</button>
                        <button type="button" class="btn btn-light btn-sm" @click="moveHeroSlide(index, 1)" :disabled="index === heroSlides.length - 1" aria-label="Descer slide">↓</button>
                        <button type="button" class="btn btn-outline-danger btn-sm" @click="removeHeroSlide(index)">Retirar slide</button>
                    </div></header>
                    <img class="showcase-hero-slide__image" :src="heroImage(slide)" @error="heroImageError($event)" alt="Imagem do slide">
                    <label class="form-label">Carregar imagem</label><input class="form-control mb-2" type="file" accept="image/jpeg,image/png,image/webp,image/gif,image/avif" @change="uploadHeroImage($event, index)">
                    <small class="text-muted d-block mb-3">Até 5 MB. Se não houver imagem, será apresentado um placeholder.</small>
                    <label class="form-label">Ficheiro da imagem</label><input class="form-control mb-3" type="text" x-model="slide.background_image" @input="heroChanged()" placeholder="hero_1.jpg ou uploads/hero/imagem.jpg">
                    <label class="form-label">Título</label><input class="form-control mb-3" type="text" maxlength="255" x-model="slide.title" @input="heroChanged()">
                    <label class="form-label">Descrição</label><textarea class="form-control mb-3" rows="3" maxlength="2000" x-model="slide.description" @input="heroChanged()"></textarea>
                    <label class="form-label">Texto do botão</label><input class="form-control mb-3" type="text" maxlength="100" x-model="slide.button_text" @input="heroChanged()">
                    <label class="form-label">Destino do botão</label><input class="form-control" type="text" maxlength="2048" x-model="slide.button_link" @input="heroChanged()" placeholder="/category/colecao">
                </section>
            </template>
            <button type="button" class="btn btn-outline-primary w-100" @click="addHeroSlide()" :disabled="heroSlides.length >= 20">+ Adicionar slide</button>
        </fieldset>
    </div>
    <div class="showcase-hero-editor__footer border-top"><small x-show="heroDirty" class="text-warning">Alterações por guardar</small><div>
        <button type="button" class="btn btn-secondary" @click="closeHero()" :disabled="heroBusy">Cancelar</button>
        <button type="button" class="btn btn-outline-primary" @click="previewHero()" :disabled="heroBusy || heroLoading || !heroRevision">Pré-visualizar</button>
        <button type="button" class="btn btn-primary" @click="saveHero()" :disabled="heroBusy || heroLoading || !heroRevision">Guardar</button>
    </div></div>
</div>
<div class="modal fade" tabindex="-1" x-ref="heroDiscardModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="heroDiscardTitle">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 id="heroDiscardTitle">Descartar alterações?</h5></div><div class="modal-body">Existem alterações nos slides que ainda não foram guardadas.</div><div class="modal-footer"><button type="button" class="btn btn-secondary" @click="keepEditingHero()">Continuar a editar</button><button type="button" class="btn btn-danger" @click="discardHero()">Descartar alterações</button></div></div></div>
</div>
