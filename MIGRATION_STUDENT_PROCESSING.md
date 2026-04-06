# Migration: Student Processing Tables
## Bộ migration này tạo các bảng cho task xử lý hồ sơ học viên gồm `student_profiles`, `student_processing_deadlines`, `student_checklist_items`.
-> Các bảng trên là nền tảng cho module processing: thông tin hồ sơ, deadline, checklist/hồ sơ hoàn thiện.

## 1) Migration tạo bảng `student_profiles`

## SQL tham chiếu:
CREATE TABLE student_profiles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_code VARCHAR(255) NOT NULL UNIQUE,
  full_name VARCHAR(255) NOT NULL,
  date_of_birth DATE NOT NULL,
  student_phone VARCHAR(30) NOT NULL,
  address VARCHAR(255) NOT NULL,
  parent_name VARCHAR(255) NULL,
  parent_phone VARCHAR(30) NULL,
  program_country VARCHAR(50) NULL,
  education_partner_id BIGINT UNSIGNED NULL,
  education_admission_id BIGINT UNSIGNED NULL,
  consultant_id BIGINT UNSIGNED NULL,
  processor_id BIGINT UNSIGNED NULL,
  stage ENUM('input','school','embassy','visa') NOT NULL DEFAULT 'input',
  status VARCHAR(120) NOT NULL DEFAULT 'Đang hướng dẫn hồ sơ đầu vào',
  risk_level ENUM('low','medium','high') NOT NULL DEFAULT 'low',
  result_summary VARCHAR(255) NULL,
  issue_note VARCHAR(255) NULL,
  full_dossier_files JSON NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL
);

## Thuộc tính chính:
### Bảng: `student_profiles`
### Mục đích: lưu hồ sơ tổng quan của học viên trong pipeline xử lý
### Điểm đáng chú ý:
- Có soft delete (`deleted_at`)
- `student_code` unique
- `stage`, `status`, `risk_level` phục vụ workflow vận hành
- `full_dossier_files` lưu metadata file hoàn thiện dạng JSON theo format chuẩn:
  - `name`: tên file hiển thị
  - `provider`: disk/provider lưu file (`public`, `s3`, ...)
  - `file_path`: đường dẫn thực tế trên provider
  - `uploaded_at`: thời điểm upload

## Cập nhật mới (2026-03-19)

- Đã thêm migration `2026_03_19_120000_remove_metadata_from_student_profiles_table.php` để loại bỏ cột `metadata` khỏi `student_profiles`.
- Lý do: cột `metadata` không còn được sử dụng trong luồng xử lý hồ sơ hiện tại.
- Đã thêm migration `2026_03_19_121000_remove_expected_departure_date_from_student_profiles_table.php` để loại bỏ cột `expected_departure_date` do chưa có luồng BE/FE sử dụng.
- Đã thêm migration `2026_03_19_121100_normalize_student_processing_file_json_structure.php` để chuẩn hóa dữ liệu file JSON sang format `provider` + `file_path` (không phụ thuộc URL cứng theo server).

## 2) Migration tạo bảng `student_processing_deadlines`

## SQL tham chiếu:
CREATE TABLE student_processing_deadlines (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_profile_id BIGINT UNSIGNED NOT NULL,
  deadline_type VARCHAR(80) NULL,
  title VARCHAR(255) NOT NULL,
  due_date DATE NOT NULL,
  completed_at TIMESTAMP NULL,
  note TEXT NULL,
  created_by BIGINT UNSIGNED NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX student_deadlines_student_status_due_idx (student_profile_id, completed_at, due_date)
);

## Thuộc tính chính:
### Bảng: `student_processing_deadlines`
### Mục đích: quản lý deadline theo từng hồ sơ học viên
### Điểm đáng chú ý:
- Liên kết `student_profile_id` cascade delete theo hồ sơ
- Có `completed_at` để đánh dấu hoàn thành
- Index tổng hợp tối ưu danh sách deadline theo trạng thái và hạn

## 3) Migration tạo bảng `student_checklist_items`

## SQL tham chiếu:
CREATE TABLE student_checklist_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_profile_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  status ENUM('missing','need_more','completed') NOT NULL DEFAULT 'missing',
  note TEXT NULL,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  attachments JSON NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

## Thuộc tính chính:
### Bảng: `student_checklist_items`
### Mục đích: quản lý checklist hồ sơ theo từng học viên
### Điểm đáng chú ý:
- `status` chuẩn hóa theo 3 trạng thái nghiệp vụ
- `attachments` lưu danh sách file checklist theo JSON cùng format chuẩn `name/provider/file_path/uploaded_at`
- `sort_order` hỗ trợ hiển thị theo thứ tự xử lý

## Migration Commands

### Chạy migration:
php artisan migrate

### Rollback migration:
php artisan migrate:rollback
