# E-Commerce Platform – Laravel Full-Stack Project

A robust full-stack **e-commerce web application** built with Laravel. This platform enables users to browse and order products, while incorporating a multi-role system that supports content management and approval workflows.

## 🌐 Features

- 🛒 **Customers (Users)** can:
  - Browse and search for products
  - Place orders
  - View upcoming events or promotions

- 🏢 **IT/Commercial Users** can:
  - Add and manage products
  - Create events and promotions

- 🛡️ **Admins** can:
  - Approve or reject products and events before they appear on the site
  - Manage users and monitor platform activity

## 🛠️ Tech Stack

- **Laravel** (PHP Framework)
- **Blade** templating engine
- **MySQL** for database
- **PHP 8+**
- **Bootstrap / Tailwind CSS** (depending on your UI)

## 🧑‍🤝‍🧑 Roles & Permissions

| Role          | Description                                                                 |
|---------------|-----------------------------------------------------------------------------|
| **User**      | Default user role. Can order products and view approved events.             |
| **IT/Commercial** | Can create products and events but needs admin approval for publishing. |
| **Admin**     | Full control: can approve/reject items and manage user accounts.            |

## 📸 Screenshots

> _(Coming Soon...)_

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.0
- Composer
- MySQL
- Node.js & npm

### Installation

1. Clone the repository:

```bash
git clone https://github.com/faresbnm/E-commerce.git
cd E-commerce
composer install
cp .env.example .env
#set database in env file
php artisan key:generate
php artisan serve
