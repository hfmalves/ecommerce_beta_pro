const showcaseRowKeys = new WeakMap();
let showcaseRowSequence = 0;
document.addEventListener('alpine:init', () => {
    Alpine.data('showcaseFields', (fields, values, options) => ({
        fields, values, options,
        fieldTemplate: document.getElementById('showcaseFieldTemplate').innerHTML,
        init() {
            this.fields.forEach(field => {
                const value = this.values[field.key];
                if (field.type === 'group' && (!value || Array.isArray(value))) this.values[field.key] = {};
                else if (field.type === 'list' && !Array.isArray(value)) this.values[field.key] = [];
                else if (field.type === 'boolean') this.values[field.key] = Boolean(Number(value ?? field.default ?? 0));
                else if (!['group', 'list'].includes(field.type) && (value === undefined || value === null)) this.values[field.key] = field.default ?? '';
                if (field.type === 'date' && typeof this.values[field.key] === 'string') this.values[field.key] = this.values[field.key].slice(0, 10);
            });
        },
        changed() { this.$dispatch('block-fields-changed'); },
        rowKey(item) { if (!showcaseRowKeys.has(item)) showcaseRowKeys.set(item, ++showcaseRowSequence); return showcaseRowKeys.get(item); },
        choices(field) {
            return field.type === 'reference' ? this.options[field.source] || []
                : Object.entries(field.options || {}).map(([id, label]) => ({ id, label }));
        },
        addItem(field) { this.values[field.key].push({}); this.changed(); },
        removeItem(field, index) { this.values[field.key].splice(index, 1); this.changed(); },
        moveItem(field, index, direction) {
            const rows = this.values[field.key], target = index + direction;
            if (target < 0 || target >= rows.length) return;
            const [item] = rows.splice(index, 1); rows.splice(target, 0, item); this.changed();
        },
        image(field) {
            const path = this.values[field.key];
            if (!path || path.includes('..') || path.includes(':')) return this.options.placeholder;
            return (path.startsWith('uploads/') ? this.options.publicBase : this.options.imageBase) + path.split('/').map(encodeURIComponent).join('/');
        },
        imageError(event) { if (event.target.src !== this.options.placeholder) event.target.src = this.options.placeholder; },
        upload(event, field) { this.$dispatch('block-editor-upload', { event, values: this.values, key: field.key }); }
    }));
});
