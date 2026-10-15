<?php

namespace App\Services;

/** Field definitions and validation shared by the normal forms and JSON endpoints. */
class ShowcaseBlockEditor
{
    public function schema(string $type): array
    {
        $title = $this->field('title', 'Título');
        $product = [$this->field('product_id', 'Produto', 'reference', ['source' => 'products']), $this->field('product_variant_id', 'Variante', 'reference', ['source' => 'variants'])];
        $carousel = [$this->field('items_limit', 'Limite de elementos', 'number', ['min' => 1, 'max' => 24, 'default' => 8]), $this->field('slides_desktop', 'Colunas no computador', 'number', ['min' => 1, 'max' => 6, 'default' => 4]), $this->field('slides_tablet', 'Colunas no tablet', 'number', ['min' => 1, 'max' => 6, 'default' => 3]), $this->field('slides_mobile', 'Colunas no telemóvel', 'number', ['min' => 1, 'max' => 3, 'default' => 2]), $this->field('autoplay_delay', 'Intervalo automático (ms)', 'number', ['min' => 0, 'max' => 30000, 'default' => 5000]), $this->field('loop', 'Repetir carrossel', 'boolean')];
        return match ($type) {
            'grid_banner' => [
                $this->group('config', 'Apresentação', [$this->field('columns_desktop', 'Colunas no computador', 'number', ['min' => 1, 'max' => 6, 'default' => 3]), $this->field('columns_tablet', 'Colunas no tablet', 'number', ['min' => 1, 'max' => 4, 'default' => 2]), $this->field('columns_mobile', 'Colunas no telemóvel', 'number', ['min' => 1, 'max' => 2, 'default' => 1])]),
                $this->rows('items', 'Banners', [$title, $this->field('subtitle', 'Subtítulo'), $this->field('image', 'Imagem', 'image'), $this->field('link', 'Destino', 'url'), $this->field('text_color', 'Cor do texto', 'select', ['options' => ['dark' => 'Escuro', 'light' => 'Claro'], 'default' => 'dark']), $this->rows('targets', 'Destinos adicionais', [$this->field('target_type', 'Tipo', 'select', ['options' => ['product' => 'Produto', 'product_variant' => 'Variante', 'collection' => 'Coleção', 'brand' => 'Marca', 'promotion' => 'Promoção', 'custom' => 'Link'], 'default' => 'custom']), $this->field('target_id', 'Identificador', 'number', ['min' => 1, 'max' => 2147483647, 'nullable' => true]), $this->field('target_value', 'Link', 'url')])]),
            ],
            'products_grid' => [$this->group('config', 'Grelha', [$title, $this->field('grid_type', 'Origem dos produtos', 'select', ['options' => ['trending' => 'Produtos recentes', 'manual' => 'Seleção manual', 'category' => 'Categoria'], 'default' => 'trending']), $this->field('category_id', 'Categoria', 'reference', ['source' => 'categories']), $this->field('items_limit', 'Limite de produtos', 'number', ['min' => 1, 'max' => 24, 'default' => 8]), $this->field('cols_desktop', 'Colunas no computador', 'number', ['min' => 1, 'max' => 6, 'default' => 4]), $this->field('cols_tablet', 'Colunas no tablet', 'number', ['min' => 1, 'max' => 4, 'default' => 3]), $this->field('cols_mobile', 'Colunas no telemóvel', 'number', ['min' => 1, 'max' => 2, 'default' => 2])]), $this->rows('items', 'Produtos da seleção manual', $product)],
            'home_deals_day' => [$this->group('config', 'Carrossel', array_merge([$title], $carousel)), $this->rows('items', 'Produtos', $product)],
            'top_category_filter' => [$this->group('config', 'Apresentação', array_merge([$title], $carousel)), $this->rows('tabs', 'Separadores', [$this->field('label', 'Nome do separador'), $this->rows('items', 'Produtos do separador', $product)])],
            'service_promotion' => [$this->group('config', 'Apresentação', [$this->field('layout', 'Disposição', 'select', ['options' => ['horizontal' => 'Horizontal', 'vertical' => 'Vertical'], 'default' => 'horizontal']), $this->field('items_limit', 'Limite de serviços', 'number', ['min' => 1, 'max' => 12, 'default' => 3])]), $this->rows('items', 'Serviços', [$this->field('icon', 'Ícone', 'select', ['options' => ['icon_shipping' => 'Envios', 'icon_headphone' => 'Apoio ao cliente', 'icon_shield' => 'Segurança'], 'default' => 'icon_shipping']), $title, $this->field('subtitle', 'Descrição')])],
            'blog_grid' => [$this->group('config', 'Apresentação', array_merge([$this->field('section_title', 'Título da secção')], $carousel)), $this->rows('items', 'Artigos', [$this->field('post_id', 'Artigo do blog', 'reference', ['source' => 'posts']), $title, $this->field('excerpt', 'Resumo', 'text'), $this->field('image', 'Imagem', 'image'), $this->field('author', 'Autor'), $this->field('published_at', 'Data de publicação', 'date'), $this->field('link', 'Destino', 'url')])],
            'faq' => [$this->field('faq_id', 'FAQ existente (opcional)', 'reference', ['source' => 'faqs']), $this->group('config', 'Apresentação', [$title]), $this->rows('items', 'Perguntas deste bloco', [$this->field('question', 'Pergunta'), $this->field('answer', 'Resposta', 'text')])],
            'footer' => [$this->group('config', 'Rodapé', array_merge(
                array_map(fn ($name) => $this->field('show_' . $name[0], $name[1], 'boolean'), [['info', 'Mostrar contactos'], ['social', 'Mostrar redes sociais'], ['newsletter', 'Mostrar newsletter'], ['opening_times', 'Mostrar horários']]),
                [$this->field('store_name', 'Nome da loja'), $this->field('contact_email', 'Email', 'email'), $this->field('contact_phone', 'Telefone'), $this->field('address_street', 'Morada'), $this->field('address_postcode', 'Código postal'), $this->field('address_city', 'Localidade'), $this->field('address_country', 'País')],
                array_map(fn ($name) => $this->field($name . '_url', ucfirst($name), 'url'), ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube']),
                array_map(fn ($day) => $this->field('opening_' . $day[0], $day[1]), [['monday', 'Segunda-feira'], ['tuesday', 'Terça-feira'], ['wednesday', 'Quarta-feira'], ['thursday', 'Quinta-feira'], ['friday', 'Sexta-feira'], ['saturday', 'Sábado'], ['sunday', 'Domingo']])
            )), $this->rows('categories', 'Colunas de links', [$title, $this->rows('items', 'Links', [$this->field('label', 'Texto'), $this->field('url', 'Destino', 'url')])])],
            default => throw new \InvalidArgumentException('Tipo de bloco não suportado.'),
        };
    }

    public function normalize(string $type, array $input, array $existing = []): array
    {
        return $this->validate($this->schema($type), $input, $existing);
    }

    private function validate(array $fields, array $input, array $existing): array
    {
        foreach ($fields as $field) {
            $key = $field['key']; $type = $field['type'];
            $value = $input[$key] ?? $field['default'] ?? null;
            if ($type === 'group') {
                if ($value !== null && ! is_array($value)) { throw new \InvalidArgumentException('Verifique ' . $field['label']); }
                $existing[$key] = $this->validate($field['fields'], $value ?? [], is_array($existing[$key] ?? null) ? $existing[$key] : []);
            } elseif ($type === 'list') {
                if (! is_array($value) || ! array_is_list($value) || count($value) > 40) { throw new \InvalidArgumentException('Verifique a lista ' . $field['label'] . ' (máximo 40).'); }
                $existing[$key] = [];
                foreach ($value as $index => $item) {
                    if (! is_array($item)) { throw new \InvalidArgumentException('Elemento inválido em ' . $field['label']); }
                    $clean = $this->validate($field['fields'], $item, []);
                    $clean += ['id' => $index + 1, 'position' => $index + 1, 'sort_order' => $index + 1, 'is_active' => 1];
                    $existing[$key][] = $clean;
                }
            } elseif ($type === 'boolean') {
                $existing[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($type === 'reference' || $type === 'number') {
                if (($value === '' || $value === null) && ($type === 'reference' || ($field['nullable'] ?? false))) { $existing[$key] = null; continue; }
                $number = filter_var($value, FILTER_VALIDATE_INT);
                if ($number === false || $number < ($field['min'] ?? 1) || $number > ($field['max'] ?? PHP_INT_MAX)) { throw new \InvalidArgumentException('Valor inválido em ' . $field['label']); }
                if ($type === 'reference' && ! $this->referenceExists($field['source'], $number)) { throw new \InvalidArgumentException('Seleção inexistente em ' . $field['label']); }
                $existing[$key] = $number;
            } else {
                $value ??= '';
                if (! is_string($value) || mb_strlen($value) > ($type === 'text' ? 5000 : 2048)) { throw new \InvalidArgumentException('Texto inválido em ' . $field['label']); }
                $value = trim($value);
                if ($type === 'select' && ! array_key_exists($value, $field['options'])) { throw new \InvalidArgumentException('Opção inválida em ' . $field['label']); }
                if ($type === 'url' && $value !== '' && ! preg_match('~^(?:/(?!/)|https?://[^\s]+$|\#[a-z0-9_-]+$)~i', $value)) { throw new \InvalidArgumentException('Destino inválido em ' . $field['label']); }
                if ($type === 'image' && $value !== '' && (str_contains($value, '..') || str_contains($value, ':') || str_starts_with($value, '/') || str_contains($value, '\\') || ! in_array(strtolower(pathinfo($value, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'], true))) { throw new \InvalidArgumentException('Imagem inválida.'); }
                if ($type === 'email' && $value !== '' && ! filter_var($value, FILTER_VALIDATE_EMAIL)) { throw new \InvalidArgumentException('Email inválido.'); }
                if ($type === 'date' && $value !== '' && ! preg_match('/^\d{4}-\d{2}-\d{2}(?:[ T]\d{2}:\d{2}(?::\d{2})?)?$/', $value)) { throw new \InvalidArgumentException('Data inválida.'); }
                $existing[$key] = $value;
            }
        }
        return $existing;
    }

    public function options(): array
    {
        $result = [];
        foreach (['products' => ['products', 'name'], 'variants' => ['products_variants', 'sku'], 'categories' => ['categories', 'name'], 'posts' => ['website_blog_posts', 'title'], 'faqs' => ['website_faqs', 'title']] as $key => [$table, $label]) {
            $result[$key] = db_connect()->table($table)->select('id, ' . $label . ' AS label')->orderBy('id')->get()->getResultArray();
        }
        return $result;
    }

    private function referenceExists(string $source, int $id): bool
    {
        $tables = ['products' => 'products', 'variants' => 'products_variants', 'categories' => 'categories', 'posts' => 'website_blog_posts', 'faqs' => 'website_faqs'];
        return db_connect()->table($tables[$source])->where('id', $id)->countAllResults() > 0;
    }
    private function field(string $key, string $label, string $type = 'string', array $extra = []): array { return $extra + compact('key', 'label', 'type'); }
    private function group(string $key, string $label, array $fields): array { return compact('key', 'label', 'fields') + ['type' => 'group']; }
    private function rows(string $key, string $label, array $fields): array { return compact('key', 'label', 'fields') + ['type' => 'list', 'default' => []]; }
}
