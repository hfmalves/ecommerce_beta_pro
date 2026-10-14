<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Loja Online</li>
                <li class="mm-active">
                    <a href="<?= base_url('admin/dashboard') ?>" class="waves-effect active">
                        <i class="bx bx-home-circle"></i>
                        <span>Painel</span>
                    </a>
                </li>
                <!-- Produtos -->
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-package"></i>
                        <span>Catálogo</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('admin/catalog/products') ?>">Produtos</a></li>
                        <li><a href="<?= base_url('admin/catalog/categories') ?>">Categorias</a></li>
                        <li><a href="<?= base_url('admin/catalog/attributes') ?>">Atributos</a></li>
                        <li><a href="<?= base_url('admin/catalog/suppliers') ?>">Fornecedores</a></li>
                        <li><a href="<?= base_url('admin/catalog/brands') ?>">Marcas</a></li>
                    </ul>
                </li>
                <!-- Clientes -->
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user"></i>
                        <span>Clientes</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('admin/customers') ?>">Lista de Clientes</a></li>
                        <li><a href="<?= base_url('admin/customers/groups') ?>">Grupos de Clientes</a></li>
                    </ul>
                </li>
                <!-- Encomendas -->
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cart"></i>
                        <span>Vendas</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('admin/sales/orders') ?>">Encomendas</a></li>
                        <li><a href="<?= base_url('admin/sales/shipments') ?>">Envios</a></li>
                        <li><a href="<?= base_url('admin/sales/transactions') ?>">Transações</a></li>
                        <!--<li><a href="<?= base_url('admin/sales/financial_documents') ?>">Documentos Fiscais</a></li> -->
                        <li><a href="<?= base_url('admin/sales/returns') ?>">Devoluções</a></li>
                        <li><a href="<?= base_url('admin/sales/cart') ?>">Carrinhos</a></li>
                    </ul>
                </li>
                <!-- Campanhas / Marketing -->
                <li class="menu-title">Campanhas</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-bullseye"></i>
                        <span>Marketing</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('admin/marketing/coupons') ?>">Cupões de Desconto</a></li>
                        <li><a href="<?= base_url('admin/marketing/campaigns') ?>">Promoções</a></li>
                        <!--<li><a href="<?= base_url('admin/marketing/catalog-rules') ?>">Regras de Preço do Catálogo</a></li>-->
                        <li><a href="<?= base_url('admin/marketing/cart-rules') ?>">Regras de Carrinho</a></li>
                    </ul>
                </li>
                <!-- Website -->
                <li class="menu-title">Loja Online</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx bx-store"></i>
                        <span>Loja</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('admin/website/showcases') ?>">Digital showcases</a></li>
                        <li><a href="<?= base_url('admin/website/menu') ?>">Menu</a></li>
                        <li><a href="<?= base_url('admin/website/blog') ?>">Blog & Artigos</a></li>
                        <li><a href="<?= base_url('admin/website/faq') ?>">Faq's</a></li>
                        <li><a href="<?= base_url('admin/website/pages') ?>">Páginas</a></li>
                        <li><a href="<?= base_url('admin/website/promo') ?>">Promos</a></li>
                    </ul>
                </li>
                <!-- Relatórios -->
                <li class="menu-title">Relatórios</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-bar-chart-alt-2"></i>
                        <span>Relatórios</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('admin/reports/sales') ?>">Vendas</a></li>
                        <li><a href="<?= base_url('admin/reports/products') ?>">Produtos</a></li>
                        <li><a href="<?= base_url('admin/reports/customers') ?>">Clientes</a></li>
                        <li><a href="<?= base_url('admin/reports/carts') ?>">Carrinhos</a></li>
                        <li><a href="<?= base_url('admin/reports/marketing') ?>">Marketing</a></li>
                        <li><a href="<?= base_url('admin/reports/finance') ?>">Financeiro</a></li>
                        <li><a href="<?= base_url('admin/reports/shipping') ?>">Envios</a></li>
                        <li><a href="<?= base_url('admin/reports/payments') ?>">Pagamentos</a></li>
                        <li><a href="<?= base_url('admin/reports/inventory') ?>">Estoque</a></li>
                        <li><a href="<?= base_url('admin/reports/geography') ?>">Geografia</a></li>
                        <li><a href="<?= base_url('admin/reports/coupons') ?>">Cupons & Promoções</a></li>
                    </ul>
                </li>

                <!-- Configurações -->
                <li class="menu-title">Configurações</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Configurações</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('admin/settings/general') ?>">Geral</a></li>
                        <li><a href="<?= base_url('admin/settings/taxes') ?>">Impostos</a></li>
                        <li><a href="<?= base_url('admin/settings/payments') ?>">Pagamentos</a></li>
                        <li><a href="<?= base_url('admin/settings/shipping') ?>">Envios</a></li>
                        <li><a href="<?= base_url('admin/settings/currencies') ?>">Moedas</a></li>
                        <li><a href="<?= base_url('admin/settings/users') ?>">Utilizadores & Permissões</a></li>
                        <li><a href="<?= base_url('admin/settings/integrations') ?>">Integrações</a></li>
                        <li><a href="<?= base_url('admin/settings/notifications') ?>">Notificações</a></li>
                        <!-- Extras para ficar completo -->
                        <li><a href="<?= base_url('admin/settings/catalog') ?>">Catálogo</a></li>
                        <li><a href="<?= base_url('admin/settings/customers') ?>">Clientes</a></li>
                        <li><a href="<?= base_url('admin/settings/emails') ?>">Emails Transacionais</a></li>
                        <li><a href="<?= base_url('admin/settings/seo') ?>">SEO & URLs</a></li>
                        <li><a href="<?= base_url('admin/settings/security') ?>">Segurança</a></li>
                        <li><a href="<?= base_url('admin/settings/languages') ?>">Idiomas & Localização</a></li>
                        <li><a href="<?= base_url('admin/settings/cache') ?>">Performance & Cache</a></li>
                        <li><a href="<?= base_url('admin/settings/system') ?>">Logs Técnicos</a></li>
                        <li><a href="<?= base_url('admin/settings/legal') ?>">Informação Legal</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar End -->
    </div>
</div>
