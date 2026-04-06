<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Department;

class BranchDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Chi nhánh
        $branches = [
            [
                'name'         => 'Trụ sở chính',
                'code'         => 'TSC',
                'address'      => '123 Đường Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                'phone'        => '028 3822 1234',
                'email'        => 'tsc@vinayuuki.com',
                'manager_name' => 'Nguyễn Văn An',
                'is_active'    => true,
            ],
            [
                'name'         => 'Chi nhánh Hà Nội',
                'code'         => 'HN01',
                'address'      => '45 Phố Huế, Quận Hai Bà Trưng, Hà Nội',
                'phone'        => '024 3825 5678',
                'email'        => 'hanoi@vinayuuki.com',
                'manager_name' => 'Trần Thị Bích',
                'is_active'    => true,
            ],
            [
                'name'         => 'Chi nhánh Đà Nẵng',
                'code'         => 'DN01',
                'address'      => '88 Đường Nguyễn Văn Linh, Quận Hải Châu, Đà Nẵng',
                'phone'        => '0236 3822 9999',
                'email'        => 'danang@vinayuuki.com',
                'manager_name' => 'Lê Minh Tuấn',
                'is_active'    => true,
            ],
        ];

        foreach ($branches as $data) {
            Branch::firstOrCreate(['code' => $data['code']], $data);
        }

        $hq = Branch::where('code', 'TSC')->first();
        $hn = Branch::where('code', 'HN01')->first();
        $dn = Branch::where('code', 'DN01')->first();

        // Phòng ban
        $departments = [
            // Trụ sở chính
            ['name' => 'Ban Giám đốc',           'code' => 'BGD',  'branch_id' => $hq?->id, 'manager_name' => 'Nguyễn Văn An',    'is_active' => true],
            ['name' => 'Ban Quản lý Dự án',       'code' => 'BQLDA','branch_id' => $hq?->id, 'manager_name' => 'Phạm Quốc Hùng',  'is_active' => true],
            ['name' => 'Ban Tài chính – Kế toán', 'code' => 'BTC',  'branch_id' => $hq?->id, 'manager_name' => 'Võ Thị Thu Hà',   'is_active' => true],
            ['name' => 'Phòng Nhân sự',           'code' => 'PNS',  'branch_id' => $hq?->id, 'manager_name' => 'Đinh Thị Lan',    'is_active' => true],
            ['name' => 'Phòng Công nghệ thông tin','code' => 'PCNTT','branch_id' => $hq?->id, 'manager_name' => 'Bùi Văn Khoa',   'is_active' => true],
            ['name' => 'Phòng Marketing',          'code' => 'PMK',  'branch_id' => $hq?->id, 'manager_name' => 'Ngô Minh Châu',  'is_active' => true],
            ['name' => 'Phòng Pháp lý',            'code' => 'PPL',  'branch_id' => $hq?->id, 'manager_name' => 'Hoàng Văn Đức',  'is_active' => true],
            // Hà Nội
            ['name' => 'Phòng Kinh doanh HN',     'code' => 'PKDHN','branch_id' => $hn?->id, 'manager_name' => 'Trần Thị Bích',  'is_active' => true],
            ['name' => 'Phòng Tư vấn HN',         'code' => 'PTVHN','branch_id' => $hn?->id, 'manager_name' => 'Lê Quang Minh',  'is_active' => true],
            // Đà Nẵng
            ['name' => 'Phòng Kinh doanh ĐN',     'code' => 'PKDDN','branch_id' => $dn?->id, 'manager_name' => 'Lê Minh Tuấn',   'is_active' => true],
            ['name' => 'Phòng Tư vấn ĐN',         'code' => 'PTVDN','branch_id' => $dn?->id, 'manager_name' => 'Trương Thị Ngọc','is_active' => true],
        ];

        foreach ($departments as $data) {
            Department::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
