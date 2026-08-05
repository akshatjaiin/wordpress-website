# WordPress Website for Wasmer Edge Deployment

This repository contains a full **WordPress** application pre-configured for **One-Click Deployment on Wasmer Edge** as well as local execution.

---

## 🚀 One-Click Deploy to Wasmer Edge

### Option 1: Via Wasmer GitHub Integration (One-Click)
1. Go to your [Wasmer Console](https://wasmer.io/console) / Dashboard.
2. Click **Add App** or **Import from GitHub**.
3. Select your repository: `akshatjaiin/wordpress-website`.
4. Wasmer Edge will automatically detect `app.yaml` and deploy your WordPress site with a managed MySQL database and persistent `wp-content` volume!

### Option 2: Via Wasmer CLI
```bash
# 1. Install Wasmer CLI (if not already installed)
# https://docs.wasmer.io/install

# 2. Login to Wasmer
wasmer login

# 3. Deploy to Wasmer Edge
wasmer deploy
```

---

## 💻 Local Development

Run with local PHP server:
```bash
php -S localhost:8080 -t app
```

Or run using the Wasmer runtime:
```bash
wasmer run .
```
Access at [http://127.0.0.1:8080](http://127.0.0.1:8080).

---

## 🛒 WooCommerce & Product Search Testing

Once your WordPress site is live on Wasmer Edge:
1. Log in to the WordPress Admin dashboard (`/wp-admin`).
2. Go to **Plugins > Add New**, search for **WooCommerce**, click **Install Now** and **Activate**.
3. Complete the quick WooCommerce setup wizard to add sample products.
4. Test product search queries using the built-in WordPress / WooCommerce search bar (`/?s=query&post_type=product`) to evaluate search performance on Wasmer.

