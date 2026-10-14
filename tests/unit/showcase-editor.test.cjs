const { test } = require('node:test');
const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const vm = require('node:vm');
const jsRoot = require('node:path').join(__dirname, '../../public/assets/js/admin');
const source = ['showcases/request.js', 'showcases/drag.js', 'showcases/add.js', 'showcases/delete.js', 'showcasesApp.js']
    .map(file => readFileSync(require('node:path').join(jsRoot, file), 'utf8')).join('\n');

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
