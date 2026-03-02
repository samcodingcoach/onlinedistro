# API Documentation - Distro E-Commerce Platform

## 📡 API Architecture Overview

### Base URL
```
http://localhost/api/
```

### Directory Structure
```
api/
├── auth/              # Authentication & Authorization
├── produk/            # Product management
├── kategori/          # Category management
├── banner/            # Banner management
├── distro/            # Store information
├── video/             # Video management
└── product-details.php
```

---

## 🔐 Authentication Flow

### How It Works

```
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│   Admin     │ ───► │  /auth/     │ ───► │   Database  │
│   Login     │      │  login.php  │      │   Verify    │
└─────────────┘      └─────────────┘      └─────────────┘
                            │
                            ▼
                    ┌─────────────┐
                    │   Generate  │
                    │   Token     │
                    │  (24 hours) │
                    └─────────────┘
                            │
                            ▼
                    ┌─────────────┐
                    │   Return    │
                    │   Token     │
                    └─────────────┘
```

### 1. Login

**Endpoint:** `POST /api/auth/login.php`

**Request:**
```json
{
  "username": "admin",
  "password": "password123"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "a1b2c3d4e5f6...",
    "expiry": "2026-03-03 10:30:00",
    "admin": {
      "id_admin": 1,
      "username": "admin"
    }
  }
}
```

**Response (Failed):**
```json
{
  "success": false,
  "message": "Username tidak ditemukan atau tidak aktif"
}
```

### 2. Token Validation

**File:** `/api/auth/validate_token.php`

**Functions:**
- `validateSessionToken($conn, $token)` - Returns admin data if valid
- `requireAuth($conn)` - Enforces authentication, exits if invalid

**Token Storage:**
- Token: 64-character hex string (32 bytes random)
- Expiry: 24 hours from login
- Stored in database (`admin` table: `session_token`, `token_expiry`)

**Usage in Protected Endpoints:**
```php
require_once __DIR__ . '/../auth/validate_token.php';
$admin = requireAuth($conn); // Exits if not authenticated
```

**Sending Token in Requests:**
```
Authorization: Bearer <token>
```
Or as query parameter: `?token=<token>`

---

## 📦 CRUD Pattern

All resource endpoints follow a consistent pattern:

| Operation | Method | File | Auth Required |
|-----------|--------|------|---------------|
| List All | GET | `list.php` | No |
| Create | POST | `new.php` | Yes |
| Update | PUT/POST | `update.php` | Yes |
| Delete | POST | `delete.php` | Yes |
| Search | GET | `search.php` | No |

### Standard Response Format

**Success Response:**
```json
{
  "success": true,
  "data": [...],
  "message": "Data berhasil diambil"
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error description"
}
```

---

## 🛍️ Products API (`/api/produk/`)

### List Products

**Endpoint:** `GET /api/produk/list.php`

