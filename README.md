# Static CMS

## Overview
Static CMS is a content management system developed in PHP, specifically designed for static websites. It provides a simple and efficient way to manage content, menus, and media for your static web pages.

## Features
- **Dynamic Content:** Easily manage and update content for your static pages.
- **Media Management:** Organize and handle media files for your website.
- **SEO Optimization:** Implement SEO-friendly practices with meta tags and page-specific settings.

## Technologies Used
- PHP
- HTML
- CSS
- JavaScript

## Directory and file Structure

```
root
├── contents
│   ├── data
│   ├── media
│   └── themes
├── manage
│   ├── assets
│   └── view
├── src
│   ├── functions
│   │   ├── admin-function.php
│   │   ├── admin-metabox.php
│   │   ├── function-third.php
│   │   ├── hook.php
│   │   ├── seo.php
│   │   └── template.php
│   ├── manage
│   │   ├── Controllers
│   │   │   ├── CartController.php
│   │   │   ├── ContactsController.php
│   │   │   ├── Controller.php
│   │   │   ├── FooterController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ImagebrowseController.php
│   │   │   ├── LoginController.php
│   │   │   ├── LogoutController.php
│   │   │   ├── MediaController.php
│   │   │   ├── MenuController.php
│   │   │   ├── PagesController.php
│   │   │   ├── SettingsController.php
│   │   │   ├── SitemapController.php
│   │   │   ├── SlidersController.php
│   │   │   ├── StaticController.php
│   │   │   ├── SysupdateController.php
│   │   │   ├── TaxonomyController.php
│   │   │   └── ThemesController.php
│   │   └── Model
│   │       ├── Media.php
│   │       ├── MediaCategory.php
│   │       ├── Menu.php
│   │       ├── Model.php
│   │       ├── Page.php
│   │       ├── Settings.php
│   │       ├── Slider.php
│   │       ├── StaticPage.php
│   │       └── Taxonomy.php
│   ├── shortcode
│   │   ├── LinkShortcode.php
│   │   ├── RandkeyShortcode.php
│   │   ├── RandphraseShortcode.php
│   │   ├── SitemapShortcode.php
│   │   ├── SliderShortcode.php
│   │   ├── StaticShortcode.php
│   │   ├── WriteusShortcode.php
│   │   ├── BoldTagCode.php
│   │   ├── ColorTagCode.php
│   │   ├── ContactShortcode.php
│   │   ├── ExlinkShortcode.php
│   │   ├── ExpandTagCode.php
│   │   ├── GridTagCode.php
│   │   ├── ImageShortcode.php
│   │   └── InnerLinkBuilder.php
│   └── Utilities
│       ├── SitemapGenerator.php
│       ├── ApiRequest.php
│       ├── BuiltIn.php
│       ├── Cart.php
│       ├── CsvFileManager.php
│       ├── FrontendControll.php
│       ├── MetaBox.php
│       ├── MetaBoxs.php
│       └── Singleton.php
├── .env
├── .htaccess
└── index.php

```



## Getting Started
Follow the steps below to set up and run the Static CMS:

### Prerequisites
- PHP installed on your server

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/AponAhmed/Static-cms.git
