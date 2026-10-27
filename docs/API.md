# Foxal Haute Parfumerie — REST API Documentation

**API Base URL**: `http://localhost:8000/api`  
**Current Version**: `1.0`  
**Format**: `application/json`  
**Authentication**: Laravel Sanctum (Bearer Token / SPA Stateful Cookie)

---

## 1. Security & Architecture Overview

### 1.1 Encryption (AES-256-CBC)
Sensitive Personally Identifiable Information (PII) is encrypted at the Eloquent model layer using Laravel's built-in `encrypted` and `encrypted:array` cast:
- **`users.phone`**: Encrypted at rest in MySQL (`text` column).
- **`addresses.full_name`**, **`addresses.phone`**, **`addresses.line1`**, **`addresses.line2`**: Encrypted at rest.
- **`orders.address_snapshot`**: Encrypted JSON snapshot of delivery address at checkout time.
- Direct database inspection displays ciphertext (e.g., base64 payload `eyJpdiI6...`), while Eloquent model accessors automatically decrypt to plaintext.

### 1.2 Password Hashing
All passwords are encrypted using `Illuminate\Support\Facades\Hash::make()` with Bcrypt/Argon2. Plaintext passwords are never stored or recoverable.

### 1.3 Rate Limiting
- **Auth Endpoints** (`/api/auth/login`, `/register`, `/forgot-password`): `10 requests/minute` per IP address.
- **General API Endpoints**: `60 requests/minute` per user/IP.

### 1.4 Security Headers Middleware
All responses include OWASP-recommended HTTP security headers:
- `X-Frame-Options: SAMEORIGIN`
- `X-Content-Type-Options: nosniff`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: camera=(), microphone=(), geolocation=()`

### 1.5 Centralized JSON Exception Format
All API error responses follow a uniform JSON structure:
```json
{
  "success": false,
  "message": "Error description",
  "data": null,
  "errors": {
    "field": ["Validation message"]
  }
}
```

---

## 2. Seeded Test Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@foxal.com` | `Admin@1234` |
| **Customer** | `customer@foxal.com` | `Customer@1234` |

---

## 3. Authentication Headers

Include the Sanctum token in the `Authorization` header for protected endpoints:
```http
Authorization: Bearer <your_token_here>
Accept: application/json
Content-Type: application/json
```

---

## 4. API Endpoints

### 4.1 Authentication (`/api/auth`)

#### `POST /api/auth/register`
Create a new customer account.

**Request Body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "phone": "9876543210",
  "password": "Password@123",
  "password_confirmation": "Password@123"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Registration successful.",
  "data": {
    "user": {
      "id": 3,
      "name": "Jane Doe",
      "email": "jane@example.com",
      "phone": "9876543210",
      "role": "customer",
      "is_active": true
    },
    "token": "1|e4g2X..."
  }
}
```

---

#### `POST /api/auth/login`
Authenticate and obtain a personal access token.

**Request Body:**
```json
{
  "email": "admin@foxal.com",
  "password": "Admin@1234"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Login successful.",
  "data": {
    "user": {
      "id": 1,
      "name": "Foxal Admin",
      "email": "admin@foxal.com",
      "phone": "9876543210",
      "role": "admin",
      "is_active": true,
      "last_login_at": "2026-10-27T00:09:42.000000Z"
    },
    "token": "2|k9f1Z..."
  }
}
```

---

#### `GET|POST /api/auth/auto-login`
Instantly logs in as the default Admin and returns user profile and access token (ideal for seamless local dev & testing without entering credentials).

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Default Admin logged in successfully.",
  "data": {
    "user": {
      "id": 1,
      "name": "Foxal Admin",
      "email": "admin@foxal.com",
      "phone": "9876543210",
      "role": "admin",
      "is_active": true
    },
    "token": "2|4VME1SXg22iUYMdp..."
  }
}
```

---

#### `GET /api/auth/me`
*Auth: `auth:sanctum`*  
Retrieve currently authenticated user profile.

---

#### `POST /api/auth/logout`
*Auth: `auth:sanctum`*  
Revoke the current authentication token.

---

#### `POST /api/auth/change-password`
*Auth: `auth:sanctum`*  

**Request Body:**
```json
{
  "current_password": "OldPassword123!",
  "new_password": "NewPassword123!",
  "new_password_confirmation": "NewPassword123!"
}
```

---

#### `POST /api/auth/forgot-password`
Request password reset link.

---

#### `POST /api/auth/reset-password`
Reset password with token.

---

### 4.2 Public Store Endpoints

#### `GET /api/products`
List active products with extensive filtering and pagination.