**Features:**
- Joins with `kategori` and `admin` tables
- Orders by `favorit DESC, nama_produk ASC`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id_produk": 1,
      "id_kategori": 2,
      "nama_kategori": "T-Shirts",
      "nama_produk": "Urban Tee Black",
      "merk": "Local Brand",
      "kode_produk": "TB001",
      "deskripsi": "Premium cotton t-shirt",
      "harga_aktif": 150000,
      "harga_coret": 200000,
      "ukuran": "M, L, XL",
      "warna": "Black",
      "in_stok": 1,
      "jumlah_stok": 50,
      "gambar1": "product1.jpg",
      "gambar2": "product1_2.jpg",
      "gambar3": "product1_3.jpg",
      "shopee_link": "https://...",
      "tiktok_link": "https://...",
      "aktif": 1,
      "favorit": 1,
      "terjual": 120,
      "update_at": "2026-03-01 10:00:00",
      "id_admin": 1,
      "username": "admin"
    }
  ],
  "message": "Data produk berhasil diambil"
}
```

### Create Product

**Endpoint:** `POST /api/produk/new.php`

**Auth Required:** Yes (Bearer Token)

**Content-Type Support:**
- `multipart/form-data` (for file uploads)
- `application/json` (for data only)

**Request (FormData):**
```
id_kategori: 2
nama_produk: Urban Tee Black
merk: Local Brand
kode_produk: TB001
deskripsi: Premium cotton t-shirt
harga_aktif: 150000
harga_coret: 200000
ukuran: M, L, XL
warna: Black
in_stok: 1
jumlah_stok: 50
gambar1: [file]
gambar2: [file]
gambar3: [file]
shopee_link: https://...
tiktok_link: https://...
aktif: 1
favorit: 1
id_admin: 1
terjual: 0
```

**Request (JSON):**
```json
{
  "id_kategori": 2,
  "nama_produk": "Urban Tee Black",
  "merk": "Local Brand",
  "kode_produk": "TB001",
  "deskripsi": "Premium cotton t-shirt",
  "harga_aktif": 150000,
  "harga_coret": 200000,
  "ukuran": "M, L, XL",
  "warna": "Black",
  "in_stok": 1,
  "jumlah_stok": 50,
  "gambar1": "product1.jpg",
  "gambar2": "product1_2.jpg",
  "gambar3": "product1_3.jpg",
  "shopee_link": "https://...",
  "tiktok_link": "https://...",
  "aktif": 1,
  "favorit": 1,
  "id_admin": 1,
  "terjual": 0
}
```

**Response:**
```json
{
  "success": true,
  "message": "Produk berhasil ditambahkan",
  "id": 123
}
```

**Validation:**
- `kode_produk` is required and must be unique
- Image files: JPG/PNG only, max 1MB each

### Update Product

**Endpoint:** `PUT /api/produk/update.php` (also accepts POST)

**Auth Required:** Yes

**Request:**
```json
{
  "id_produk": 1,
  "nama_produk": "Updated Name",
  "harga_aktif": 175000,
  "gambar1": "new_image.jpg"
}
```

**Features:**
- Validates `id_produk` exists
- Checks duplicate `kode_produk` (excluding current product)
- Handles image upload and deletes old images
- Returns error if no changes made

**Response:**
```json
{
  "success": true,
  "message": "Produk berhasil diupdate"
}
```

### Delete Product

**Endpoint:** `POST /api/produk/delete.php`

**Auth Required:** Yes

**Request:**
```json
{
  "id_produk": 1
}
```

**Features:**
- Deletes associated image files from `/public/images/`
- Returns 404 if product not found

**Response:**
```json
{
  "success": true,
  "message": "Produk berhasil dihapus"
}
```

### Search Products

**Endpoint:** `GET /api/produk/search.php?q=<search_term>`

**Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| `q` | string | Search keyword (optional) |

**Search Fields:**
- `nama_produk`
- `kode_produk`
- `merk`
- `nama_kategori`

**Request:**
```
GET /api/produk/search.php?q=black
```

**Response:**
```json
{
  "success": true,
  "data": [...],
  "message": "Data produk berhasil diambil"
}
```

---

## 📂 Categories API (`/api/kategori/`)

### List Categories

**Endpoint:** `GET /api/kategori/list.php`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id_kategori": 1,
      "nama_kategori": "T-Shirts",
      "background_url": "category_bg.jpg",
      "favorit": 1,
      "aktif": 1
    }
  ],
  "message": "Data kategori berhasil diambil"
}
```

**Ordering:** `favorit DESC, nama_kategori ASC`

---

## 🎨 Banners API (`/api/banner/`)

### List Banners

**Endpoint:** `GET /api/banner/list.php`

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id_banner": 1,
      "nama_banner": "Main Banner",
      "judul": "Summer Collection",
      "deskripsi": "New arrivals for summer",
      "url_gambar": "banner.jpg",
      "aktif": 1,
      "created_at": "2026-03-01 10:00:00"
    }
  ],
  "count": 1
}
```

**Note:** Uses `status` instead of `success` (legacy format)

---

## 🏪 Distro Info API (`/api/distro/`)

### List Distro Info

**Endpoint:** `GET /api/distro/list.php`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id_distro": 1,
      "nama_distro": "APRIL",
      "slogan": "Modern Fashion",
      "alamat": "Jl. Example No. 123",
      "kota": "Makassar",
      "provinsi": "Sulawesi Selatan",
      "no_telepon": "081234567890",
      "ig": "@april.distro",
      "fb": "April Distro",
      "email": "info@april.com",
      "youtube": "April Distro Official",
      "twitter": "@aprildistro",
      "gps": "-5.1477,119.4327",
      "update_at": "2026-03-01 10:00:00"
    }
  ],
  "message": "Data distro berhasil diambil",
  "total": 1
}
```

---

## 🎬 Videos API (`/api/video/`)

### List Videos

**Endpoint:** `GET /api/video/list.php`

**Response:**
```json
{
  "success": true,
  "data": [...],
  "message": "Data video berhasil diambil"
}
```

---

## 👤 Admin API (`/api/auth/`)

### List Admins

**Endpoint:** `GET /api/auth/list.php`

**Auth Required:** Yes

### Create Admin

**Endpoint:** `POST /api/auth/new.php`

**Auth Required:** Yes

---

## 🔄 Data Flow Architecture

### Frontend → API → Database

