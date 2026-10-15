<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Módulos
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-8">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0 fw-bold">Gestão de Blocos</h4>
                    <small class="text-muted">Visualize, edite ou remova blocos</small>
                </div>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#chooseShowcaseBlock">
                    <i class="bx bx-plus-circle me-1"></i> Novo Bloco
                </button>
            </div>
            <div class="card-body">
                <link rel="stylesheet" href="<?= base_url('assets/css/admin/showcase-drag.css') ?>">
                <div class="table-responsive showcase-drag" x-data="showcasesApp()"
                     data-home-id="<?= (int) $showcase['id'] ?>"
                     data-save-url="<?= base_url('admin/website/showcases/reorder-blocks') ?>"
                     data-delete-url="<?= base_url('admin/website/showcases/delete-block') ?>"
                     data-add-url="<?= base_url('admin/website/showcases/add-block') ?>"
                     data-hero-save-url="<?= base_url('admin/website/showcases/save-hero') ?>"
                     data-hero-preview-url="<?= base_url('admin/website/showcases/preview-hero') ?>"
                     data-hero-upload-url="<?= base_url('admin/website/showcases/upload-hero-image') ?>"
                     data-block-save-url="<?= base_url('admin/website/showcases/save-block') ?>"
                     data-block-preview-url="<?= base_url('admin/website/showcases/preview-block') ?>"
                     data-block-upload-url="<?= base_url('admin/website/showcases/upload-block-image') ?>"
                     data-public-base="<?= base_url() ?>" data-hero-image-base="<?= base_url('uploads/hero/') ?>"
                     data-placeholder-url="<?= base_url('assets/images/slide-placeholder.svg') ?>"
                     data-csrf-name="<?= esc(csrf_token(), 'attr') ?>" data-csrf-hash="<?= esc(csrf_hash(), 'attr') ?>"
                     @dragover.prevent="dragOver($event)" @drop.prevent="drop($event)" @dragend="dragEnd()"
                     @keydown.escape.window="cancel()" :aria-busy="saving">
                    <div class="showcase-drag__status" role="status" aria-live="polite" :class="{'text-danger': failed}">
                        <span x-text="message">Arraste um bloco para alterar a ordem.</span>
                        <button type="button" class="btn btn-link btn-sm" x-show="failed" x-cloak @click="save()">Tentar guardar novamente</button>
                    </div>
                    <?php foreach ($blocks as $index => $block): ?>
                        <?= view('admin/website/showcases/block_card', ['block' => $block, 'blockImages' => $images[$block['id']] ?? []]) ?>
                    <?php endforeach ?>
                    <?= view('admin/website/showcases/hero_editor') ?>
                    <?= view('admin/website/showcases/block_editor') ?>
                    <div class="modal fade" id="deleteShowcaseBlock" x-ref="deleteModal" tabindex="-1" aria-labelledby="deleteShowcaseBlockTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title" id="deleteShowcaseBlockTitle">Eliminar bloco?</h5><button type="button" class="btn-close" @click="cancelDelete()" :disabled="saving" aria-label="Fechar"></button></div>
                            <div class="modal-body"><p>Vai eliminar o bloco <strong x-text="deleteName"></strong> desta vitrine.</p><p class="text-muted mb-0">Esta ação não pode ser desfeita.</p><p class="text-danger mt-3 mb-0" x-show="deleteError" x-text="deleteError" role="alert"></p></div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" @click="cancelDelete()" :disabled="saving">Cancelar</button><button type="button" class="btn btn-danger" @click="confirmDelete()" :disabled="saving" x-text="saving ? 'A eliminar…' : 'Eliminar bloco'"></button></div>
                        </div></div>
                    </div>
                    <div class="modal fade" id="chooseShowcaseBlock" x-ref="addModal" tabindex="-1" aria-labelledby="chooseShowcaseBlockTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title" id="chooseShowcaseBlockTitle">Que tipo de bloco pretende adicionar?</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button></div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <?php foreach ($blockChoices as $type => $choice): ?>
                                        <div class="col-12 col-sm-6 col-lg-4">
                                            <button type="button" class="showcase-block-choice" :class="{'is-selected': selectedType === '<?= $type ?>'}" :aria-pressed="selectedType === '<?= $type ?>'" @click="selectedType = '<?= $type ?>'" :disabled="saving">
                                                <?php foreach ($choice['images'] as $previewImage): ?><img src="<?= esc($previewImage, 'attr') ?>" alt="" loading="lazy"><?php endforeach ?>
                                                <strong><?= esc($choice['name']) ?></strong><small><?= esc($choice['description']) ?></small>
                                            </button>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                                <p class="text-muted mt-3 mb-0">O novo bloco fica no fim da vitrine, inativo e sem conteúdo.</p>
                                <p class="text-danger mt-2" x-show="addError" x-text="addError" role="alert"></p>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" @click="addBlock()" :disabled="!selectedType || saving" x-text="saving ? 'A adicionar…' : 'Adicionar bloco'"></button></div>
                        </div></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-4"
         x-data="showcaseSettings"
         data-settings="<?= esc($settingsJson, 'attr') ?>"
         data-save-url="<?= base_url('admin/website/showcases/update') ?>"
    >
        <form class="card shadow-sm border-0">
            <div class="card-header">
                <h4 class="card-title mb-0 fw-bold">Definições</h4>
                <small class="text-muted">Gestão de definições da Vitrine</small>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Nome</label>
                        <input type="text"
                               class="form-control"
                               x-model="form.name">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Home Code</label>
                        <input type="text"
                               class="form-control"
                               value="<?= esc($showcase['home_code']) ?>"
                               disabled>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   x-model="form.is_active" :true-value="1" :false-value="0"
                                   >
                            <label class="form-check-label">Ativo</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   x-model="form.is_default" :true-value="1" :false-value="0"
                                   >
                            <label class="form-check-label">Default</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Ativo desde</label>
                        <input type="datetime-local"
                               class="form-control"
                               x-model="form.active_start">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Ativo até</label>
                        <input type="datetime-local"
                               class="form-control"
                               x-model="form.active_end">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Criado em</label>
                        <input type="text"
                               class="form-control"
                               value="<?= esc($showcase['created_at']) ?>"
                               disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Atualizado em</label>
                        <input type="text"
                               class="form-control"
                               value="<?= esc($showcase['updated_at']) ?>"
                               disabled>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="button"
                        class="btn btn-primary"
                        @click="submit()"
                        :disabled="loading">
                    <template x-if="!loading">
                        <span><i class="mdi mdi-content-save-outline me-1"></i> Guardar</span>
                    </template>
                    <template x-if="loading">
                        <span><i class="fa fa-spinner fa-spin me-1"></i> A guardar…</span>
                    </template>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content-script') ?>
<script src="<?= base_url('assets/js/admin/showcases/request.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/drag.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/add.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/delete.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/settings.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/preview-frame.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/hero-editor.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/block-fields.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcases/block-editor.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/showcasesApp.js') ?>"></script>
<?= $this->endSection() ?>
