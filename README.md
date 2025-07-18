Shopify Product App (Laravel 10+)
This Laravel-based application fetches products from your Shopify store using the Admin API, saves them into a local MySQL database, and displays them beautifully using Blade templates. It supports metafield parsing and is designed to be simple, fast, and extensible.

Features
    • Fetch product data directly from Shopify Admin API
    • Store product details in your MySQL database
    • Display product information on a styled Blade view:
        ◦ Product Title
        ◦ Price
        ◦ Image
        ◦ Metafields 

Setup Instructions
1. Clone the Repository
git clone https://github.com/Karthikar123/shopify-product-app.git
cd shopify-product-app

2. Install Backend & Frontend Dependencies
composer install
npm install && npm run dev

3. Configure Environment
Create your environment config:
cp .env.example .env

Edit your .env file and fill in your database and Shopify API credentials:
APP_NAME=ShopifyProductApp
APP_URL=http://localhost:8000

DB_DATABASE=shopify
DB_USERNAME=root
DB_PASSWORD=

SHOPIFY_STORE_DOMAIN=your-store.myshopify.com
SHOPIFY_ACCESS_TOKEN=your-shopify-access-token

4. Generate Application Key
php artisan key:generate

5. Run Migrations
php artisan migrate

6. Serve the Application
php artisan serve

Then visit:
http://localhost:8000/products



Available Artisan Commands
Use Laravel Tinker to test the product import manually:
php artisan tinker
>>> app(App\Services\ShopifyProductService::class)->fetchAndStoreProducts()


Sample Data Structure
Each product saved includes:
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


Requirements
    • PHP 8.1+
    • Laravel 10+
    • Composer
    • Node.js & NPM
    • MySQL
    • Shopify Store + Admin API Access Token

Author
Karthika R
Developer @ Emvigotech
Email: karthika.r@emvigotech.com
GitHub: github.com/Karthikar123



