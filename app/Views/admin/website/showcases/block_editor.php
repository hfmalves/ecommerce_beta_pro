<div class="offcanvas offcanvas-end showcase-block-editor" tabindex="-1" x-ref="blockEditor" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="blockEditorTitle">
    <div class="offcanvas-header border-bottom"><div><h5 id="blockEditorTitle" x-text="blockEditorTitle"></h5><small class="text-muted">Conteúdo e opções deste bloco</small></div><button type="button" class="btn-close" @click="closeBlockEditor()" :disabled="blockEditorBusy" aria-label="Fechar"></button></div>
    <div class="offcanvas-body" @block-fields-changed="blockChanged()" @block-editor-upload="uploadBlockEditorImage($event)">
        <p x-show="blockEditorLoading" role="status">A carregar…</p><p class="text-danger" x-show="blockEditorError" x-text="blockEditorError" role="alert"></p><p class="text-success" x-show="blockEditorNotice" x-text="blockEditorNotice" role="status"></p>
        <fieldset :disabled="blockEditorBusy || blockEditorLoading || !blockEditorRevision">
            <label class="form-check form-switch mb-4"><input class="form-check-input" type="checkbox" x-model="blockEditorActive" @change="blockChanged()"><span>Bloco ativo</span></label>
            <template x-if="blockEditorReady"><div x-data="showcaseFields(blockEditorSchema, blockEditorDraft, blockEditorOptions)" x-html="fieldTemplate"></div></template>
        </fieldset>
    </div>
    <div class="showcase-hero-editor__footer border-top"><small class="text-warning" x-show="blockEditorDirty">Alterações por guardar</small><div>
        <button type="button" class="btn btn-secondary" @click="closeBlockEditor()" :disabled="blockEditorBusy">Cancelar</button>
        <button type="button" class="btn btn-outline-primary" @click="previewBlockEditor()" :disabled="blockEditorBusy || !blockEditorReady">Pré-visualizar</button>
        <button type="button" class="btn btn-primary" @click="saveBlockEditor()" :disabled="blockEditorBusy || !blockEditorReady">Guardar</button>
    </div></div>
</div>
<div class="modal fade" tabindex="-1" x-ref="blockDiscardModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="blockDiscardTitle"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 id="blockDiscardTitle">Descartar alterações?</h5></div><div class="modal-body">Existem alterações por guardar neste bloco.</div><div class="modal-footer"><button type="button" class="btn btn-secondary" @click="keepEditingBlock()">Continuar a editar</button><button type="button" class="btn btn-danger" @click="discardBlockEditor()">Descartar alterações</button></div></div></div></div>
<template id="showcaseFieldTemplate">
    <template x-for="field in fields" :key="field.key">
        <div class="showcase-editor-field">
            <template x-if="field.type === 'group'"><section class="showcase-editor-group"><h6 x-text="field.label"></h6><div x-data="showcaseFields(field.fields, values[field.key], options)" x-html="fieldTemplate"></div></section></template>
            <template x-if="field.type === 'list'"><section class="showcase-editor-group">
                <h6 x-text="field.label"></h6>
                <template x-for="(item, index) in values[field.key]" :key="rowKey(item)"><section class="showcase-editor-item">
                    <header><strong x-text="field.label + ' · ' + (index + 1)"></strong><div><button class="btn btn-light btn-sm" type="button" @click="moveItem(field, index, -1)" :disabled="index === 0" aria-label="Subir">↑</button><button class="btn btn-light btn-sm" type="button" @click="moveItem(field, index, 1)" :disabled="index === values[field.key].length - 1" aria-label="Descer">↓</button><button class="btn btn-outline-danger btn-sm" type="button" @click="removeItem(field, index)">Retirar</button></div></header>
                    <div x-data="showcaseFields(field.fields, item, options)" x-html="fieldTemplate"></div>
                </section></template>
                <button class="btn btn-outline-primary w-100" type="button" @click="addItem(field)" :disabled="values[field.key].length >= 40">+ Adicionar elemento</button>
            </section></template>
            <template x-if="!['group','list'].includes(field.type)"><div>
                <label class="form-label" x-text="field.label"></label>
                <template x-if="field.type === 'boolean'"><input type="checkbox" class="form-check-input d-block" x-model="values[field.key]" @change="changed()"></template>
                <template x-if="field.type === 'text'"><textarea class="form-control" rows="3" maxlength="5000" x-model="values[field.key]" @input="changed()"></textarea></template>
                <template x-if="['reference','select'].includes(field.type)"><select class="form-select" x-model="values[field.key]" @change="changed()"><option value="">Selecionar…</option><template x-for="choice in choices(field)" :key="choice.id"><option :value="choice.id" x-text="choice.label"></option></template></select></template>
                <template x-if="field.type === 'image'"><div><img class="showcase-hero-slide__image" :src="image(field)" @error="imageError($event)" alt="Pré-visualização da imagem"><input class="form-control mb-2" type="file" accept="image/jpeg,image/png,image/webp,image/gif,image/avif" @change="upload($event, field)"><input class="form-control" type="text" x-model="values[field.key]" @input="changed()" placeholder="Ficheiro da imagem"><small class="text-muted">Até 5 MB. Sem imagem, apresenta um placeholder.</small></div></template>
                <template x-if="['string','url','email','number','date'].includes(field.type)"><input class="form-control" :type="field.type === 'number' ? 'number' : field.type === 'date' ? 'date' : field.type === 'email' ? 'email' : 'text'" :min="field.min" :max="field.max" maxlength="2048" x-model="values[field.key]" @input="changed()"></template>
            </div></template>
        </div>
    </template>
</template>
