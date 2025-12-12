# ArmoredLaw WordPress Theme

Custom WordPress theme used for the **Armored Law** website.

This repository contains **only the theme code**:

- `wp-content/themes/armoredlaw/`

WordPress core, plugins, and uploads are not tracked here.

---

## 1. Required Plugins

The project relies on the following plugins:

### 1.1 Elementor (Free)

- Source: WordPress.org plugin repository  
- Purpose: Page builder (base plugin required by Pro)

### 1.2 Elementor Pro

- Source: https://my.elementor.com/  
- License / Account owner: `mkulish@codebridge.tech`  
- Note: Credentials are stored in the company password manager.

### 1.3 Advanced Custom Fields PRO

- Source: https://www.advancedcustomfields.com/  
- License / Account owner: `mkulish@codebridge.tech`  
- Note: Credentials are stored in the company password manager.

If any of the premium plugins are missing on a new environment, log into the corresponding account and download the latest version, or use the company’s plugin storage (if available).

---

## 2. Theme Location

Theme path inside WordPress:

wp-content/themes/armoredlaw

## 3. Front-end build (styles & scripts)

The theme uses a Node-based build pipeline for assets.

### 3.1 Install dependencies

cd wp-content/themes/armoredlaw

npm install

This installs all Node dependencies defined in package.json.

### 3.2 Development (watch)

For local development with automatic rebuild:

npm run watch

Watches source files (LESS/JS/etc.)

Rebuilds assets on change.

### 3.3 Production build

Before committing or deploying, run a clean production build:

npm run build

## 4. Git / Repository Notes

- Only the theme is tracked in git.

- WordPress core, plugins, uploads, and node_modules are intentionally ignored.

- To restore Node dependencies on any machine, run npm install in:
  wp-content/themes/armoredlaw