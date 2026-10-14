# Temas

- `base`: cópia das páginas, layout, parciais e recursos do website existente. Os caminhos internos das views e dos recursos foram ajustados para esta pasta.
- `starter`: apenas estrutura equivalente, templates vazios e layout HTML mínimo, sem design nem imagens.

## Pastas

Templates e manifestos: `app/Views/themes/{tema}`.
CSS, JS, imagens e fontes: `public/themes/{tema}`.

Os dois temas têm os mesmos nomes de páginas, parciais e blocos. O ficheiro `theme.json` documenta a estrutura; ainda não existe um carregador que o interprete.

Esta preparação não ativa temas nem altera os controllers. A loja continua a usar as views originais. A seleção do tema e a ligação aos controllers serão uma etapa separada.

O Base conserva as dependências partilhadas do sistema (uploads e JavaScript do carrinho). Não inclui dados de demonstração nem copia uploads da loja.