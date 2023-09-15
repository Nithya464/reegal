<?php

namespace App\Http\Middleware;

use App\Utils\ModuleUtil;
use Closure;
use Menu;

class AdminSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        Menu::create('admin-sidebar-menu', function ($menu) {
            $enabled_modules = ! empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

            $common_settings = ! empty(session('business.common_settings')) ? session('business.common_settings') : [];
            $pos_settings = ! empty(session('business.pos_settings')) ? json_decode(session('business.pos_settings'), true) : [];

            $is_admin = auth()->user()->hasRole('Admin#'.session('business.id')) ? true : false;
            //Home
            $menu->url(action([\App\Http\Controllers\HomeController::class, 'index']), __('home.home'), ['icon' => 'fa fas fa-tachometer-alt', 'active' => request()->segment(1) == 'home'])->order(5);

            //User management dropdown
            if (auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view')) {
                $menu->dropdown(
                    __('user.user_management'),
                    function ($sub) {
                        if (auth()->user()->can('user.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ManageUserController::class, 'index']),
                                __('user.users'),
                                ['icon' => 'fa fas fa-user', 'active' => request()->segment(1) == 'users']
                            );
                        }
                        if (auth()->user()->can('roles.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\RoleController::class, 'index']),
                                __('user.roles'),
                                ['icon' => 'fa fas fa-briefcase', 'active' => request()->segment(1) == 'roles']
                            );
                        }
                        // if (auth()->user()->can('user.create')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\SalesCommissionAgentController::class, 'index']),
                        //         __('lang_v1.sales_commission_agents'),
                        //         ['icon' => 'fa fas fa-handshake', 'active' => request()->segment(1) == 'sales-commission-agents']
                        //     );
                        // }
                    },
                    ['icon' => 'fa fas fa-users']
                )->order(10);
            }

            //Contacts dropdown
            if (auth()->user()->can('supplier.view') ||  auth()->user()->can('supplier.view_own')) {
                $menu->dropdown(
                    __('contact.contacts'),
                    function ($sub) {
                        if (auth()->user()->can('supplier.view') || auth()->user()->can('supplier.view_own')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'supplier']),
                                __('report.supplier'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->input('type') == 'supplier']
                            );
                        }
                        
                        if (auth()->user()->can('customer_groups.view') || auth()->user()->can('customer_groups.view_own')) {
                            $sub->url(
                                action([\App\Http\Controllers\CustomerGroupController::class, 'index']),
                                __('lang_v1.customer_groups'),
                                ['icon' => 'fa fas fa-users', 'active' => request()->segment(1) == 'customer-group']
                            );
                        }
                       
                        if (auth()->user()->can('supplier.create') || auth()->user()->can('customer.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'getImportContacts']),
                                __('lang_v1.import_contacts'),
                                ['icon' => 'fa fas fa-download', 'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'import']
                            );
                        }

                        if (! empty(env('GOOGLE_MAP_API_KEY'))) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'contactMap']),
                                __('lang_v1.map'),
                                ['icon' => 'fa fas fa-map-marker-alt', 'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'map']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-address-book', 'id' => 'tour_step4']
                )->order(15);
            }


            if (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('stock.create'))) {
                $menu->dropdown(
                    "Inventory",
                    function ($sub) {
                        if (auth()->user()->can('stock.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\ProductStockController::class, 'create']),
                                "Update Stock",
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'stock' && request()->segment(2) == 'create']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-truck']
                )->order(16);
            }

            //Products dropdown
            if (auth()->user()->can('product.view') || auth()->user()->can('product.create') ||
                auth()->user()->can('brand.view') || auth()->user()->can('unit.view') ||
                auth()->user()->can('category.view') || auth()->user()->can('brand.create') ||
                auth()->user()->can('unit.create') || auth()->user()->can('category.create')) {
                $menu->dropdown(
                    __('sale.products'),
                    function ($sub) {
                        if (auth()->user()->can('brand.view') || auth()->user()->can('brand.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\BrandController::class, 'index']),
                                __('brand.brands'),
                                ['icon' => 'fa fas fa-gem', 'active' => request()->segment(1) == 'brands']
                            );
                        }
                        if (auth()->user()->can('category.view') || auth()->user()->can('category.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\TaxonomyController::class, 'index']).'?type=product',
                                __('category.categories'),
                                ['icon' => 'fa fas fa-tags', 'active' => request()->segment(1) == 'taxonomies' && request()->get('type') == 'product']
                            );
                        }
                        if (auth()->user()->can('product.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ProductController::class, 'index']),
                                __('lang_v1.list_products'),
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'products' && request()->segment(2) == '']
                            );
                        }
                        if (auth()->user()->can('product.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\ProductController::class, 'create']),
                                __('product.add_product'),
                                ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'products' && request()->segment(2) == 'create']
                            );
                        }
                        // if (auth()->user()->can('product.view')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\LabelsController::class, 'show']),
                        //         __('barcode.print_labels'),
                        //         ['icon' => 'fa fas fa-barcode', 'active' => request()->segment(1) == 'labels' && request()->segment(2) == 'show']
                        //     );
                        // }
                        // if (auth()->user()->can('product.create')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\VariationTemplateController::class, 'index']),
                        //         __('product.variations'),
                        //         ['icon' => 'fa fas fa-circle', 'active' => request()->segment(1) == 'variation-templates']
                        //     );
                        //     $sub->url(
                        //         action([\App\Http\Controllers\ImportProductsController::class, 'index']),
                        //         __('product.import_products'),
                        //         ['icon' => 'fa fas fa-download', 'active' => request()->segment(1) == 'import-products']
                        //     );
                        // }
                        // if (auth()->user()->can('product.opening_stock')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\ImportOpeningStockController::class, 'index']),
                        //         __('lang_v1.import_opening_stock'),
                        //         ['icon' => 'fa fas fa-download', 'active' => request()->segment(1) == 'import-opening-stock']
                        //     );
                        // }
                        // if (auth()->user()->can('product.create')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\SellingPriceGroupController::class, 'index']),
                        //         __('lang_v1.selling_price_group'),
                        //         ['icon' => 'fa fas fa-circle', 'active' => request()->segment(1) == 'selling-price-group']
                        //     );
                        // }
                        if (auth()->user()->can('unit.view') || auth()->user()->can('unit.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\UnitController::class, 'index']),
                                __('unit.units'),
                                ['icon' => 'fa fas fa-balance-scale', 'active' => request()->segment(1) == 'units']
                            );
                        }
                        // $sub->url(
                        //     action([\App\Http\Controllers\WarrantyController::class, 'index']),
                        //     __('lang_v1.warranties'),
                        //     ['icon' => 'fa fas fa-shield-alt', 'active' => request()->segment(1) == 'warranties']
                        // );
                    },
                    ['icon' => 'fa fas fa-cubes', 'id' => 'tour_step5']
                )->order(20);
            }
            
            if (in_array('purchases', $enabled_modules) && (auth()->user()->can('purchase_order.view') || auth()->user()->can('purchase_order.create') || auth()->user()->can('purchase_order.update') || auth()->user()->can('receive_stock.create') || auth()->user()->can('receive_stock.update')) || auth()->user()->can('purchase_order_IR.view_all') || auth()->user()->can('purchase_order_IR.create') || auth()->user()->can('purchase_order_IM.draft_view_all')) {
                $menu->dropdown(
                    "Purchase Order",
                        function ($sub) {
                            if(auth()->user()->can('purchase_order.view_all') || auth()->user()->can('purchase_order.create') || auth()->user()->can('purchase_order.view')){
                                $sub->dropdown('By Internal', function ($sub) {
                                    if (auth()->user()->can('purchase_order.create')) {
                                        $sub->url(
                                            action([\App\Http\Controllers\PurchaseOrderController::class, 'create']),
                                            "Ⅰ-Generate PO",
                                            ['icon' => 'fa fa-shopping-cart', 'active' => request()->segment(1) == 'purchase-order' && request()->segment(2) == 'create']
                                        );
                                    }
                                    if (auth()->user()->can('purchase_order.view_all')) {
                                        $sub->url(
                                            action([\App\Http\Controllers\PurchaseOrderController::class, 'index']),
                                            "Ⅰ-PO List",
                                            ['icon' => 'fa fas fa-list', 'active' => (request()->segment(1) == 'purchase-order' && request()->segment(2) == null)]
                                        );
                                    }
                                });
                            }
                            if(auth()->user()->can('receive_stock.view_all') || auth()->user()->can('purchase_order_IR.view_all') || auth()->user()->can('purchase_order_IR.create') || auth()->user()->can('purchase_order_IM.draft_view_all')){
                                $sub->dropdown('By Vendor', function ($sub) {
                                    if (auth()->user()->can('purchase_order_IM.draft_view_all')) {
                                        $sub->url(
                                            action([\App\Http\Controllers\PurchaseOrderByVendorImController::class, 'index']),
                                            "Draft PO List (IM)",
                                            ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'purchase-order-vendor-im' && request()->segment(2) == null]
                                        );
                                    }
                                    if (auth()->user()->can('receive_stock.view_all')) {
                                        $sub->url(
                                            action([\App\Http\Controllers\ReceiveStockController::class, 'index']),
                                            "PO List (IM)",
                                            ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'receive-stock']
                                        );
                                    }

                                    if (auth()->user()->can('purchase_order_IR.create')) {
                                        $sub->url(
                                            action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'create']),
                                            "Generate PO (IR)",
                                            ['icon' => 'fa fa-shopping-cart', 'active' => request()->segment(1) == 'purchase-order-vendor' && request()->segment(2) == 'create']
                                        );
                                    }
                                    if (auth()->user()->can('purchase_order_IR.view_all')) {
                                        $sub->url(
                                            // action([\App\Http\Controllers\StockAdjustmentController::class, 'index']),
                                            action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'index']),
                                            "PO List (IR)",
                                            ['icon' => 'fa fas fa-list', 'active' => (request()->segment(1) == 'purchase-order-vendor' && request()->segment(2) == null)]
                                        );
                                    }
                                });
                            }
                        },
                    ['icon' => 'fa fa-shopping-cart']
                )->order(21);
            }

            if (auth()->user()->can('order.view')) {
                $menu->dropdown(
                    __('Orders'),
                    function ($sub) {
                        if (auth()->user()->can('car.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\OrderController::class, 'index']),
                                __('Order List'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'customer_orders']
                            );
                        }
                    },
                    ['icon' => 'fas fa-money-check', 'id' => 'tour_step4']

                    
                )->order(21);
            }

            //Sell dropdown

            if ($is_admin || auth()->user()->hasAnyPermission(['direct_sell_pos.view', 'direct_sell_pos.create','direct_sell_pos.update', 'direct_sell_pos.delete'])) {
                $menu->dropdown(
                    __('Orders'),
                    function ($sub) use ($enabled_modules, $is_admin, $pos_settings) {
                        // if (! empty($pos_settings['enable_sales_order']) && ($is_admin || auth()->user()->hasAnyPermission(['so.view_own', 'so.view_all', 'so.create']))) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\SalesOrderController::class, 'index']),
                        //         __('lang_v1.sales_order'),
                        //         ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'sales-order']
                        //     );
                        // }

                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell_pos.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\PosSellController::class, 'create']),
                                __('New Order'),
                                ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'pos-sells' && request()->segment(2) == 'create' && empty(request()->get('status'))]
                            );
                        }

                        if ($is_admin || auth()->user()->hasAnyPermission(['direct_sell_pos.view'])) {
                            $sub->url(
                                action([\App\Http\Controllers\PosSellController::class, 'index']),
                                __('Order List (POS)'),
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'pos-sells' && request()->segment(2) != 'create']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-arrow-circle-up', 'id' => 'tour_step7']
                )->order(30);
            }

            if ($is_admin || auth()->user()->hasAnyPermission(['direct_sell_sp.view', 'direct_sell_sp.create','direct_sell_sp.update', 'direct_sell_sp.delete'])) {
                $menu->dropdown(
                    __('Orders ( SP )'),
                    function ($sub) use ($enabled_modules, $is_admin, $pos_settings) {
             
                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell_sp.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\SpSellController::class, 'create']),
                                __('New Order ( SP )'),
                                ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'sp-sells' && request()->segment(2) == 'create' && empty(request()->get('status'))]
                            );
                        }

                        if ($is_admin || auth()->user()->hasAnyPermission(['direct_sell_sp.view'])) {
                            $sub->url(
                                action([\App\Http\Controllers\SpSellController::class, 'index']),
                                __('Order List ( SP )'),
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'sp-sells' && request()->segment(2) != 'create']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-arrow-circle-up', 'id' => 'tour_step7']
                )->order(30);
            }

            //Purchase dropdown

            // if (in_array('purchases', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('purchase.update'))) {
            //     $menu->dropdown(
            //         __('purchase.purchases'),
            //         function ($sub) use ($common_settings) {
            //             if (! empty($common_settings['enable_purchase_requisition']) && (auth()->user()->can('purchase_requisition.view_all') || auth()->user()->can('purchase_requisition.view_own'))) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\PurchaseRequisitionController::class, 'index']),
            //                     __('lang_v1.purchase_requisition'),
            //                     ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'purchase-requisition']
            //                 );
            //             }

            //             if (! empty($common_settings['enable_purchase_order']) && (auth()->user()->can('purchase_order.view_all') || auth()->user()->can('purchase_order.view_own'))) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\PurchaseOrderController::class, 'index']),
            //                     __('lang_v1.purchase_order'),
            //                     ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'purchase-order']
            //                 );
            //             }
            //             if (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\PurchaseController::class, 'index']),
            //                     __('purchase.list_purchase'),
            //                     ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == null]
            //                 );
            //             }
            //             if (auth()->user()->can('purchase.create')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\PurchaseController::class, 'create']),
            //                     __('purchase.add_purchase'),
            //                     ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == 'create']
            //                 );
            //             }
            //             if (auth()->user()->can('purchase.update')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\PurchaseReturnController::class, 'index']),
            //                     __('lang_v1.list_purchase_return'),
            //                     ['icon' => 'fa fas fa-undo', 'active' => request()->segment(1) == 'purchase-return']
            //                 );
            //             }
            //         },
            //         ['icon' => 'fa fas fa-arrow-circle-down', 'id' => 'tour_step6']
            //     )->order(25);
            // }

            //Sell dropdown

            // if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping', 'access_sell_return', 'direct_sell.view', 'direct_sell.update', 'access_own_sell_return'])) {
            //     $menu->dropdown(
            //         __('sale.sale'),
            //         function ($sub) use ($enabled_modules, $is_admin, $pos_settings) {
            //             if (! empty($pos_settings['enable_sales_order']) && ($is_admin || auth()->user()->hasAnyPermission(['so.view_own', 'so.view_all', 'so.create']))) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SalesOrderController::class, 'index']),
            //                     __('lang_v1.sales_order'),
            //                     ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'sales-order']
            //                 );
            //             }

            //             if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'direct_sell.view', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping'])) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellController::class, 'index']),
            //                     __('lang_v1.all_sales'),
            //                     ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == null]
            //                 );
            //             }
            //             if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellController::class, 'create']),
            //                     __('sale.add_sale'),
            //                     ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'create' && empty(request()->get('status'))]
            //                 );
            //             }
            //             if (auth()->user()->can('sell.create')) {
            //                 if (in_array('pos_sale', $enabled_modules)) {
            //                     if (auth()->user()->can('sell.view')) {
            //                         $sub->url(
            //                             action([\App\Http\Controllers\SellPosController::class, 'index']),
            //                             __('sale.list_pos'),
            //                             ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'pos' && request()->segment(2) == null]
            //                         );
            //                     }

            //                     $sub->url(
            //                         action([\App\Http\Controllers\SellPosController::class, 'create']),
            //                         __('sale.pos_sale'),
            //                         ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'pos' && request()->segment(2) == 'create']
            //                     );
            //                 }
            //             }

            //             if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellController::class, 'create'], ['status' => 'draft']),
            //                     __('lang_v1.add_draft'),
            //                     ['icon' => 'fa fas fa-plus-circle', 'active' => request()->get('status') == 'draft']
            //                 );
            //             }
            //             if (in_array('add_sale', $enabled_modules) && ($is_admin || auth()->user()->hasAnyPermission(['draft.view_all', 'draft.view_own']))) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellController::class, 'getDrafts']),
            //                     __('lang_v1.list_drafts'),
            //                     ['icon' => 'fa fas fa-pen-square', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'drafts']
            //                 );
            //             }
            //             if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellController::class, 'create'], ['status' => 'quotation']),
            //                     __('lang_v1.add_quotation'),
            //                     ['icon' => 'fa fas fa-plus-circle', 'active' => request()->get('status') == 'quotation']
            //                 );
            //             }
            //             if (in_array('add_sale', $enabled_modules) && ($is_admin || auth()->user()->hasAnyPermission(['quotation.view_all', 'quotation.view_own']))) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellController::class, 'getQuotations']),
            //                     __('lang_v1.list_quotations'),
            //                     ['icon' => 'fa fas fa-pen-square', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'quotations']
            //                 );
            //             }

            //             if (auth()->user()->can('access_sell_return') || auth()->user()->can('access_own_sell_return')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellReturnController::class, 'index']),
            //                     __('lang_v1.list_sell_return'),
            //                     ['icon' => 'fa fas fa-undo', 'active' => request()->segment(1) == 'sell-return' && request()->segment(2) == null]
            //                 );
            //             }

            //             if ($is_admin || auth()->user()->hasAnyPermission(['access_shipping', 'access_own_shipping', 'access_commission_agent_shipping'])) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellController::class, 'shipments']),
            //                     __('lang_v1.shipments'),
            //                     ['icon' => 'fa fas fa-truck', 'active' => request()->segment(1) == 'shipments']
            //                 );
            //             }

            //             if (auth()->user()->can('discount.access')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\DiscountController::class, 'index']),
            //                     __('lang_v1.discounts'),
            //                     ['icon' => 'fa fas fa-percent', 'active' => request()->segment(1) == 'discount']
            //                 );
            //             }
            //             if (in_array('subscription', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\SellPosController::class, 'listSubscriptions']),
            //                     __('lang_v1.subscriptions'),
            //                     ['icon' => 'fa fas fa-recycle', 'active' => request()->segment(1) == 'subscriptions']
            //                 );
            //             }

            //             if (auth()->user()->can('sell.create')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ImportSalesController::class, 'index']),
            //                     __('lang_v1.import_sales'),
            //                     ['icon' => 'fa fas fa-file-import', 'active' => request()->segment(1) == 'import-sales']
            //                 );
            //             }
            //         },
            //         ['icon' => 'fa fas fa-arrow-circle-up', 'id' => 'tour_step7']
            //     )->order(30);
            // }

            //Stock transfer dropdown


            if (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create'))) {
                $menu->dropdown(
                    "Stock",
                    function ($sub) {

                        if (auth()->user()->can('purchase.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\StockTransferController::class, 'index']),
                                "Internal Stock",
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == null]
                            );
                        }

                        // if (auth()->user()->can('purchase.create')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\StockTransferController::class, 'create']),
                        //         "Add Internal Stock",
                        //         ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == 'create']
                        //     );
                        // }
                        
                        if (auth()->user()->can('purchase.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\StockAdjustmentController::class, 'index']),
                                "External Stock",
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == null]
                            );
                        }
                        // if (auth()->user()->can('purchase.create')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\StockAdjustmentController::class, 'create']),
                        //         "External Stock Add",
                        //         ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == 'create']
                        //     );
                        // }
                        
                    },
                    ['icon' => 'fa fas fa-truck']
                )->order(35);
            }
            

            if (auth()->user()->can('route.view')  || auth()->user()->can('car.view')  || auth()->user()->can('car.create') || auth()->user()->can('car.update')) {
                $menu->dropdown(
                    __('Application'),
                    function ($sub) {
                        if (auth()->user()->can('car.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\CarController::class, 'index']),
                                __('Manage Car'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'cars']
                            );
                        }
                        if (auth()->user()->can('route.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\RouteController::class, 'index']),
                                __('Manage Route'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'routes']
                            );
                        }
                        if (auth()->user()->can('tax.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\TaxController::class, 'index']),
                                __('Manage Tax'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'taxes']
                            );
                        }

                        if (auth()->user()->can('driver.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\DriverController::class, 'index']),
                                __('Manage Driver'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'drivers']
                            );
                        }

                        if (auth()->user()->can('state.view')) {
                            $sub->dropdown('Parameter Setting', function ($sub) {
                                if (auth()->user()->can('state.view')) {
                                    $sub->url(action([\App\Http\Controllers\StateController::class, 'index']),
                                        "Manage States",
                                        ['icon' => 'fa fa-shopping-cart', 'active' => request()->segment(1) == 'states']
                                    );
                                }
                                if (auth()->user()->can('city.view')) {
                                    $sub->url(action([\App\Http\Controllers\CityController::class, 'index']),
                                        "Manage Cities",
                                        ['icon' => 'fa fa-shopping-cart', 'active' => request()->segment(1) == 'cities']
                                    );
                                }
                                if (auth()->user()->can('zipcode.view')) {
                                    $sub->url(action([\App\Http\Controllers\ZipcodeController::class, 'index']),
                                        "Manage Zipcodes",
                                        ['icon' => 'fa fa-shopping-cart', 'active' => request()->segment(1) == 'zipcodes']
                                    );
                                }
                                
                            });
                        }
                    },
                    ['icon' => 'fa fas fa-address-book', 'id' => 'tour_step4']

                    
                )->order(36);
            }

            if (auth()->user()->can('price_level.view')  || auth()->user()->can('price_level.create') || auth()->user()->can('price_level.update') || auth()->user()->can('customer.view') || auth()->user()->can('customer.view_own')) {
                $menu->dropdown(
                    __('Customers'),
                    function ($sub) {
                        if (auth()->user()->can('customer.view') || auth()->user()->can('customer.view_own')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'customer']),
                                __('report.customer_list'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->input('type') == 'customer']
                            );
                        }
                        if (auth()->user()->can('customer_location.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\CustomerLocationController::class, 'index']),
                                __('report.customer_location'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'customers_locations']
                            );
                        }
                        if (auth()->user()->can('draft_customer.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'draft_customer']),
                                __('report.draft_customer'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->input('type') == 'draft_customer']
                            );
                        }
                        if (auth()->user()->can('price_level.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\PriceLevelController::class, 'index']),
                                __('Manage Price Level'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'price_level']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-users', 'id' => 'tour_step4']

                    )->order(36);
            }

            if (auth()->user()->can('vendor.view') || auth()->user()->can('vendor.create')) {
                $menu->dropdown(
                    __('Vendor'),
                    function ($sub) {
                        if (auth()->user()->can('vendor.view') || auth()->user()->can('vendor.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\VendorController::class, 'index']),
                                __('Vendor List'),
                                ['icon' => 'fa fas fa-gem', 'active' => request()->segment(1) == 'vendors']
                            );
                        }
                    },
                    ['icon' => 'fa fa-clipboard', 'id' => 'tour_step5']
                )->order(37);
            }

            if (auth()->user()->can('write_cheque.view')|| auth()->user()->can('write_cheque.create')) {
                $menu->dropdown(
                    __('Write Cheque'),
                    function ($sub) {
                        if (auth()->user()->can('write_cheque.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\WriteChequeController::class, 'index']),
                                __('Write Cheque List'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'cheques']
                            );
                        }
                    },
                    ['icon' => 'fas fa-money-check-alt', 'id' => 'tour_step4']
                )->order(38);
            }
    
            if (auth()->user()->can('manage_expense.view')  || auth()->user()->can('manage_expense.create') || auth()->user()->can('manage_expense.update') || auth()->user()->can('expense_category.view')  || auth()->user()->can('expense_category.create') || auth()->user()->can('expense_category.update')) {
                $menu->dropdown(
                    __('expense.expenses'),
                    function ($sub) {
                        if (auth()->user()->can('expense_category.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ExpenseCategoryController::class, 'index']),
                                __('Expense Category'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'expense-categories']
                            );
                        }
                        if (auth()->user()->can('manage_expense.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ManageExpenseController::class, 'index']),
                                __('Expenses'),
                                ['icon' => 'fa fas fa-star', 'active' => request()->segment(1) == 'manage_expenses']
                            );
                        }
                    },
                    ['icon' => 'fa fa-print', 'id' => 'tour_step4']

                    
                )->order(37);
            }

            if (auth()->user()->can('purchase_return.view')  || auth()->user()->can('purchase_return.create') || auth()->user()->can('purchase_return.update') || auth()->user()->can('access_sell_return')) {
                $menu->dropdown(
                    __('Credit Memo'),
                    function ($sub) {
                        // if (auth()->user()->can('purchase_return.update')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\PurchaseReturnController::class, 'index']),
                        //         __('Credit Memo List (SM)'),
                        //         ['icon' => 'fa fas fa-undo', 'active' => request()->segment(1) == 'purchase-return']
                        //     );
                        // }
                        if (auth()->user()->can('access_sell_return')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellReturnController::class, 'index']),
                                __('Credit Memo List (POS)'),
                                ['icon' => 'fa fas fa-undo', 'active' => request()->segment(1) == 'sell-return']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-money-check-alt', 'id' => 'tour_step4']

                    
                )->order(37);
            }
            // Receive Payments
            if (auth()->user()->can('receive_payment.view') || auth()->user()->can('receive_payment.create')) {
                $menu->url(action([\App\Http\Controllers\ReceivePaymentController::class, 'index']), __('lang_v1.receive_payment'), ['icon' => 'fa fas fa-hdd', 'active' => request()->segment(1) == 'receive-payment'])->order(60);
            }

            // if (in_array('stock_adjustment', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create'))) {
            //     $menu->dropdown(
            //         "External Stock",
            //         function ($sub) {
            //             if (auth()->user()->can('purchase.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\StockAdjustmentController::class, 'index']),
            //                     "List External Stock",
            //                     ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == null]
            //                 );
            //             }
            //             if (auth()->user()->can('purchase.create')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\StockAdjustmentController::class, 'create']),
            //                     "External Stock Add",
            //                     ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == 'create']
            //                 );
            //             }
            //         },
            //         ['icon' => 'fa fas fa-database']
            //     )->order(40);
            // }

            //Expense dropdown


            // if (in_array('expenses', $enabled_modules) && (auth()->user()->can('all_expense.access') || auth()->user()->can('view_own_expense'))) {
            //     $menu->dropdown(
            //         __('expense.expenses'),
            //         function ($sub) {
            //             $sub->url(
            //                 action([\App\Http\Controllers\ExpenseController::class, 'index']),
            //                 __('lang_v1.list_expenses'),
            //                 ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == null]
            //             );

            //             if (auth()->user()->can('expense.add')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ExpenseController::class, 'create']),
            //                     __('expense.add_expense'),
            //                     ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == 'create']
            //                 );
            //             }

            //             if (auth()->user()->can('expense.add') || auth()->user()->can('expense.edit')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ExpenseCategoryController::class, 'index']),
            //                     __('expense.expense_categories'),
            //                     ['icon' => 'fa fas fa-circle', 'active' => request()->segment(1) == 'expense-categories']
            //                 );
            //             }
            //         },
            //         ['icon' => 'fa fas fa-minus-circle']
            //     )->order(45);
            // }


            //Accounts dropdown


            // if (auth()->user()->can('account.access') && in_array('account', $enabled_modules)) {
            //     $menu->dropdown(
            //         __('lang_v1.payment_accounts'),
            //         function ($sub) {
            //             $sub->url(
            //                 action([\App\Http\Controllers\AccountController::class, 'index']),
            //                 __('account.list_accounts'),
            //                 ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'account']
            //             );
            //             $sub->url(
            //                 action([\App\Http\Controllers\AccountReportsController::class, 'balanceSheet']),
            //                 __('account.balance_sheet'),
            //                 ['icon' => 'fa fas fa-book', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'balance-sheet']
            //             );
            //             $sub->url(
            //                 action([\App\Http\Controllers\AccountReportsController::class, 'trialBalance']),
            //                 __('account.trial_balance'),
            //                 ['icon' => 'fa fas fa-balance-scale', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'trial-balance']
            //             );
            //             $sub->url(
            //                 action([\App\Http\Controllers\AccountController::class, 'cashFlow']),
            //                 __('lang_v1.cash_flow'),
            //                 ['icon' => 'fa fas fa-exchange-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'cash-flow']
            //             );
            //             $sub->url(
            //                 action([\App\Http\Controllers\AccountReportsController::class, 'paymentAccountReport']),
            //                 __('account.payment_account_report'),
            //                 ['icon' => 'fa fas fa-file-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'payment-account-report']
            //             );
            //         },
            //         ['icon' => 'fa fas fa-money-check-alt']
            //     )->order(50);
            // }

            //Reports dropdown

            // if (auth()->user()->can('purchase_n_sell_report.view') || auth()->user()->can('contacts_report.view')
            //     || auth()->user()->can('stock_report.view') || auth()->user()->can('tax_report.view')
            //     || auth()->user()->can('trending_product_report.view') || auth()->user()->can('sales_representative.view') || auth()->user()->can('register_report.view')
            //     || auth()->user()->can('expense_report.view')) {
            //     $menu->dropdown(
            //         __('report.reports'),
            //         function ($sub) use ($enabled_modules, $is_admin) {
            //             if (auth()->user()->can('profit_loss_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getProfitLoss']),
            //                     __('report.profit_loss'),
            //                     ['icon' => 'fa fas fa-file-invoice-dollar', 'active' => request()->segment(2) == 'profit-loss']
            //                 );
            //             }
            //             if (config('constants.show_report_606') == true) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'purchaseReport']),
            //                     'Report 606 ('.__('lang_v1.purchase').')',
            //                     ['icon' => 'fa fas fa-arrow-circle-down', 'active' => request()->segment(2) == 'purchase-report']
            //                 );
            //             }
            //             if (config('constants.show_report_607') == true) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'saleReport']),
            //                     'Report 607 ('.__('business.sale').')',
            //                     ['icon' => 'fa fas fa-arrow-circle-up', 'active' => request()->segment(2) == 'sale-report']
            //                 );
            //             }
            //             if ((in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules)) && auth()->user()->can('purchase_n_sell_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getPurchaseSell']),
            //                     __('report.purchase_sell_report'),
            //                     ['icon' => 'fa fas fa-exchange-alt', 'active' => request()->segment(2) == 'purchase-sell']
            //                 );
            //             }

            //             if (auth()->user()->can('tax_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getTaxReport']),
            //                     __('report.tax_report'),
            //                     ['icon' => 'fa fas fa-percent', 'active' => request()->segment(2) == 'tax-report']
            //                 );
            //             }
            //             if (auth()->user()->can('contacts_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getCustomerSuppliers']),
            //                     __('report.contacts'),
            //                     ['icon' => 'fa fas fa-address-book', 'active' => request()->segment(2) == 'customer-supplier']
            //                 );
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getCustomerGroup']),
            //                     __('lang_v1.customer_groups_report'),
            //                     ['icon' => 'fa fas fa-users', 'active' => request()->segment(2) == 'customer-group']
            //                 );
            //             }
            //             if (auth()->user()->can('stock_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getStockReport']),
            //                     __('report.stock_report'),
            //                     ['icon' => 'fa fas fa-hourglass-half', 'active' => request()->segment(2) == 'stock-report']
            //                 );
            //                 if (session('business.enable_product_expiry') == 1) {
            //                     $sub->url(
            //                         action([\App\Http\Controllers\ReportController::class, 'getStockExpiryReport']),
            //                         __('report.stock_expiry_report'),
            //                         ['icon' => 'fa fas fa-calendar-times', 'active' => request()->segment(2) == 'stock-expiry']
            //                     );
            //                 }
            //                 if (session('business.enable_lot_number') == 1) {
            //                     $sub->url(
            //                         action([\App\Http\Controllers\ReportController::class, 'getLotReport']),
            //                         __('lang_v1.lot_report'),
            //                         ['icon' => 'fa fas fa-hourglass-half', 'active' => request()->segment(2) == 'lot-report']
            //                     );
            //                 }

            //                 if (in_array('stock_adjustment', $enabled_modules)) {
            //                     $sub->url(
            //                         action([\App\Http\Controllers\ReportController::class, 'getStockAdjustmentReport']),
            //                         __('report.stock_adjustment_report'),
            //                         ['icon' => 'fa fas fa-sliders-h', 'active' => request()->segment(2) == 'stock-adjustment-report']
            //                     );
            //                 }
            //             }

            //             if (auth()->user()->can('trending_product_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getTrendingProducts']),
            //                     __('report.trending_products'),
            //                     ['icon' => 'fa fas fa-chart-line', 'active' => request()->segment(2) == 'trending-products']
            //                 );
            //             }

            //             if (auth()->user()->can('purchase_n_sell_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'itemsReport']),
            //                     __('lang_v1.items_report'),
            //                     ['icon' => 'fa fas fa-tasks', 'active' => request()->segment(2) == 'items-report']
            //                 );

            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getproductPurchaseReport']),
            //                     __('lang_v1.product_purchase_report'),
            //                     ['icon' => 'fa fas fa-arrow-circle-down', 'active' => request()->segment(2) == 'product-purchase-report']
            //                 );

            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getproductSellReport']),
            //                     __('lang_v1.product_sell_report'),
            //                     ['icon' => 'fa fas fa-arrow-circle-up', 'active' => request()->segment(2) == 'product-sell-report']
            //                 );

            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'purchasePaymentReport']),
            //                     __('lang_v1.purchase_payment_report'),
            //                     ['icon' => 'fa fas fa-search-dollar', 'active' => request()->segment(2) == 'purchase-payment-report']
            //                 );

            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'sellPaymentReport']),
            //                     __('lang_v1.sell_payment_report'),
            //                     ['icon' => 'fa fas fa-search-dollar', 'active' => request()->segment(2) == 'sell-payment-report']
            //                 );
            //             }
            //             if (in_array('expenses', $enabled_modules) && auth()->user()->can('expense_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getExpenseReport']),
            //                     __('report.expense_report'),
            //                     ['icon' => 'fa fas fa-search-minus', 'active' => request()->segment(2) == 'expense-report']
            //                 );
            //             }
            //             if (auth()->user()->can('register_report.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getRegisterReport']),
            //                     __('report.register_report'),
            //                     ['icon' => 'fa fas fa-briefcase', 'active' => request()->segment(2) == 'register-report']
            //                 );
            //             }
            //             if (auth()->user()->can('sales_representative.view')) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getSalesRepresentativeReport']),
            //                     __('report.sales_representative'),
            //                     ['icon' => 'fa fas fa-user', 'active' => request()->segment(2) == 'sales-representative-report']
            //                 );
            //             }
            //             if (auth()->user()->can('purchase_n_sell_report.view') && in_array('tables', $enabled_modules)) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getTableReport']),
            //                     __('restaurant.table_report'),
            //                     ['icon' => 'fa fas fa-table', 'active' => request()->segment(2) == 'table-report']
            //                 );
            //             }

            //             if (auth()->user()->can('tax_report.view') && ! empty(config('constants.enable_gst_report_india'))) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'gstSalesReport']),
            //                     __('lang_v1.gst_sales_report'),
            //                     ['icon' => 'fa fas fa-percent', 'active' => request()->segment(2) == 'gst-sales-report']
            //                 );

            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'gstPurchaseReport']),
            //                     __('lang_v1.gst_purchase_report'),
            //                     ['icon' => 'fa fas fa-percent', 'active' => request()->segment(2) == 'gst-purchase-report']
            //                 );
            //             }

            //             if (auth()->user()->can('sales_representative.view') && in_array('service_staff', $enabled_modules)) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'getServiceStaffReport']),
            //                     __('restaurant.service_staff_report'),
            //                     ['icon' => 'fa fas fa-user-secret', 'active' => request()->segment(2) == 'service-staff-report']
            //                 );
            //             }

            //             if ($is_admin) {
            //                 $sub->url(
            //                     action([\App\Http\Controllers\ReportController::class, 'activityLog']),
            //                     __('lang_v1.activity_log'),
            //                     ['icon' => 'fa fas fa-user-secret', 'active' => request()->segment(2) == 'activity-log']
            //                 );
            //             }
            //         },
            //         ['icon' => 'fa fas fa-chart-bar', 'id' => 'tour_step8']
            //     )->order(55);
            // }
            

            //Backup menu

            // if (auth()->user()->can('backup')) {
            //     $menu->url(action([\App\Http\Controllers\BackUpController::class, 'index']), __('lang_v1.backup'), ['icon' => 'fa fas fa-hdd', 'active' => request()->segment(1) == 'backup'])->order(60);
            // }

            //Modules menu

            // if (auth()->user()->can('manage_modules')) {
            //     $menu->url(action([\App\Http\Controllers\Install\ModulesController::class, 'index']), __('lang_v1.modules'), ['icon' => 'fa fas fa-plug', 'active' => request()->segment(1) == 'manage-modules'])->order(60);
            // }

            //Booking menu

            // if (in_array('booking', $enabled_modules) && (auth()->user()->can('crud_all_bookings') || auth()->user()->can('crud_own_bookings'))) {
            //     $menu->url(action([\App\Http\Controllers\Restaurant\BookingController::class, 'index']), __('restaurant.bookings'), ['icon' => 'fas fa fa-calendar-check', 'active' => request()->segment(1) == 'bookings'])->order(65);
            // }

            //Kitchen menu

            // if (in_array('kitchen', $enabled_modules)) {
            //     $menu->url(action([\App\Http\Controllers\Restaurant\KitchenController::class, 'index']), __('restaurant.kitchen'), ['icon' => 'fa fas fa-fire', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'kitchen'])->order(70);
            // }

            //Service Staff menu

            // if (in_array('service_staff', $enabled_modules)) {
            //     $menu->url(action([\App\Http\Controllers\Restaurant\OrderController::class, 'index']), __('restaurant.orders'), ['icon' => 'fa fas fa-list-alt', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'orders'])->order(75);
            // }

            
            //Notification template menu

            // if (auth()->user()->can('send_notifications')) {
            //     $menu->url(action([\App\Http\Controllers\NotificationTemplateController::class, 'index']), __('lang_v1.notification_templates'), ['icon' => 'fa fas fa-envelope', 'active' => request()->segment(1) == 'notification-templates'])->order(80);
            // }

            
            // Settings Dropdown


            if (auth()->user()->can('business_settings.access') ||
                auth()->user()->can('barcode_settings.access') ||
                auth()->user()->can('invoice_settings.access') ||
                auth()->user()->can('tax_rate.view') ||
                auth()->user()->can('tax_rate.create') ||
                auth()->user()->can('access_package_subscriptions')) {
                $menu->dropdown(
                    __('business.settings'),
                    function ($sub) use ($enabled_modules) {
                        if (auth()->user()->can('business_settings.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\BusinessController::class, 'getBusinessSettings']),
                                __('business.business_settings'),
                                ['icon' => 'fa fas fa-cogs', 'active' => request()->segment(1) == 'business', 'id' => 'tour_step2']
                            );
                            $sub->url(
                                action([\App\Http\Controllers\BusinessLocationController::class, 'index']),
                                __('business.business_locations'),
                                ['icon' => 'fa fas fa-map-marker', 'active' => request()->segment(1) == 'business-location']
                            );
                        }
                        // if (auth()->user()->can('invoice_settings.access')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\InvoiceSchemeController::class, 'index']),
                        //         __('invoice.invoice_settings'),
                        //         ['icon' => 'fa fas fa-file', 'active' => in_array(request()->segment(1), ['invoice-schemes', 'invoice-layouts'])]
                        //     );
                        // }
                        // if (auth()->user()->can('barcode_settings.access')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\BarcodeController::class, 'index']),
                        //         __('barcode.barcode_settings'),
                        //         ['icon' => 'fa fas fa-barcode', 'active' => request()->segment(1) == 'barcodes']
                        //     );
                        // }
                        // if (auth()->user()->can('access_printers')) {
                        //     $sub->url(
                        //         action([\App\Http\Controllers\PrinterController::class, 'index']),
                        //         __('printer.receipt_printers'),
                        //         ['icon' => 'fa fas fa-share-alt', 'active' => request()->segment(1) == 'printers']
                        //     );
                        // }

                        if (auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\TaxRateController::class, 'index']),
                                __('tax_rate.tax_rates'),
                                ['icon' => 'fa fas fa-bolt', 'active' => request()->segment(1) == 'tax-rates']
                            );
                        }

                        if (in_array('tables', $enabled_modules) && auth()->user()->can('access_tables')) {
                            $sub->url(
                                action([\App\Http\Controllers\Restaurant\TableController::class, 'index']),
                                __('restaurant.tables'),
                                ['icon' => 'fa fas fa-table', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'tables']
                            );
                        }

                        if (in_array('modifiers', $enabled_modules) && (auth()->user()->can('product.view') || auth()->user()->can('product.create'))) {
                            $sub->url(
                                action([\App\Http\Controllers\Restaurant\ModifierSetsController::class, 'index']),
                                __('restaurant.modifiers'),
                                ['icon' => 'fa fas fa-pizza-slice', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'modifiers']
                            );
                        }

                        if (in_array('types_of_service', $enabled_modules) && auth()->user()->can('access_types_of_service')) {
                            $sub->url(
                                action([\App\Http\Controllers\TypesOfServiceController::class, 'index']),
                                __('lang_v1.types_of_service'),
                                ['icon' => 'fa fas fa-user-circle', 'active' => request()->segment(1) == 'types-of-service']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-cog', 'id' => 'tour_step3']
                )->order(85);
            }

            
            //Contacts dropdown


            // if (auth()->user()->can('supplier.view') || auth()->user()->can('customer.view') || auth()->user()->can('supplier.view_own') || auth()->user()->can('customer.view_own')) {
                // $menu->dropdown(
                //     __('Application'),
                //     function ($sub) {
                //         if (auth()->user()->can('cars.view')) {
                //             $sub->url(
                //                 action([\App\Http\Controllers\CarController::class, 'index'], ['type' => 'car']),
                //                 __('car'),
                //                 ['icon' => 'fa fas fa-car', 'active' => request()->input('type') == 'car']
                //             );
                //         }
                        
                //     },
                //     ['icon' => 'fa fa-book', 'id' => 'tour_step4']
                // )->order(86);
            // }
        });

        //Add menus from modules
        $moduleUtil = new ModuleUtil;
        $moduleUtil->getModuleData('modifyAdminMenu');

        return $next($request);
    }
}
