# Distro E-Commerce Platform

A modern, responsive e-commerce platform for distro/clothing stores built with PHP, MySQL, and TailwindCSS.

## 📁 Project Structure

```
htdocs/
├── admin/                  # Admin panel interface
│   ├── css/               # Admin stylesheets
│   ├── js/                # Admin JavaScript files
│   ├── includes/          # Reusable admin components
│   ├── admin.html         # Admin management page
│   ├── banner.html        # Banner management
│   ├── dashboard.html     # Admin dashboard
│   ├── distro.html        # Distro info management
│   ├── kategori.html      # Category management
│   ├── login.html         # Admin login page
│   ├── produk.html        # Product management
│   ├── sample.html        # Sample/template page
│   └── video.html         # Video management
│
├── api/                    # RESTful API endpoints
│   ├── auth/              # Authentication endpoints
│   │   ├── list.php       # List admins
│   │   ├── login.php      # Admin login
│   │   ├── new.php        # Create admin
│   │   └── validate_token.php
│   ├── banner/            # Banner management
│   │   ├── delete.php
│   │   ├── list.php
│   │   ├── new.php
│   │   └── update.php
│   ├── distro/            # Distro information
│   │   ├── list.php
│   │   └── update.php
│   ├── kategori/          # Product categories
│   ├── produk/            # Product management
│   │   ├── delete.php
│   │   ├── list.php
│   │   ├── merk.php
│   │   ├── new.php
│   │   ├── search.php
│   │   └── update.php
│   ├── video/             # Video management
│   └── product-details.php
│
├── config/                 # Configuration files
│   ├── api_helper.php     # API helper functions
│   ├── backup_db.php      # Database backup utility
│   └── koneksi.php        # Database connection
│
├── public/                 # Public-facing website
│   ├── components/        # Reusable components
│   ├── css/               # Public stylesheets
│   ├── images/            # Product/category images
│   ├── modal/             # Modal components
│   ├── script/            # Frontend JavaScript
│   ├── videos/            # Video assets
│   ├── .htaccess          # Apache configuration
│   ├── all-categories.php # All categories page
│   ├── all-itemproduct.php# Product listing page
│   ├── footer.php         # Footer component
│   ├── index.php          # Homepage
│   ├── navbar.php         # Navigation component
│   ├── product.php        # Product detail page
│   └── *.html             # Sample pages
│
└── backup/                 # Backup directory (excluded)
```

## 🚀 Features

### Public Storefront (`/public/`)
- **Responsive Design** - Built with TailwindCSS, mobile-first approach
- **Dark/Light Mode** - Toggle between themes
- **Product Catalog** - Browse products by category
- **Search Functionality** - Real-time product search
- **Shopping Cart** - Add to cart functionality
- **Hero Banner** - Dynamic promotional banner
- **New Arrivals** - Featured new products carousel
- **Shop by Category** - Category-based navigation
- **Best Sellers** - Popular products section
- **WhatsApp Integration** - Floating contact button

### Admin Panel (`/admin/`)
- **Dashboard** - Overview and statistics
- **Product Management** - CRUD operations for products
- **Category Management** - Manage product categories
- **Banner Management** - Control homepage banners
- **Distro Settings** - Update store information
- **Video Management** - Manage promotional videos
- **Authentication** - Secure admin login with token validation

### API Endpoints (`/api/`)
- **Authentication** - Login, token validation, admin management
- **Products** - List, create, update, delete, search products
- **Categories** - Category listing and management
- **Banners** - Banner CRUD operations
- **Distro Info** - Store information retrieval and updates
- **Videos** - Video content management

## 🛠️ Technology Stack

| Layer | Technology |
|-------|------------|
| **Frontend** | HTML5, TailwindCSS, JavaScript |
| **Backend** | PHP (Native) |
| **Database** | MySQL |
| **Styling** | TailwindCSS, Font Awesome, Material Symbols |
| **Fonts** | Google Fonts (Manrope) |
| **Icons** | Material Symbols Outlined |

## 📋 Database Configuration

Default database settings in `config/koneksi.php`:

```php
$host = 'localhost';
$username = 'matos'
$password = '1234'
$database = 'doni-distro'
```

### Database Tables
- `produk` - Products
- `kategori` - Product categories
- `admin` - Admin users
- `banner` - Homepage banners
- `distro` - Store information
- `video` - Promotional videos

## 🔧 Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Steps

1. **Clone/Copy Project**
   ```bash
   # Copy project to XAMPP htdocs directory
   # Path: C:\xampp\htdocs\
   ```

2. **Create Database**
   ```sql
   CREATE DATABASE `doni-distro`;
   ```

3. **Configure Database Connection**
   - Edit `config/koneksi.php`
   - Update database credentials if needed

4. **Import Database**
   - Import your SQL backup to the `doni-distro` database

5. **Set Permissions**
   - Ensure `/public/images/` and `/public/videos/` are writable

6. **Access the Application**
   - **Public Store**: `http://localhost/public/`
   - **Admin Panel**: `http://localhost/admin/login.html`

## 📝 API Usage

### Product List Endpoint
```
GET /api/produk/list.php
```

**Response:**
```json
{
  "success": true,
  "data": [...],
  "message": "Data produk berhasil diambil"
}
```

### Authentication Endpoint
```
POST /api/auth/login.php
```

**Request:**
```json
{
  "username": "admin",
  "password": "password"
}
```

## 🎨 Key Features Explained

### Dynamic Content Loading
The application uses `api_helper.php` to fetch data from API endpoints dynamically, allowing seamless integration between the frontend and backend.

### Responsive Design
- Mobile menu with slide-out navigation
- Touch-friendly carousels
- Adaptive grid layouts
- Custom scrollbars

### Product Features
- Multiple product images (gambar1, gambar2, gambar3)
- Stock management (in_stok, jumlah_stok)
- Pricing with discount (harga_aktif, harga_coret)
- Sales tracking (terjual)
- Category and brand association

### Security
- Token-based admin authentication
- Input sanitization with `htmlspecialchars()`
- SQL injection prevention with prepared statements
- CORS headers for API endpoints

## 📂 Ignored Files/Folders

The following are excluded from version control (see `.gitignore`):
- `/public/images/` - User-uploaded product images
- `/public/videos/` - User-uploaded video content
- `favicon.ico`
- `well-known.zip`
- `/backup/` - Database backups

## 🔐 Default Admin Access

Access the admin panel at `/admin/login.html`

> ⚠️ **Note**: Change default credentials after installation for security.

## 🌐 Timezone

Application uses **Asia/Makassar** timezone (configured in `koneksi.php`).

## 📄 File Conventions

| Prefix | Description |
|--------|-------------|
| `list.php` | Retrieve all records |
| `new.php` | Create new record |
| `update.php` | Update existing record |
| `delete.php` | Remove record |
| `search.php` | Search functionality |

## 🤝 Contributing

1. Follow existing code conventions
2. Use meaningful commit messages
3. Test changes in both public and admin interfaces
4. Update documentation as needed

## 📞 Support

For issues or questions, contact through the WhatsApp integration button available on the public storefront.

---

**Built with ❤️ for modern distro businesses**