**Query Parameters:**
- `q`: Search keyword across name, description, brand, fragrance
- `category`: Category slug (e.g., `extrait-de-parfum`)
- `category_id`: Category ID
- `fragrance`: Scent family filter (e.g., `Woody`)
- `min_price`: Minimum price (INR)
- `max_price`: Maximum price (INR)
- `featured`: `true` or `false`
- `sort`: `latest`, `price_asc`, `price_desc`, `rating`, `name_asc`
- `per_page`: Number of results (default `12`, max `50`)
- `page`: Page number

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Products fetched successfully.",
  "data": [
    {
      "id": 1,
      "name": "Nuit Sombre",
      "slug": "nuit-sombre",
      "category_id": 1,
      "fragrance": "Woody Amber & Smoked Rose | Notes: Incense, Turkish Rose, Agarwood, Amber Noir",
      "brand": "Foxal Paris",
      "mrp": "5999.00",
      "price": "4499.00",
      "stock": 100,
      "sku": "FX-NS-001",
      "images": [
        "https://images.unsplash.com/photo-1541643600914-78b084683601?w=800"
      ],
      "is_active": true,
      "is_featured": true,
      "avg_rating": "4.90",
      "category": {
        "id": 1,
        "name": "Extrait de Parfum",
        "slug": "extrait-de-parfum"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 12,
    "total": 10
  }
}
```

---

#### `GET /api/products/{slug}`
Get full product details with active variants and approved reviews.

---

#### `GET /api/products/{slug}/reviews`
List approved reviews for a product (paginated).

---

#### `POST /api/products/{slug}/reviews`
*Auth: `auth:sanctum`*  
Submit a customer review (1 review per product per user; requires admin approval).

**Request Body:**
```json
{
  "rating": 5,
  "title": "Extraordinary longevity and aroma",
  "body": "This scent lasts through an entire workday and remains noticeable the next morning."
}
```

---

#### `GET /api/categories`
List active categories with product counts.

---

#### `GET /api/categories/{slug}`
Get category details and its products.

---

#### `POST /api/contact`
Submit customer contact message.

**Request Body:**
```json
{
  "name": "Rohit Verma",
  "email": "rohit@example.com",
  "subject": "Bespoke perfume consultation",
  "message": "I would like to enquire about custom formulation services."
}
```

---

### 4.3 Customer Endpoints (`auth:sanctum`)

#### `GET /api/cart`
Retrieve the current authenticated user's cart.

---

#### `POST /api/cart/items`
Add product to cart with strict real-time stock validation.

**Request Body:**
```json
{
  "product_id": 1,
  "quantity": 2
}
```

---

#### `PUT /api/cart/items/{id}`
Update item quantity.

**Request Body:**
```json
{
  "quantity": 3
}
```

---

#### `DELETE /api/cart/items/{id}`
Remove item from cart.

---

#### `DELETE /api/cart`
Clear all items in user's cart.

---

#### `POST /api/cart/merge`
Merge guest cart items into user's cart upon login.

**Request Body:**
```json
{
  "items": [
    { "product_id": 1, "quantity": 1 },
    { "product_id": 3, "quantity": 2 }
  ]
}
```

---

#### `GET /api/wishlist`
List customer wishlist items.

---

#### `POST /api/wishlist/{productId}`
Toggle product in wishlist (adds if absent, removes if present).

---

#### `GET /api/addresses`
List saved addresses with decrypted values.

---

#### `POST /api/addresses`
Create a new delivery address (fields encrypted in database via AES-256).

**Request Body:**
```json
{
  "full_name": "Aarav Sharma",
  "phone": "9812345678",
  "line1": "Flat 402, Royale Crest",
  "line2": "Bandra West",
  "city": "Mumbai",
  "state": "Maharashtra",
  "pincode": "400050",
  "is_default": true
}
```

---

#### `POST /api/coupons/validate`
Validate promo code against a cart subtotal.

**Request Body:**
```json
{
  "code": "FOXAL20",
  "subtotal": 3000.00
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Coupon applied successfully!",
  "data": {
    "id": 2,
    "code": "FOXAL20",
    "type": "percent",
    "value": 20,
    "discount": 600,
    "new_subtotal": 2400
  }
}
```

---

#### `POST /api/orders`
Create an order inside `DB::transaction` with `lockForUpdate()` on product stock.

**Request Body:**
```json
{
  "address_id": 1,
  "payment_method": "cod",
  "coupon_code": "WELCOME10",
  "notes": "Please leave at reception."
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Order placed successfully!",
  "data": {
    "order_number": "FX-20261027-A9K3D",
    "user_id": 2,
    "status": "PENDING",
    "payment_status": "PENDING",
    "payment_method": "cod",
    "subtotal": "4499.00",
    "discount": "449.90",
    "shipping": "0.00",
    "gst_amount": "617.66",
    "total": "4049.10",
    "items": [
      {
        "product_id": 1,
        "product_name": "Nuit Sombre",
        "price": "4499.00",
        "quantity": 1,
        "subtotal": "4499.00"
      }
    ]
  }
}
```

---

#### `GET /api/orders`
List past customer orders (paginated).

---

#### `GET /api/orders/{orderNumber}`
Get full order details with decrypted address snapshot and items.

---

#### `POST /api/orders/{orderNumber}/cancel`
Cancel an order before shipment. Automatically restores product inventory inside a database transaction.

---

#### `GET /api/orders/{orderNumber}/invoice`
Download generated Tax Invoice PDF via DomPDF.

---

### 4.4 Admin Endpoints (`/api/admin/*`, `auth:sanctum` + `admin` middleware)

#### `GET /api/admin/dashboard`
Returns high-level business metrics and 30-day analytics.

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "total_revenue": 142800.00,
    "revenue_today": 8998.00,
    "orders_today": 2,
    "new_users": 15,
    "total_users": 42,
    "low_stock_products": 0,
    "chart_last_30_days": [
      { "date": "2026-09-28", "orders": 1, "revenue": 4499.00 },
      { "date": "2026-10-26", "orders": 2, "revenue": 8998.00 }
    ]
  }
}
```

---

#### `GET /api/admin/products`
List all products including soft-deleted ones with filters.

---

#### `POST /api/admin/products`
Create product with optional multi-image Cloudinary upload.
- Multipart/form-data supported for `image_files[]` (JPEG, PNG, WebP up to 5MB).
- Automatically writes entry to `audit_logs`.

---

#### `PUT /api/admin/products/{id}`
Update product attributes, images, and prices. Writes to `audit_logs`.

---

#### `DELETE /api/admin/products/{id}`
Soft-delete product.

---

#### `POST /api/admin/products/{id}/restore`
Restore soft-deleted product.

---

#### `POST /api/admin/products/{id}/toggle-active`
Toggle product visibility in customer catalog.

---

#### `POST /api/admin/products/{id}/toggle-featured`
Toggle featured carousel status.

---

#### `POST /api/admin/products/{id}/stock`
Directly set product inventory count.

---

#### `POST /api/admin/products/bulk-price-update`
Bulk update pricing across multiple products.

**Request Body:**
```json
{
  "product_ids": [1, 2, 3],
  "type": "percentage",
  "action": "increase",
  "value": 10
}
```

---

#### `GET /api/admin/users`
List all users with search, role filter, and decrypted phone number.

---

#### `GET /api/admin/users/{id}`
View user profile with decrypted address book and order history.

---

#### `POST /api/admin/users/{id}/toggle-block`
Block/unblock a user account. Blocked users cannot log in.

---

#### `POST /api/admin/users/{id}/role`
Change user role between `customer` and `admin`.

---

#### `GET /api/admin/users/export`
Streamed CSV download of all registered users.

---

#### `GET /api/v1/admin/orders`
List orders with status, date range, and search filters.

---

#### `PUT /api/v1/admin/orders/{id}/status`
Update order status (`PENDING`, `PAID`, `PACKED`, `SHIPPED`, `DELIVERED`, `CANCELLED`, `REFUNDED`) and set tracking number. Automatically queues `OrderStatusUpdatedMail`.

---

#### `POST /api/v1/admin/orders/{id}/refund`
Mark order as refunded and optionally restock products.

---

#### `GET /api/v1/admin/orders/export`
Streamed CSV download of orders ledger.

---

#### `GET /api/v1/admin/reviews`
List all reviews (moderation queue).

---

#### `POST /api/v1/admin/reviews/{id}/approve`
Approve review and recalculate product average star rating.

---

#### `DELETE /api/v1/admin/reviews/{id}`
Delete inappropriate review and update average rating.

---

#### `GET /api/v1/admin/coupons` | `POST` | `PUT` | `DELETE`
Coupons CRUD.

---

#### `GET /api/v1/admin/contact-messages`
List contact messages. Mark read with `POST .../mark-read`.

---

#### `GET /api/v1/admin/settings` & `PUT /api/v1/admin/settings`
View and update store settings:
```json
{
  "shipping_charge": 99.00,
  "free_shipping_threshold": 999.00,
  "gst_rate": 18.00,
  "announcement_text": "Complimentary discovery atomizer on orders over ₹2,000."
}
```

---

#### `GET /api/v1/admin/audit-logs`
Audit log history tracking all administrative writes (actions, affected model, old values, new values, IP, timestamp).
