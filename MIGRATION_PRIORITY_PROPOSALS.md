# Migration: Add Priority Column to Proposals Table
## Migration này thêm cột `priority` vào bảng `proposals` để hỗ trợ phân loại mức độ ưu tiên của các đề xuất.

## sql: ALTER TABLE proposals ADD COLUMN priority VARCHAR(255) NULL DEFAULT 'normal' AFTER status;

## Thuộc tính cột:
### Tên: `priority`
### Kiểu dữ liệu: `VARCHAR(255)`
### Nullable: Có (NULL)
### Default value: `'normal'`
### Vị trí: Sau cột `status`
### Comment: Mức độ ưu tiên của đề xuất: very_low, low, normal, high, very_high ứng với Rất thấp, Thấp, Bình thường, Cao, Rất cao

## Giá trị Priority

Cột `priority` sử dụng các giá trị enum được định nghĩa trong `App\Enums\ProposalPriority`:

| Giá trị | Nhãn hiển thị | Nhãn với icon | Mô tả |
|---------|---------------|---------------|--------|
| `very_low` | Rất thấp | ⬇ Rất thấp | Mức ưu tiên thấp nhất |
| `low` | Thấp | ↘ Thấp | Mức ưu tiên thấp |
| `normal` | Bình thường | ➡ Bình thường | Mức ưu tiên mặc định |
| `high` | Cao | ↗ Cao | Mức ưu tiên cao |
| `very_high` | Rất cao | ⬆ Rất cao | Mức ưu tiên cao nhất |

## Migration Commands

### Chạy migration:
php artisan migrate

### Rollback migration:
php artisan migrate:rollback