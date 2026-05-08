# InvoiceX - Invoice Management System

A modern invoice management application built with Laravel 13 and Tailwind CSS.

## 🚀 Live Demo
[View Live App](https://your-app.railway.app) ← Add this after deployment

## ✨ Features
- Multi-user authentication with Laravel Breeze
- Client management (CRUD operations)
- Invoice creation with dynamic line items
- Invoice status tracking (Draft, Sent, Paid, Overdue)
- PDF export with professional formatting
- Real-time total calculation
- Responsive dashboard with statistics

## 🛠️ Tech Stack
- **Backend:** Laravel 13.5.0, PHP 8.4.6
- **Frontend:** Blade Templates, Tailwind CSS
- **Database:** MySQL
- **PDF Generation:** DomPDF
- **Deployment:** Railway

## 📦 Installation

```bash
git clone https://github.com/Ghl-ane/invoice-manager.git
cd invoice-manager
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## 🎯 Key Features Demo
- **Dashboard:** Real-time stats showing total clients, invoices, revenue, and pending amounts
- **Invoice Creation:** Dynamic line item addition with automatic subtotal calculation
- **PDF Export:** Professional invoice PDFs with client details and branding
- **Security:** Per-user data isolation — users only see their own data

## 📸 Screenshots
(Add 2-3 screenshots of dashboard, invoice creation, PDF)

## 👤 Author
**Ghlane Mohamed Habib**
- GitHub: [@Ghl-ane](https://github.com/Ghl-ane)