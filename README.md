# AfyaLinks - Modern Healthcare Management System

AfyaLinks is a comprehensive digital healthcare platform designed to bridge the gap between patients and healthcare providers. Built with Laravel and modern web technologies, it streamlines the process of booking appointments, managing patient records, and handling payments.

## 🚀 Features

### For Patients
- **Easy Appointment Booking**: Browse doctors by specialization and book appointments seamlessly.
- **Real-time Availability**: View doctor schedules in real-time.
- **Payment Integration**: Secure payment processing for consultation fees (Mobile Money, Card, etc.).
- **Medical History**: Track past appointments and view doctor's notes.
- **Notifications**: Automated email confirmations and reminders.

### For Doctors
- **Schedule Management**: Set availability and manage working hours.
- **Patient Records**: Access patient history and add consultation notes.
- **Appointment Dashboard**: View upcoming appointments and manage cancellations.
- **Earnings Tracking**: Monitor consultation fees and payments.

### For Administrators
- **User Management**: Manage doctors, patients, and health workers.
- **System Monitoring**: Oversee platform activity and ensuring smooth operations.

## 🛠 Technology Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Database**: MySQL / SQLite
- **Frontend**: Blade Templates with Tailwind CSS
- **Authentication**: Laravel Breeze / Jetstream
- **Payments**: Integrated Payment Gateway

## 📦 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/anandaananda2/afyalinks.git
   cd afyalinks
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   Configure your database settings in `.env`, then run:
   ```bash
   php artisan migrate --seed
   ```

5. **Run the Application**
   ```bash
   php artisan serve
   ```

## 🔒 Security

If you discover any security related issues, please email the administrator instead of using the issue tracker.

## 📄 License

This project is proprietary software. All rights reserved.