```
┌─────────────────┐
│  Frontend       │
│  (index.php)    │
│  (product.php)  │
└────────┬────────┘
         │
         │ HTTP Request
         ▼
┌─────────────────┐
│  API Helper     │
│  (api_helper.php)│
│  - fetchApiData │
│  - getApiUrl    │
└────────┬────────┘
         │
         │ HTTP Request to API endpoint
         ▼
┌─────────────────┐
│  API Endpoint   │
│  (list.php)     │
│  - Headers      │
│  - Auth Check   │
│  - Query DB     │
└────────┬────────┘
         │
         │ SQL Query
         ▼
┌─────────────────┐
│  MySQL Database │
│  (doni-distro)  │
└────────┬────────┘
         │
         │ JSON Response
         ▼
┌─────────────────┐
│  Frontend       │
│  Render Data    │
└─────────────────┘
```

### API Helper Function Flow

**File:** `/config/api_helper.php`

```php
// Usage in frontend PHP files
require_once __DIR__ . '/../config/api_helper.php';
$data = fetchApiData($api_file_path);
```

**Process:**
1. Converts file path to URL using `getApiUrl()`
2. Attempts cURL first (`fetchViaCurl()`)
3. Falls back to `file_get_contents()` if cURL unavailable
4. Returns decoded JSON if `success: true` or `status: success`

**URL Construction:**
```
File Path: C:\xampp\htdocs\api\produk\list.php
URL: http://localhost/api/produk/list.php
```

---

## 🔒 Security Features

### 1. CORS Headers
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

### 2. Prepared Statements (SQL Injection Prevention)
```php
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
```

### 3. Input Validation
```php
// Required field check
if ($kode_produk === '') {
    echo json_encode(['success' => false, 'message' => 'kode_produk wajib diisi']);
    exit;
}

// Type casting
$id_kategori = (int)$_POST['id_kategori'];
$harga_aktif = (float)$_POST['harga_aktif'];
```

### 4. File Upload Validation
```php
// Allowed types
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
if (!in_array($file['type'], $allowedTypes)) {
    // Reject
}

// File size (1MB max)
if ($file['size'] > 1024 * 1024) {
    // Reject
}
```

### 5. Method Validation
```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
```

### 6. Preflight Handling (CORS)
```php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
```

---

## 📊 Database Schema Reference

### Tables Used by API

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `produk` | Products | id_produk, nama_produk, harga_aktif, gambar1-3 |
| `kategori` | Categories | id_kategori, nama_kategori, background_url |
| `admin` | Admin users | id_admin, username, password, session_token |
| `banner` | Homepage banners | id_banner, judul, deskripsi, url_gambar |
| `distro` | Store info | id_distro, nama_distro, slogan, contacts |
| `video` | Promotional videos | id_video, judul, url_video |

### Relationships
```
produk.id_kategori → kategori.id_kategori
produk.id_admin → admin.id_admin
```

---

## 🛠️ Error Handling

### HTTP Status Codes

| Code | Usage |
|------|-------|
| 200 | Success |
| 204 | No Content (OPTIONS preflight) |
| 400 | Bad Request (validation failed) |
| 401 | Unauthorized (invalid/missing token) |
| 404 | Not Found |
| 405 | Method Not Allowed |
| 500 | Internal Server Error |

### Error Response Pattern
```json
{
  "success": false,
  "message": "Error description here"
}
```

### Try-Catch Pattern
```php
try {
    // Database operations
} catch (Exception $e) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}
```

---

## 📝 Best Practices

### 1. Always Close Connections
```php
$stmt->close();
$conn->close();
```

### 2. Use `JSON_PRETTY_PRINT` for Debugging
```php
echo json_encode($response, JSON_PRETTY_PRINT);
```

### 3. Output Buffering for Clean JSON
```php
ob_start();
// ... code ...
ob_clean();
echo json_encode($response);
ob_end_flush();
```

### 4. Consistent Naming
- Files: `list.php`, `new.php`, `update.php`, `delete.php`
- Variables: `$response`, `$data`, `$stmt`, `$conn`
- Response keys: `success`, `data`, `message`

### 5. Timezone Setting
```php
// In koneksi.php
date_default_timezone_set("Asia/Makassar");
```

---

## 🔧 Testing API Endpoints

### Using cURL

**List Products:**
```bash
curl -X GET http://localhost/api/produk/list.php
```

**Login:**
```bash
curl -X POST http://localhost/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password123"}'
```

**Create Product (with token):**
```bash
curl -X POST http://localhost/api/produk/new.php \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"kode_produk":"TEST001","nama_produk":"Test Product",...}'
```

**Search Products:**
```bash
curl -X GET "http://localhost/api/produk/search.php?q=tshirt"
```

---

## 📈 Performance Considerations

1. **Database Indexes** - Ensure indexes on frequently queried fields
2. **Prepared Statements** - Reusable and cached by MySQL
3. **LIMIT clauses** - Paginate large result sets
4. **Image Optimization** - Max 1MB per image
5. **Token Expiry** - 24-hour sessions reduce DB lookups

---

**Last Updated:** March 2, 2026  
**API Version:** 1.0
