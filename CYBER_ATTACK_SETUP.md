# Cyber Attack Management - Setup Instructions

## File yang Telah Dibuat

1. **Migration**: `database/migrations/2026_01_15_172000_create_cyber_attacks_table.php`
2. **Model**: `app/Models/CyberAttack.php`
3. **Controller**: `app/Http/Controllers/CyberAttackController.php`
4. **Import Class**: `app/Imports/CyberAttackImport.php`
5. **Views**:
   - `resources/views/cyber_attacks/index.blade.php` (List data)
   - `resources/views/cyber_attacks/import.blade.php` (Import form)
6. **Routes**: Ditambahkan ke `routes/web.php`

## Instalasi Laravel Excel Package

Untuk menggunakan fitur import, Anda perlu menginstall package **Laravel Excel**:

```bash
composer require maatwebsite/excel
```

Setelah package terinstall, publish konfigurasinya (opsional):

```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

## Routes yang Tersedia

Semua routes dibuat dengan prefix `cyber-attacks` dan memerlukan role `admin` atau `editor`:

- `GET /cyber-attacks` - Halaman list data
- `GET /cyber-attacks/list` - API untuk ambil data (JSON)
- `GET /cyber-attacks/import` - Halaman form import
- `POST /cyber-attacks/import` - Proses import file
- `GET /cyber-attacks/download-template` - **Download template CSV untuk import**
- `DELETE /cyber-attacks/{id}` - Hapus data
- `GET /cyber-attacks/statistics` - API statistik (untuk dashboard)

## Format File Import

File Excel/CSV harus memiliki kolom header dengan nama berikut:

| Column Name          | Type    | Required | Description                    |
|---------------------|---------|----------|--------------------------------|
| attack_id           | string  | No       | Unique attack identifier       |
| source_ip           | string  | No       | Source IP address              |
| destination_ip      | string  | No       | Destination IP address         |
| source_country      | string  | No       | Source country                 |
| destination_country | string  | No       | Destination country            |
| protocol            | string  | No       | Protocol (TCP, UDP, HTTP, etc) |
| source_port         | integer | No       | Source port number             |
| destination_port    | integer | No       | Destination port number        |
| attack_type         | string  | No       | Type of attack                 |
| payload_size_bytes  | integer | No       | Payload size in bytes          |
| detection_label     | string  | No       | Detection label                |
| confidence_score    | float   | No       | Confidence score (0-1)         |
| ml_model            | string  | No       | ML model name                  |
| affected_system     | string  | No       | Affected system                |
| port_type           | string  | No       | Port type                      |

**Contoh file Excel**: Lihat `storage/templates/cyber_attacks_template.xlsx`

## Batasan Import

- Maksimal ukuran file: **10MB**
- Format yang didukung: `.xlsx`, `.xls`, `.csv`
- Batch size: 1000 records per batch
- Chunk size: 1000 records per chunk

## Testing

Untuk test fitur ini:

1. Login sebagai admin/editor
2. Akses: `http://your-domain/cyber-attacks`
3. Klik tombol "Import Data"
4. **Klik "Download Template" untuk mendapatkan format file yang benar**
5. Isi template dengan data Anda atau gunakan contoh data yang sudah ada
6. Upload file Excel/CSV dengan format yang sesuai
7. Data akan otomatis masuk ke database

## Cara Download Template

Ada 3 cara untuk mendapatkan template:

### 1. Dari Halaman Import (Recommended)
- Akses `/cyber-attacks/import`
- Klik tombol **"Download Template CSV"** di bagian atas atau di box highlight
- Template akan otomatis terdownload

### 2. Direct Link
- Akses langsung: `http://your-domain/cyber-attacks/download-template`

### 3. Manual File
- Template juga tersedia di: `storage/templates/cyber_attacks_template.csv`

## Authorization

Fitur ini dilindungi oleh:
- Middleware: `auth`, `throttle:60,1`
- Role check: `admin` atau `editor` atau permission `manage-cyber-attack`

Jika user tidak memiliki akses, akan muncul error 403.

## Logs

Semua aktivitas akan tercatat di Laravel log:
- Import success/failure
- Delete operations
- Unauthorized access attempts
