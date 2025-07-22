# Shopify Product App (Laravel 10+)

This Laravel-based application integrates with your Shopify store using the Admin API to fetch product and warehouse data, store it locally in a MySQL database, and render it beautifully using Blade templates. It supports metafield parsing and is built for speed, simplicity, and extensibility.

---

## 🚀 Features

### 📦 Product Management

* Fetch product data directly from the Shopify Admin API
* Save product details to a local MySQL database
* Display data on the frontend with Blade UI
* Display includes:

  * Product Title
  * Price
  * Image
  * Metafields (e.g., Sleeve-length-type, Color-pattern, Age-group)

### 📍 Warehouse Locations

* Fetch warehouse locations from Shopify Admin API
* Store locations in a local database table
* Display them using Blade views under the Locations page

### 🖥️ User Interface

* Sidebar navigation for smooth transitions
* Dashboard separated from inner pages
* Responsive and stylish UI using Bootstrap and custom Blade components

---

## 🛠️ Setup Instructions

### Step 1: Clone the Repository

```bash
git clone https://github.com/Karthikar123/shopify-product-app.git
cd shopify-product-app
```

### Step 2: Install Dependencies

```bash
composer install
npm install && npm run dev
```

### Step 3: Environment Configuration

```bash
cp .env.example .env
```

Update your `.env` file with the following:

```env
APP_NAME=ShopifyProductApp
APP_URL=http://localhost:8000

DB_DATABASE=shopify
DB_USERNAME=root
DB_PASSWORD=

SHOPIFY_STORE_DOMAIN=your-store.myshopify.com
SHOPIFY_ACCESS_TOKEN=your-shopify-access-token
```

### Step 4: Generate App Key

```bash
php artisan key:generate
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

### Step 6: Serve the Application

```bash
php artisan serve
```

Visit: [http://localhost:8000/products](http://localhost:8000/products)

---

## 📁 Sample Product Data Structure

```json
{
  "id": 7866333331510,
  "title": "Casual Blazer",
  "price": "1999",
  "image": "https://cdn.shopify.com/s/files/...jpg",
  "metafields": {
    "Sleeve-length-type": "Short",
    "Color-pattern": "Beige",
    "Age-group": "Adults"
  }
}
```

---

## ⚙️ Requirements

* PHP 8.1+
* Laravel 10+
* Composer
* Node.js & NPM
* MySQL
* Shopify Store + Admin API Access Token

---

## 👩‍💻 Author

**Karthika R**
Developer @ Emvigotech
Email: [karthika.r@emvigotech.com](mailto:karthika.r@emvigotech.com)
GitHub: [github.com/Karthikar123](https://github.com/Karthikar123)

---

## 🌐 GitHub Repository

**URL**: [https://github.com/Karthikar123/shopify-product-app](https://github.com/Karthikar123/shopify-product-app)

**Languages Used:**

* Blade: 54.8%
* PHP: 44.8%
* Other: 0.4%


