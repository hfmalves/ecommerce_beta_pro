                        <div class="d-flex flex-column gap-2 mt-2 showcase-drag__block" data-block-id="<?= (int) $block['id'] ?>"
                             draggable="false" @keydown.alt.up.prevent="move($event, -1)" @keydown.alt.down.prevent="move($event, 1)">
                            <div class="showcase-drag__toolbar">
                            <div class="showcase-drag__actions">
                                <button type="button" class="btn btn-outline-danger btn-sm" @click.stop="requestDelete($event)" :disabled="saving" title="Eliminar bloco" aria-label="Eliminar bloco">
                                    <i class="bx bx-trash" aria-hidden="true"></i> Eliminar
                                </button>
                            </div>
                            <div class="showcase-drag__handle" tabindex="0" role="button" aria-label="Mover bloco <?= esc($block['block_type'], 'attr') ?>. Use arrastar ou Alt e as setas."
                                 draggable="true" @dragstart.stop="dragStart($event)"
                                 title="Arraste para mover · Alt + ↑/↓ para usar o teclado">
                                <span aria-hidden="true">⠿</span>
                                <strong><?= esc($block['block_type']) ?></strong>
                                <small>Arrastar para mover</small>
                            </div>
                            </div>
                                <iframe class="showcase-drag__block-preview" title="Pré-visualização de <?= esc($block['block_type'], 'attr') ?>" loading="lazy"
                                        x-data="showcasePreviewFrame"
                                        src="<?= base_url('admin/website/showcases/block-preview/' . (int) $block['home_id'] . '/' . (int) $block['id']) ?>"
                                        referrerpolicy="same-origin"></iframe>
                        </div>
        
