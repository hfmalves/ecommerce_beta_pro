# Transição das tabelas de apresentação

## Estado

Migrações nativas: `2026-10-14-220000_CreateWebsiteThemeStructure` e `2026-10-14-220100_ArchiveWebsitePresentationTables`.

Mantidas: `website_homes`, `website_home_blocks`, menus, páginas, blog, FAQs, subscritores e todas as tabelas de negócio.

Criadas:
- `website_themes`: registo dos temas `base` e `starter`.
- `website_theme_settings`: seleção e configuração JSON por loja e tema; uma configuração por par loja/tema.

Acrescentados a `website_home_blocks`: `settings` (JSON) e `settings_version` (versão do formato).
O JSON guarda `config`, `items` e, conforme o bloco, `slides`, `tabs`, `categories` ou uma referência `faq_id`.

29 tabelas de apresentação foram renomeadas para `legacy_website_*`, sem apagar linhas.
Os nomes antigos continuam como views simples, com SQL SECURITY INVOKER, para manter os leitores e editores existentes operacionais. A lista exata encontra-se em `ArchiveWebsitePresentationTables::TABLES`.

## Limite desta fase

Os controllers e os formulários antigos continuam a utilizar as views e os dados arquivados. O JSON é uma cópia inicial, não está sincronizado automaticamente com futuras alterações desses editores. A ativação do carregador dos temas e a migração do editor para este formato ainda têm de ser implementadas. Não remover as views nem as tabelas legacy antes dessa mudança.
Os módulos antigos sem correspondência numa vitrine foram preservados no arquivo, não convertidos em blocos ativos automaticamente.
A newsletter visual por home também está preservada no arquivo; as subscrições continuam na tabela original.

## Comandos

- `php spark website:backup`: cópia SQL local das tabelas do website e histórico de migrações, em `writable/backups` (ignorado pelo Git).
- `php spark website:schema-status`: verificação apenas de leitura de arquivos, views e JSON.
- `php spark website:test-transition`: clona a base numa base temporária, testa up/down/up e os seeders do website, e remove apenas essa cópia. Requer permissão de criação de bases. Não executar durante alterações concorrentes de dados.
- `php spark migrate`: aplica as migrações nativas.

O seeder global continua DESTRUTIVO: limpa dados e recria exemplos. Agora ignora views na limpeza e os seeders antigos resolvem os nomes físicos através de `website_presentation_table`. `WebsiteThemeSeeder` volta a registar os temas e copia os blocos para JSON no fim.

## Reversão

`down()` da migração de arquivo remove as views e restaura os nomes físicos originais, mantendo os dados. `down()` da migração de estrutura remove as duas tabelas novas e as colunas JSON: fazer backup antes, pois elimina personalizações novas.
Não editar migrações antigas para acompanhar os nomes legacy; a transição é feita pelas duas novas migrações.