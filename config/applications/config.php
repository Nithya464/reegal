<?php

return [
    'role' => [
        'Admin',
        'Account Manager',
        'Inventory Manager',
        'Inventory Receiver',
        'Packer',
        'POS',
        'Sales Manager',
        'Sales Person',
        'Warehouse Manager',
        'Driver',
    ],
    'user' => [
        [
            'username' => 'regal',
            'first_name' => 'MR Reegal',
            'email' => 'regal@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'warehouse_manager',
            'first_name' => 'Warehouse Manager',
            'email' => 'wm@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'sales_person',
            'first_name' => 'Sales Person',
            'email' => 'sp@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'sales_manager',
            'first_name' => 'Sales Manager',
            'email' => 'sm@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'pos',
            'first_name' => 'POS',
            'email' => 'pos@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'packer',
            'first_name' => 'Packer',
            'email' => 'packer@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'inventory_receiver',
            'first_name' => 'Inventory Receiver',
            'email' => 'ir@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'inventory_manager',
            'first_name' => 'Inventory Manager',
            'email' => 'im@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'account',
            'first_name' => 'Account Manager',
            'email' => 'am@gmail.com',
            'password' => '12345678',
        ],
        [
            'username' => 'driver',
            'first_name' => 'Driver Manager',
            'email' => 'driver@gmail.com',
            'password' => '12345678',
        ],
    ],

    'admin_user_role' => 'Admin#1',

    'permissions'      =>  [

    'inv_manager_permissions_grant' => [

        ['name' => 'product.opening_stock'],
        ['name' => 'view_purchase_price'],
        ['name' => 'supplier.create'],
        ['name' => 'supplier.update'],
        ['name' => 'supplier.delete'],

        ['name' => 'product.view'],
        ['name' => 'product.create'],
        ['name' => 'product.update'],
        ['name' => 'product.delete'],

        ['name' => 'brand.view'],
        ['name' => 'brand.create'],
        ['name' => 'brand.update'],
        ['name' => 'brand.delete'],


        ['name' => 'tax_rate.view'],
        ['name' => 'tax_rate.create'],
        ['name' => 'tax_rate.update'],
        ['name' => 'tax_rate.delete'],


        ['name' => 'unit.view'],
        ['name' => 'unit.create'],
        ['name' => 'unit.update'],
        ['name' => 'unit.delete'],


        ['name' => 'category.view'],
        ['name' => 'category.create'],
        ['name' => 'category.update'],
        ['name' => 'category.delete'],

        ['name' => 'purchase_order.create'],
        ['name' => 'purchase_order.update'],
        ['name' => 'purchase_order.delete'],


        ['name' => 'supplier.view_own'],
        ['name' => 'stock.create'],

        ['name' => 'purchase_order.view_all'],
        ['name' => 'purchase_order_IM.draft_view_all'],
        ['name' => 'purchase_order_IM.update'],
        
        ['name' => 'receive_stock.view_all'],
        ['name' => 'receive_stock.create'],
        ['name' => 'receive_stock.update'],
    ],

    'inv_manager_permissions_revoke' => [
        
    ],

    'inv_receiver_permissions_grant' => [
        'stock.create',
        'purchase_order_R.view_all',
        'purchase_order_IR.view_all',
        'purchase_order_IR.create',
        'purchase_order_IR.update',
        'purchase_order_IR.delete'
    ],

    'inv_receiver_permissions_revoke' => [

    ],

    'warehs_manager_permissions_grant' => [
        'product.opening_stock',
        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',
        'view_purchase_price',
        'product.view',
        'product.create',
        'product.update',
        'product.delete',
        'business_settings.access',
        'barcode_settings.access',
        'invoice_settings.access',
        'brand.view',
        'brand.create',
        'brand.update',
        'brand.delete',
        'tax_rate.view',
        'tax_rate.create',
        'tax_rate.update',
        'tax_rate.delete',
        'unit.view',
        'unit.create',
        'unit.update',
        'unit.delete',
        'category.view',
        'category.create',
        'category.update',
        'category.delete',
        'access_printers',
    ],

    'warehs_manager_permissions_revoke' => [

    ],

     'acc_manager_permissions_grant' => [
        'manage_expense.view',
        'manage_expense.create',
        'manage_expense.update',
        'manage_expense.delete',
     ],

    'acc_manager_permissions_revoke' => [

    ],

    'sales_manager_permissions_grant' => [
        'customer.create',
        'customer.update',
        'customer.delete',
        'customer.view_own',
        'customer_irrespective_of_sell',
        'car.view',
        'car.create',
        'car.update',
        'car.delete',
        'route.view',
        'route.create',
        'route.update',
        'route.delete',
        'purchase_return.view',
        'purchase_return.create',
        'purchase_return.update',
        'purchase_return.delete',
        'state.view',
        'state.create',
        'state.update',
        'state.delete',
        'city.view',
        'city.create',
        'city.update',
        'city.delete',
        'customer_location.view',
        'zipcode.view',
        'zipcode.create',
        'zipcode.update',
        'zipcode.delete',
        'tax.view',
        'tax.create',
        'tax.update',
        'tax.delete',
        'draft_customer.view',
        'draft_customer.create',
        'draft_customer.update',
        'draft_customer.delete',
        'price_level.view',
        'price_level.create',
        'price_level.update',
        'price_level.delete'
     ],

    'sales_manager_permissions_revoke' => [

    ],

    'salesperson_permissions_grant' => [
        'customer.view',
        'product.view',
        'route.view',
        'route.create',
        'route.update',
        'route.delete',
        'direct_sell_sp.view',
        'direct_sell_sp.create',
        'direct_sell_sp.update',
        'direct_sell_sp.delete'
    ],

     'salesperson_permissions_revoke' => [
       
     ],

     'packer_permissions_grant' => [
        'user.view'
     ],

     'packer_permissions_revoke' => [

     ],

     'pos_permissions_grant' => [
        'product.view',
        'direct_sell_pos.view',
        'direct_sell_pos.create',
        'direct_sell_pos.update',
        'direct_sell_pos.delete',
        'receive_payment.view',
        'receive_payment.create',
        'direct_sell_pos.reset',
     ],

     'pos_permissions_revoke' => [
      
     ],


    ]
];