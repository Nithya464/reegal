<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'user.view'],
            ['name' => 'user.create'],
            ['name' => 'user.update'],
            ['name' => 'user.delete'],

            ['name' => 'supplier.view'],
            ['name' => 'supplier.create'],
            ['name' => 'supplier.update'],
            ['name' => 'supplier.delete'],

            ['name' => 'customer.view'],
            ['name' => 'customer.create'],
            ['name' => 'customer.update'],
            ['name' => 'customer.delete'],

            ['name' => 'product.view'],
            ['name' => 'product.create'],
            ['name' => 'product.update'],
            ['name' => 'product.delete'],
            ['name' => 'product.opening_stock'],
            ['name' => 'view_purchase_price'],
            
            ['name' => 'purchase.view'],
            ['name' => 'purchase.create'],
            ['name' => 'purchase.update'],
            ['name' => 'purchase.delete'],

            ['name' => 'purchase_order.create'],
            ['name' => 'purchase_order.update'],
            ['name' => 'purchase_order.delete'],

            ['name' => 'sell.view'],
            ['name' => 'sell.create'],
            ['name' => 'sell.update'],
            ['name' => 'sell.delete'],

            ['name' => 'purchase_n_sell_report.view'],
            ['name' => 'contacts_report.view'],
            ['name' => 'stock_report.view'],
            ['name' => 'tax_report.view'],
            ['name' => 'trending_product_report.view'],
            ['name' => 'register_report.view'],
            ['name' => 'sales_representative.view'],
            ['name' => 'expense_report.view'],

            ['name' => 'business_settings.access'],
            ['name' => 'barcode_settings.access'],
            ['name' => 'invoice_settings.access'],

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
            ['name' => 'expense.access'],

            ['name' => 'access_all_locations'],
            ['name' => 'dashboard.data'],
            ['name' => 'supplier.view_own'],

            ['name' => 'stock.create'],
            ['name' => 'purchase_order_R.view_all'],
            ['name' => 'purchase_order.view_all'],
            ['name' => 'purchase_order_IM.draft_view_all'],
            ['name' => 'purchase_order_IM.update'],
            ['name' => 'receive_stock.view_all'],
            ['name' => 'receive_stock.create'],
            ['name' => 'receive_stock.update'],

            ['name' => 'purchase_order_IR.view_all'],
            ['name' => 'purchase_order_IR.create'],
            ['name' => 'purchase_order_IR.update'],
            ['name' => 'purchase_order_IR.delete'],

            ['name' => 'roles.view'],
            ['name' => 'roles.create'],
            ['name' => 'roles.update'],
            ['name' => 'roles.delete'],
            ['name' => 'access_printers'],

            ['name' => 'manage_expense.view'],
            ['name' => 'manage_expense.create'],
            ['name' => 'manage_expense.update'],
            ['name' => 'manage_expense.delete'],
            ['name' => 'direct_sell_pos.view'],
            ['name' => 'direct_sell_pos.update'],

            ['name' => 'customer.view_own'],
            ['name' => 'customer_irrespective_of_sell'],
            ['name' => 'car.view'],
            ['name' => 'car.create'],
            ['name' => 'car.update'],
            ['name' => 'car.delete'],

            ['name' => 'route.view'],
            ['name' => 'route.create'],
            ['name' => 'route.update'],
            ['name' => 'route.delete'],

            ['name' => 'purchase_return.view'],
            ['name' => 'purchase_return.create'],
            ['name' => 'purchase_return.update'],
            ['name' => 'purchase_return.delete'],

            ['name' => 'state.view'],
            ['name' => 'state.create'],
            ['name' => 'state.update'],
            ['name' => 'state.delete'],

            ['name' => 'city.view'],
            ['name' => 'city.create'],
            ['name' => 'city.update'],
            ['name' => 'city.delete'],

            ['name' => 'route.view'],
            ['name' => 'route.create'],
            ['name' => 'route.update'],
            ['name' => 'route.delete'],
            ['name' => 'customer_location.view'],

            ['name' => 'zipcode.view'],
            ['name' => 'zipcode.create'],
            ['name' => 'zipcode.update'],
            ['name' => 'zipcode.delete'],

            ['name' => 'tax.view'],
            ['name' => 'tax.create'],
            ['name' => 'tax.update'],
            ['name' => 'tax.delete'],

            ['name' => 'draft_customer.view'],
            ['name' => 'draft_customer.create'],
            ['name' => 'draft_customer.update'],
            ['name' => 'draft_customer.delete'],

            ['name' => 'price_level.view'],
            ['name' => 'price_level.create'],
            ['name' => 'price_level.update'],
            ['name' => 'price_level.delete'],

            ['name' => 'direct_sell_sp.view'],
            ['name' => 'direct_sell_sp.create'],
            ['name' => 'direct_sell_sp.update'],
            ['name' => 'direct_sell_sp.delete'],

            ['name' => 'direct_sell_pos.create'],
            ['name' => 'direct_sell_pos.delete'],
            ['name' => 'receive_payment.view'],
            ['name' => 'receive_payment.create'],
            ['name' => 'direct_sell_pos.reset']
        ];

        $existing_permissions = Permission::pluck('name')->toArray();
        $newpermission_names = array_column($data,'name');
        
        // avoid duplicate inserts in the permission table after each seeding 

        if(count($newpermission_names) > count($existing_permissions))
        {
            $permissions_arr = [];
            $new_permissions = array_diff($newpermission_names,$existing_permissions);
            $index = 0;
            foreach($new_permissions  as $permission)
            {
                $permissions_arr[$index]['name'] = $permission;
                $index++;
            }
            $data = $permissions_arr;  
            echo 'new Permissions added successfully!!!';
        }

        // avoid duplicate inserts in the permission table after each seeding 

        // dd($data);
        $insert_data = [];
        $time_stamp = \Carbon::now()->toDateTimeString();
        foreach ($data as $d) {
            $d['guard_name'] = 'web';
            $d['created_at'] = $time_stamp;
            $insert_data[] = $d;
        }
        Permission::insert($insert_data);
    }
}
