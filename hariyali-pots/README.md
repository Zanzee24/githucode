# Hariyali Pots 🌱

A full-stack plant nursery eCommerce application using Core PHP (OOP), MySQL, Bootstrap 5, jQuery AJAX.

## Features
- User panel: home, live search, product listing/filter/sort/pagination, product detail + reviews/ratings, cart, wishlist, checkout, COD orders, tracking, invoice download.
- Admin panel: dashboard, categories, products, orders, users (block/unblock), reviews moderation.
- Security: prepared statements, bcrypt password hashing, session-based auth, simple CSRF helper for review form.

## XAMPP Setup (Step-by-step)
1. Copy `hariyali-pots` folder into `xampp/htdocs/`.
2. Start Apache + MySQL from XAMPP Control Panel.
3. Open phpMyAdmin and create/import database using `hariyali-pots/database.sql`.
4. Confirm DB credentials in `config/db.php` (default: root / empty password).
5. Visit:
   - User app: `http://localhost/hariyali-pots/index.php`
   - Admin: `http://localhost/hariyali-pots/admin/login.php`
6. Admin default credentials:
   - Email: `admin@hariyali.com`
   - Password: `admin123`

## Invoice Library
- For PDF invoices, put the official FPDF library file at:
  `hariyali-pots/vendor/fpdf/fpdf.php`
- Download from: http://www.fpdf.org/

## Notes
- Product image upload can be extended by switching image URL input to file upload + `move_uploaded_file`.
- `cart` table exists per requested schema, while runtime cart is session-based for faster UX.
