const { test } = require('node:test');
const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const vm = require('node:vm');
const jsRoot = require('node:path').join(__dirname, '../../public/assets/js/admin');
const source = ['showcases/request.js', 'showcases/drag.js', 'showcases/add.js', 'showcases/delete.js', 'showcases/hero-editor.js', 'showcases/block-editor.js', 'showcasesApp.js']
    .map(file => readFileSync(require('node:path').join(jsRoot, file), 'utf8')).join('\n');

test('formulário recursivo renderiza por x-html em todos os níveis', () => {
    const markup = readFileSync(require('node:path').join(__dirname, '../../app/Views/admin/website/showcases/block_editor.php'), 'utf8');
    const mounts = markup.match(/<div x-data="showcaseFields\([^"]+"[^>]*>/g);
    assert.equal(mounts.length, 3);
    mounts.forEach(mount => assert.match(mount, /x-html="fieldTemplate"/));
    let factory;
    const template = '<template x-for="field in fields"></template>';
    vm.runInNewContext(readFileSync(require('node:path').join(jsRoot, 'showcases/block-fields.js'), 'utf8'), {
        document: { addEventListener: (_, callback) => callback(), getElementById: () => ({ innerHTML: template }) },
        Alpine: { data: (_, callback) => { factory = callback; } },
    });
    const draft = { config: { title: 'Produtos em destaque' }, items: [] };
    const root = factory([{ key: 'config', type: 'group' }, { key: 'items', type: 'list' }], draft, {});
    root.init();
    assert.equal(root.fieldTemplate, template);
    const config = factory([{ key: 'title', type: 'string' }], draft.config, {});
    config.init();
    config.values.title = 'Nova seleção';
    assert.equal(draft.config.title, 'Nova seleção');
    root.$dispatch = () => {};
    root.addItem({ key: 'items' });
    const products = [{ id: 7, label: 'Produto de teste' }];
    const row = factory([{ key: 'product_id', type: 'reference' }], draft.items[0], { products });
    row.init();
    assert.equal(row.choices({ type: 'reference', source: 'products' }), products);
    row.values.product_id = '7';
    assert.equal(draft.items[0].product_id, '7');
});

function editor({ ok = true } = {}) {
    let factory, requests = 0, removed = false, reloaded = false;
    const block = { dataset: { blockId: '4' }, querySelector: () => ({ textContent: 'hero' }), remove() { removed = true; } };
    const context = {
        document: { addEventListener(name, callback) { callback(); } },
        Alpine: { data(name, callback) { factory = callback; } },
        bootstrap: { Modal: { getOrCreateInstance: () => ({ show() {}, hide() {} }) } },
        window: { location: { reload() { reloaded = true; } } },
        fetch: async () => {
            requests++;
            return { ok, json: async () => ({ status: ok ? 'success' : 'error', message: 'Teste', csrf: { token: 'csrf', hash: 'fresh' } }) };
        },
    };
    vm.runInNewContext(source, context);
    const app = factory();
    app.$refs = { deleteModal: {} };
    app.$root = { dataset: { homeId: '1', csrfName: 'csrf', csrfHash: 'old', deleteUrl: '/delete', addUrl: '/add' }, querySelectorAll: () => removed ? [] : [block] };
    return { app, event: { target: { closest: () => block } }, state: () => ({ requests, removed, reloaded }) };
}

test('cancelar confirmação não elimina nem envia pedidos', async () => {
    const { app, event, state } = editor();
    app.requestDelete(event);
    app.cancelDelete();
    await app.confirmDelete();
    assert.deepEqual(state(), { requests: 0, removed: false, reloaded: false });
});

test('falha no servidor mantém o bloco visível', async () => {
    const { app, event, state } = editor({ ok: false });
    app.requestDelete(event);
    await app.confirmDelete();
    assert.equal(state().removed, false);
    assert.equal(app.saving, false);
    assert.equal(app.$root.dataset.csrfHash, 'fresh');
});

test('sucesso elimina apenas depois da confirmação do servidor', async () => {
    const { app, event, state } = editor();
    app.requestDelete(event);
    await app.confirmDelete();
    assert.equal(state().requests, 1);
    assert.equal(state().removed, true);
});

test('modal exige seleção e mantém-se aberto em caso de erro', async () => {
    const { app, state } = editor({ ok: false });
    await app.addBlock();
    assert.equal(state().requests, 0);
    app.selectedType = 'hero';
    await app.addBlock();
    assert.equal(state().reloaded, false);
    assert.equal(app.saving, false);
    assert.equal(app.addError, 'Teste');
});

test('adicionar insere o bloco devolvido pelo servidor sem recarregar', async () => {
    const { app, state } = editor();
    let inserted = '';
    app.$refs.addModal = {};
    app.$refs.deleteModal.insertAdjacentHTML = (position, html) => {
        assert.equal(position, 'beforebegin');
        inserted = html;
    };
    app.request = async () => ({ block_html: '<div data-block-id="5"></div>', message: 'Bloco adicionado.' });
    app.selectedType = 'hero';
    await app.addBlock();
    assert.equal(inserted, '<div data-block-id="5"></div>');
    assert.equal(state().reloaded, false);
    assert.equal(app.selectedType, '');
    assert.equal(app.saving, false);
});

test('pré-visualizar slides não guarda e mantém alterações pendentes', async () => {
    const { app } = editor();
    const frame = {};
    app.heroCard = { querySelector: () => frame };
    app.$root.dataset.heroPreviewUrl = '/preview';
    app.heroDirty = true;
    app.heroSlides = [{ key: 1, title: 'Alterado', description: '', background_image: '', button_text: '', button_link: '' }];
    app.request = async (url, payload) => {
        assert.equal(url, '/preview');
        assert.equal(payload.settings.slides[0].title, 'Alterado');
        assert.equal('key' in payload.settings.slides[0], false);
        return { html: '<html>preview</html>', message: 'Sem guardar' };
    };
    await app.previewHero();
    assert.equal(frame.srcdoc, '<html>preview</html>');
    assert.equal(app.heroDirty, true);
});

test('erro ao guardar mantém os slides e permite tentar novamente', async () => {
    const { app } = editor();
    app.heroDirty = true;
    app.heroSlides = [{ title: 'Não perder' }];
    app.request = async () => { throw new Error('Conflito'); };
    await app.saveHero();
    assert.equal(app.heroSlides[0].title, 'Não perder');
    assert.equal(app.heroDirty, true);
    assert.equal(app.heroBusy, false);
    assert.equal(app.heroError, 'Conflito');
});

test('guardar atualiza a revisão e só recarrega o iframe', async () => {
    const { app, state } = editor();
    let refreshed = false;
    app.heroDirty = true;
    app.request = async () => ({ revision: 'nova', message: 'Guardado' });
    app.resetHeroPreview = () => { refreshed = true; };
    await app.saveHero();
    assert.equal(app.heroRevision, 'nova');
    assert.equal(app.heroDirty, false);
    assert.equal(refreshed, true);
    assert.equal(state().reloaded, false);
});
