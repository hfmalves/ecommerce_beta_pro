<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->group('auth', ['namespace' => 'App\Controllers\Admin\Auth'], function ($routes) {
        $routes->get('login', 'AuthController::login', ['filter' => 'adminNoAuth']);
        $routes->post('login', 'AuthController::attemptLogin', ['filter' => 'adminNoAuth']);
        $routes->post('verify2FA', 'Auth::verify2FA', ['filter' => 'adminNoAuth']);
        $routes->get('register', 'RegisterController::index', ['filter' => 'adminNoAuth']);
        $routes->post('register', 'RegisterController::attemptRegister', ['filter' => 'adminNoAuth']);
        $routes->get('recovery', 'ForgotPasswordController::index', ['filter' => 'adminNoAuth']);
        $routes->post('recovery', 'ForgotPasswordController::sendRecovery', ['filter' => 'adminNoAuth']);
        $routes->get('reset/(:segment)', 'ForgotPasswordController::reset/$1', ['filter' => 'adminNoAuth']);
        $routes->post('reset', 'ForgotPasswordController::attemptReset', ['filter' => 'adminNoAuth']);
        $routes->get('logout', 'Auth::logoutController', ['filter' => 'adminAuth']);
    });
    $routes->get('dashboard', 'DashboardController::index', ['filter' => 'adminAuth']);
    $routes->group('catalog', ['namespace' => 'App\Controllers\Admin\Catalog'], function ($routes) {
        $routes->group('products', function ($routes) {
            $routes->get('/', 'ProductsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'ProductsController::store');
            $routes->get('edit/(:num)', 'ProductsController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'ProductsController::update');
            $routes->post('disable', 'ProductsController::disable');
            $routes->post('enabled', 'ProductsController::enabled');
            $routes->post('upload-image', 'ProductImagesController::upload');
            $routes->delete('delete-image/(:num)', 'ProductImagesController::delete/$1');
            $routes->post('reorder-images', 'ProductImagesController::reorder');
            $routes->group('variants', function ($routes) {
                $routes->post('create', 'ProductVariantsController::create', ['filter' => 'adminNoAuth']);
                $routes->get('edit/(:num)', 'ProductVariantsController::edit/$1', ['filter' => 'adminNoAuth']);
                $routes->post('update', 'ProductVariantsController::update');
                $routes->delete('delete/(:num)', 'ProductVariantsController::delete/$1');
            });
            $routes->group('packs', function ($routes) {
                $routes->get('items/(:num)', 'ProductsPackItemController::getPackItems/$1', ['filter' => 'adminNoAuth']);
                $routes->post('items/save/(:num)', 'ProductsPackItemController::savePackItems/$1', ['filter' => 'adminNoAuth']);
                $routes->post('items/update-qty/(:num)', 'ProductsPackItemController::updateQty/$1', ['filter' => 'adminNoAuth']);
                $routes->delete('items/delete/(:num)', 'ProductsPackItemController::deleteItem/$1', ['filter' => 'adminNoAuth']);
            });
            $routes->group('virtuals', function ($routes) {
                $routes->get('(:num)', 'ProductsVirtualController::show/$1', ['filter' => 'adminNoAuth']);
                $routes->post('save/(:num)', 'ProductsVirtualController::save/$1', ['filter' => 'adminNoAuth']);
                $routes->delete('delete/(:num)', 'ProductsVirtualController::delete/$1', ['filter' => 'adminNoAuth']);
                $routes->post('upload/(:num)', 'ProductsVirtualController::upload/$1', ['filter' => 'adminNoAuth']);
            });
        });
        $routes->group('categories', function ($routes) {
            $routes->get('/', 'CategoriesController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'CategoriesController::store');
            $routes->get('edit/(:num)', 'CategoriesController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'CategoriesController::update');
            $routes->post('reorder', 'CategoriesController::reorder');
            $routes->post('disable', 'CategoriesController::disable');
            $routes->post('enabled', 'CategoriesController::enabled');
            $routes->post('upload-image', 'CategoriesController::uploadImage');

        });
        $routes->group('attributes', function ($routes) {
            $routes->get('/', 'AttributesController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'AttributesController::store');
            $routes->get('edit/(:num)', 'AttributesController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'AttributesController::update');
            $routes->group('value', function ($routes) {
                $routes->post('store', 'AttributesController::storeValue');
                $routes->post('update', 'AttributesController::updateValue');
                $routes->post('delete', 'AttributesController::deleteValue');
                $routes->post('update-order', 'AttributesController::updateValueOrder');
            });
        });
        $routes->group('suppliers', function ($routes) {
            $routes->get('/', 'SuppliersController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'SuppliersController::store');
            $routes->get('edit/(:num)', 'SuppliersController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'SuppliersController::update');
            $routes->post('delete', 'SuppliersController::delete');
        });
        $routes->group('brands', function ($routes) {
            $routes->get('/', 'BrandsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'BrandsController::store');
            $routes->get('edit/(:num)', 'BrandsController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'BrandsController::update');
            $routes->post('upload-logo', 'BrandsController::uploadLogo');
            $routes->post('delete-logo', 'BrandsController::deleteLogo');
        });
    });
    $routes->group('customers', ['namespace' => 'App\Controllers\Admin\Customers'], function ($routes) {
        $routes->get('/', 'CustumersController::index', ['filter' => 'adminNoAuth']);
        $routes->get('edit/(:num)', 'CustumersController::edit/$1', ['filter' => 'adminNoAuth']);
        $routes->post('store', 'CustumersController::store');
        $routes->post('update', 'CustumersController::update');
        $routes->post('deactivate', 'CustumersController::deactivate');
        $routes->post('delete', 'CustumersController::delete');
        $routes->group('groups', function ($routes) {
            $routes->get('/', 'CustumersGroupsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'CustumersGroupsController::store');
            $routes->get('edit/(:num)', 'CustumersGroupsController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'CustumersGroupsController::update');
            $routes->post('delete', 'CustumersGroupsController::delete');
            $routes->post('deactivate', 'CustumersGroupsController::deactivate');
        });
    });
    $routes->group('sales', ['namespace' => 'App\Controllers\Admin\Sales'], function ($routes) {
        $routes->group('orders', function ($routes) {
            $routes->get('/', 'OrdersController::index', ['filter' => 'adminNoAuth']);
            $routes->get('create', 'OrdersController::create', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'OrdersController::store', ['filter' => 'adminNoAuth']);
            $routes->get('edit/(:num)', 'OrdersController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('updateStatus', 'OrdersController::updateStatus', ['filter' => 'adminNoAuth']);
        });
        $routes->group('transactions', function ($routes) {
            $routes->get('/', 'PaymentsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'PaymentsController::store');
            $routes->get('edit/(:num)', 'PaymentsController::edit/$1', ['filter' => 'adminNoAuth']);
        });
        $routes->group('financial_documents', function ($routes) {
            $routes->get('/', 'FinancialDocumentsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'FinancialDocumentsController::store');
            $routes->get('edit/(:num)', 'FinancialDocumentsController::edit/$1', ['filter' => 'adminNoAuth']);
        });
        $routes->group('shipments', function ($routes) {
            $routes->get('/', 'OrdersShipmentsController::index', ['filter' => 'adminNoAuth']);
            $routes->get('edit/(:num)', 'OrdersShipmentsController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'OrdersShipmentsController::update');
        });
        $routes->group('returns', function ($routes) {
            $routes->get('/', 'OrdersReturnController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'OrdersReturnController::store');
            $routes->get('edit/(:num)', 'OrdersReturnController::edit/$1', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'OrdersReturnController::update');
            $routes->post('saveItems', 'OrdersReturnController::saveItems', ['filter' => 'adminNoAuth']);
            $routes->post('removeItems', 'OrdersReturnController::removeItems', ['filter' => 'adminNoAuth']);
        });
        $routes->group('cart', ['namespace' => 'App\Controllers\Admin\Sales'], function ($routes) {
            // Lista de carrinhos
            $routes->get('/', 'OrdersCartController::index', ['filter' => 'adminNoAuth']);
            $routes->get('edit/(:num)', 'OrdersCartController::edit/$1', ['filter' => 'adminNoAuth']);
        });
    });
    $routes->group('marketing', ['namespace' => 'App\Controllers\Admin\Marketing'], function ($routes) {
        $routes->group('coupons', ['filter' => 'adminNoAuth'], function ($routes) {
            $routes->get('/', 'CouponsController::index');
            $routes->get('edit/(:num)', 'CouponsController::edit/$1');
            $routes->post('update', 'CouponsController::update');
            $routes->post('addCategory', 'CouponsController::addCategory');
            $routes->post('updateCategoryInclude', 'CouponsController::updateCategoryInclude');
            $routes->post('deleteCategory', 'CouponsController::deleteCategory');
            $routes->post('addProduct', 'CouponsController::addProduct');
            $routes->post('updateProductInclude', 'CouponsController::updateProductInclude');
            $routes->post('deleteProduct', 'CouponsController::deleteProduct');
        });

        $routes->group('campaigns', function ($routes) {
            $routes->get('/', 'CampaignsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'CampaignsController::store');
            $routes->get('edit/(:num)', 'CampaignsController::edit/$1');
            $routes->post('update', 'CampaignsController::update');
        });
        $routes->group('cart-rules', ['namespace' => 'App\Controllers\Admin\Marketing'], function ($routes) {
            $routes->get('/', 'CartRulesController::index');
            $routes->post('store', 'CartRulesController::store');
            $routes->get('edit/(:num)', 'CartRulesController::edit/$1');
            $routes->post('update', 'CartRulesController::update');
            $routes->post('delete', 'CartRulesController::delete');
            $routes->post('deactivate', 'CartRulesController::deactivate');

            $routes->post('addCategory', 'CartRulesController::addCategory');
            $routes->post('updateCategoryInclude', 'CartRulesController::updateCategoryInclude');
            $routes->post('deleteCategory', 'CartRulesController::deleteCategory');
            $routes->post('addProduct', 'CartRulesController::addProduct');
            $routes->post('updateProductInclude', 'CartRulesController::updateProductInclude');
            $routes->post('deleteProduct', 'CartRulesController::deleteProduct');
            $routes->post('addGroup', 'CartRulesController::addGroup');
            $routes->post('updateGroupInclude', 'CartRulesController::updateGroupInclude');
            $routes->post('deleteGroup', 'CartRulesController::deleteGroup');
        });
    });
    $routes->group('website', ['namespace' => 'App\Controllers\Admin\Website'], function ($routes) {
        $routes->group('menu', ['filter' => 'adminNoAuth'], function ($routes) {
            $routes->get('/', 'MenuController::index');
            $routes->get('edit/(:num)', 'MenuController::edit/$1');
            $routes->post('store', 'MenuController::store');
            $routes->post('update', 'MenuController::update');
            $routes->post('delete/(:num)', 'MenuController::delete/$1');
            $routes->post('disable', 'MenuController::disable');
            $routes->post('enable', 'MenuController::enable');
            $routes->post('reorder', 'MenuController::reorder');
        });
        $routes->group('showcases', ['filter' => 'adminNoAuth'], function ($routes) {
            $routes->get('/', 'ShowcasesController::index');
            $routes->post('create', 'ShowcasesController::create');
            $routes->get('edit/(:num)', 'ShowcasesController::edit/$1');
            $routes->get('block-preview/(:num)/(:num)', 'ShowcasesController::blockPreview/$1/$2', ['filter' => 'adminAuth']);
            $routes->post('update', 'ShowcasesController::update');
            $routes->post('reorder-blocks', 'ShowcasesController::reorderBlocks', ['filter' => 'adminAuth']);
            $routes->post('delete-block', 'ShowcasesController::deleteBlock', ['filter' => 'adminAuth']);
            $routes->post('add-block', 'ShowcasesController::addBlock', ['filter' => 'adminAuth']);
            $routes->post('delete', 'ShowcasesController::delete');
            $routes->post('deactivate', 'ShowcasesController::deactivate');
        });
        $routes->group('blog', ['filter' => 'adminNoAuth'], function ($routes) {
            $routes->get('/', 'BlogController::index');
        });
        $routes->group('faq', ['filter' => 'adminNoAuth'], function ($routes) {
            $routes->get('/', 'FaqController::index');
        });
        $routes->group('pages', ['filter' => 'adminNoAuth'], function ($routes) {
            $routes->get('/', 'PagesController::index');
        });
    });
    $routes->group('reports', function ($routes) {
        $routes->group('sales', ['namespace' => 'App\Controllers\Admin\Reports\Sales'], function ($routes) {
            $routes->get('/', 'SalesController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('products', ['namespace' => 'App\Controllers\Admin\Reports\Products'], function ($routes) {
            $routes->get('/', 'ProductsController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('customers', ['namespace' => 'App\Controllers\Admin\Reports\Customers'], function ($routes) {
            $routes->get('/', 'CustomersController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('carts', ['namespace' => 'App\Controllers\Admin\Reports\Carts'], function ($routes) {
            $routes->get('/', 'CartsController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('marketing', ['namespace' => 'App\Controllers\Admin\Reports\Marketing'], function ($routes) {
            $routes->get('/', 'MarketingController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('finance', ['namespace' => 'App\Controllers\Admin\Reports\Finance'], function ($routes) {
            $routes->get('/', 'FinanceController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('shipping', ['namespace' => 'App\Controllers\Admin\Reports\Shipping'], function ($routes) {
            $routes->get('/', 'ShippingController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('payments', ['namespace' => 'App\Controllers\Admin\Reports\Payments'], function ($routes) {
            $routes->get('/', 'PaymentsController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('inventory', ['namespace' => 'App\Controllers\Admin\Reports\Inventory'], function ($routes) {
            $routes->get('/', 'InventoryController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('geography', ['namespace' => 'App\Controllers\Admin\Reports\Geography'], function ($routes) {
            $routes->get('/', 'GeographyController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('coupons', ['namespace' => 'App\Controllers\Admin\Reports\Coupons'], function ($routes) {
            $routes->get('/', 'CouponsController::index', ['filter' => 'adminNoAuth']);
        });
    });
    $routes->group('settings', function ($routes) {
        $routes->group('general', ['namespace' => 'App\Controllers\Admin\Configurations\General'], function ($routes) {
            $routes->get('/', 'GeneralController::index', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'GeneralController::update');
        });
        $routes->group('taxes', ['namespace' => 'App\Controllers\Admin\Configurations\Taxes'], function ($routes) {
            $routes->get('/', 'TaxesController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'TaxesController::store');
            $routes->post('update', 'TaxesController::update');
            $routes->post('delete', 'TaxesController::delete');
        });
        $routes->group('payments', ['namespace' => 'App\Controllers\Admin\Configurations\Payments'], function ($routes) {
            $routes->get('/', 'PaymentsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'PaymentsController::store');
            $routes->post('update', 'PaymentsController::update');
            $routes->post('delete', 'PaymentsController::delete');
        });
        $routes->group('shipping', ['namespace' => 'App\Controllers\Admin\Configurations\Shipping'], function ($routes) {
            $routes->get('/', 'ShippingController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'ShippingController::store');
            $routes->post('update', 'ShippingController::update');
            $routes->post('delete', 'ShippingController::delete');
        });
        $routes->group('currencies', ['namespace' => 'App\Controllers\Admin\Configurations\Currencies'], function ($routes) {
            $routes->get('/', 'CurrenciesController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'CurrenciesController::store');
            $routes->post('update', 'CurrenciesController::update');
            $routes->post('delete', 'CurrenciesController::delete');
        });
        $routes->group('users', ['namespace' => 'App\Controllers\Admin\Configurations\Users'], function ($routes) {
            $routes->get('/', 'UsersController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('integrations', ['namespace' => 'App\Controllers\Admin\Configurations\Integrations'], function ($routes) {
            $routes->get('/', 'IntegrationsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'IntegrationsController::store');
            $routes->post('update', 'IntegrationsController::update');
            $routes->post('delete', 'IntegrationsController::delete');
        });
        $routes->group('notifications', ['namespace' => 'App\Controllers\Admin\Configurations\Notifications'], function ($routes) {
            $routes->get('/', 'NotificationsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'NotificationsController::store');
            $routes->post('update', 'NotificationsController::update');
            $routes->post('delete', 'NotificationsController::delete');
        });
        $routes->group('catalog', ['namespace' => 'App\Controllers\Admin\Configurations\Catalog'], function ($routes) {
            $routes->get('/', 'CatalogController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'CatalogController::store');
            $routes->post('update', 'CatalogController::update');
            $routes->post('delete', 'CatalogController::delete');
        });
        $routes->group('customers', ['namespace' => 'App\Controllers\Admin\Configurations\Customers'], function ($routes) {
            $routes->get('/', 'CustomersController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('emails', ['namespace' => 'App\Controllers\Admin\Configurations\Emails'], function ($routes) {
            $routes->get('/', 'EmailsController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'EmailsController::store');
            $routes->post('update', 'EmailsController::update');
            $routes->post('delete', 'EmailsController::delete');
        });
        $routes->group('seo', ['namespace' => 'App\Controllers\Admin\Configurations\Seo'], function ($routes) {
            $routes->get('/', 'SeoController::index', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'SeoController::update', ['filter' => 'adminNoAuth']);
        });
        $routes->group('security', ['namespace' => 'App\Controllers\Admin\Configurations\Security'], function ($routes) {
            $routes->get('/', 'SecurityController::index', ['filter' => 'adminNoAuth']);
            $routes->post('update', 'SecurityController::update', ['filter' => 'adminNoAuth']);
        });

        $routes->group('languages', ['namespace' => 'App\Controllers\Admin\Configurations\Languages'], function ($routes) {
            // Página de listagem de idiomas
            $routes->get('/', 'LanguagesController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'LanguagesController::store');
            $routes->post('update', 'LanguagesController::update');
            $routes->post('delete', 'LanguagesController::delete');
        });
        $routes->group('cache', ['namespace' => 'App\Controllers\Admin\Configurations\Cache'], function ($routes) {
            $routes->get('/', 'CacheController::index', ['filter' => 'adminNoAuth']);
            $routes->post('store', 'CacheController::store');
            $routes->post('update', 'CacheController::update');
            $routes->post('delete', 'CacheController::delete');
        });
        $routes->group('system', ['namespace' => 'App\Controllers\Admin\Configurations\System'], function ($routes) {
            $routes->get('/', 'SystemController::index', ['filter' => 'adminNoAuth']);
        });
        $routes->group('legal', ['namespace' => 'App\Controllers\Admin\Configurations\Legal'], function ($routes) {
            $routes->get('/', 'LegalController::index', ['filter' => 'adminNoAuth']);
        });
    });
});

$routes->group('', ['namespace' => 'App\Controllers\Website'], function ($routes) {
    $routes->get('/', 'HomeController::index');
    $routes->get('product/(:any)', 'ProductController::index/$1');
    $routes->get('page/(:any)', 'PageController::index/$1');
    $routes->get('category/(:any)', 'CategoryController::index/$1');
    $routes->get('promo/(:any)', 'CategoryController::index/$1');
    $routes->group('newsletter', function ($routes) {
        $routes->post('subscribe', 'NewsletterController::subscribe');
        $routes->post('seen', 'NewsletterController::seen');
        $routes->get('status', 'NewsletterController::status');
    });
    $routes->group('cart', ['namespace' => 'App\Controllers\Website'], function ($routes) {
        $routes->get('/', 'CartController::cart');
        $routes->post('add', 'CartController::add');
        $routes->post('update', 'CartController::update');
        $routes->post('remove', 'CartController::remove');
        $routes->post('clear', 'CartController::clear');
        $routes->get('drawer', 'CartController::drawer');
        $routes->get('content', 'CartController::cartContent');
        $routes->post('coupon', 'CartController::coupon');
    });


    $routes->get('checkout', 'CartController::checkout');
    $routes->get('order_complete', 'CartController::complete');
    $routes->group('user', function ($routes) {
        $routes->group('auth', ['namespace' => 'App\Controllers\Website\User'], function ($routes) {
            $routes->get('login', 'AuthController::login');
            $routes->post('login', 'AuthController::makelogin');
            $routes->get('register', 'AuthController::register');
            $routes->post('register', 'AuthController::makeRegister');
            $routes->get('recovery', 'AuthController::recovery');
            $routes->post('recovery', 'AuthController::makeRecovery');
            $routes->get('logout', 'AuthController::logout');
        });
        $routes->group('account', [
            'namespace' => 'App\Controllers\Website\User',
            'filter'    => 'userAuth'
        ], function ($routes) {
            $routes->get('/', 'AccountController::dashboard');
            $routes->get('dashboard', 'AccountController::dashboard');
            $routes->get('edit', 'AccountController::edit');
            $routes->get('address', 'AddressController::index');
            $routes->post('address', 'AddressController::store');
            $routes->post('address/(:num)', 'AddressController::update/$1');
            $routes->get('address/delete/(:num)', 'AddressController::delete/$1');
            $routes->get('wishlist', 'WishlistController::index');
            $routes->get('wishlist/add/(:num)', 'WishlistController::add/$1');
            $routes->get('wishlist/remove/(:num)', 'WishlistController::remove/$1');
            $routes->group('orders', function ($routes) {
                $routes->get('/', 'OrdersController::index');
                $routes->get('(:num)', 'OrdersController::show/$1');
                $routes->post('cancel/(:num)', 'OrdersController::cancel/$1');
            });
            $routes->post('pay/(:num)', 'PaymentsController::pay/$1');
            $routes->post('refund/(:num)', 'PaymentsController::refund/$1');
        });
    });
});


