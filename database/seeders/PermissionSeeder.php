<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions for User Management
        $permissions = [
            'view_dashboard' => 'Xem bảng điều khiển',
            'view_user' => 'Xem danh sách tài khoản',
            'create_user' => 'Thêm mới tài khoản',
            'update_user' => 'Chỉnh sửa tài khoản',
            'delete_user' => 'Xóa tài khoản',
            'assign_role' => 'Phân quyền tài khoản',

            // Permissions for Role Management
            'view_role' => 'Xem danh sách chức vụ',
            'create_role' => 'Thêm mới chức vụ',
            'update_role' => 'Chỉnh sửa chức vụ',
            'delete_role' => 'Xóa chức vụ',

            // Permissions for Media Manager
            'view_media' => 'Xem tài liệu an toàn',
            'create_media' => 'Tải lên tài liệu',
            'update_media' => 'Chỉnh sửa tài liệu',
            'delete_media' => 'Xóa tài liệu',





            // Permissions for Finance
            'view_finance' => 'Xem tài chính & phí',
            'create_finance' => 'Thêm giao dịch',
            'update_finance' => 'Cập nhật giao dịch',
            'delete_finance' => 'Xóa giao dịch',

            // Permissions for News
            'view_news' => 'Xem danh sách tin tức',
            'create_news' => 'Tạo tin tức',
            'update_news' => 'Cập nhật tin tức',
            'delete_news' => 'Xóa tin tức',

            // Permissions for Branch (Chi nhánh)
            'view_branch' => 'Xem chi nhánh',
            'create_branch' => 'Thêm chi nhánh',
            'update_branch' => 'Cập nhật chi nhánh',
            'delete_branch' => 'Xóa chi nhánh',


            // Permissions for Orders Management
            'view_orders' => 'Xem đơn hàng',
            'create_orders' => 'Tạo đơn hàng',

            // Permissions for payment receipt
            'view_payment_receipt' => 'Xem phiếu thu',
            'create_payment_receipt' => 'Thu phí',
            'update_payment_receipt' => 'Cập nhật phiếu thu',

            // Permissions for Services Management
            'view_services' => 'Xem dịch vụ',
            'create_services' => 'Thêm dịch vụ',
            'update_services' => 'Cập nhật dịch vụ',
            'delete_services' => 'Xóa dịch vụ',

            // Permissions for Post
            'view_post' => 'Xem bài viết',
            'create_post' => 'Tạo bài viết',
            'update_post' => 'Cập nhật bài viết',
            'delete_post' => 'Xóa bài viết',

            // Permissions for Category
            'view_category' => 'Xem chuyên mục',
            'create_category' => 'Thêm chuyên mục',
            'update_category' => 'Cập nhật chuyên mục',
            'delete_category' => 'Xóa chuyên mục',

        ];

        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['display_name' => $displayName]
            );
        }

        // Assign to Admin Role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(array_keys($permissions));
        }

        $this->command->info('User permissions seeded successfully.');
    }
}
