<?php

// Xác định đường dẫn tới file artisan
$artisanPath = __DIR__ . '/artisan';

// Hàm helper để chạy lệnh và hiển thị kết quả
function run_artisan_command($command, $artisanPath) {
    echo "<h3>Thực thi: php artisan $command</h3>";
    $output = [];
    $return_var = 0;
    
    // Thử dùng shell_exec trước (thường trả về string full)
    try {
        $result = shell_exec("php $artisanPath $command 2>&1");
        if ($result) {
            echo "<pre>$result</pre>";
            return;
        }
    } catch (Exception $e) {
        // Ignored
    }

    // Fallback sang exec
    exec("php $artisanPath $command 2>&1", $output, $return_var);
    
    if ($output) {
        echo "<pre>" . implode("\n", $output) . "</pre>";
    } else {
        echo "<pre>Không có output hoặc lệnh chạy thất bại (Exit code: $return_var)</pre>";
    }
}

// Kiểm tra tool có được cho phép chạy không
// Có thể thêm password đơn giản ở đây để bảo mật

echo "<h1>Deployment Helper</h1>";

// 1. Xóa Cache
run_artisan_command("config:clear", $artisanPath);
run_artisan_command("cache:clear", $artisanPath);
run_artisan_command("view:clear", $artisanPath);
run_artisan_command("route:clear", $artisanPath);
run_artisan_command("migrate", $artisanPath);
run_artisan_command("db:seed --class=PermissionSeeder", $artisanPath);

// Xóa cache thành công, Pusher Cloud sẽ tự động xử lý Websocket mà không cần chạy ngầm

echo "<h2>Đã hoàn tất!</h2>";
?>
