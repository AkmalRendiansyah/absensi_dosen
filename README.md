# Lecturer Attendance System (Sistem Absensi Dosen)

A comprehensive lecturer attendance management system consisting of a mobile application for lecturers to mark their attendance, an admin panel for management, and a backend API for handling attendance data.

## Project Structure

The project is divided into three main components:

### 1. Mobile Application (`absensi_dosen_app/`)

- Built with Flutter
- Handles lecturer login and attendance marking
- Cross-platform support (Android, iOS)

### 2. Admin Panel (`absensi-dosen-admin/`)

- Built with Laravel
- Manages lecturers, schedules, and attendance records
- Provides administrative dashboard and reports

### 3. Backend API (`absensi-dosen-api/`)

- Built with PHP
- Handles attendance data processing using Redis Queue system:
  - Uses Redis for queue management
  - Implements a worker system for processing attendance records
  - Asynchronously processes attendance data to prevent data loss
  - Stores attendance with location data (latitude/longitude)
  - Real-time processing with timestamp tracking
- Manages email notifications using PHPMailer and SendGrid

## Features

### Mobile App Features

- Lecturer authentication
- Attendance marking
- Dashboard view
- Schedule viewing

### Admin Panel Features

- User management
- Schedule management
- Attendance tracking
- Report generation
- Dashboard analytics

### API Features

- Authentication endpoints
- Attendance processing
- Email notifications
- Data validation

## Technology Stack

- **Mobile App:** Flutter/Dart
- **Admin Panel:** Laravel/PHP
- **API:** PHP
- **Queue System:** Redis
- **Email Services:** PHPMailer, SendGrid
- **Database:** MySQL (assumed based on Laravel configuration)

## Setup Instructions

### Mobile App Setup

1. Navigate to `absensi dosen app/` directory
2. Install Flutter dependencies:
   ```bash
   flutter pub get
   ```
3. Run the app:
   ```bash
   flutter run
   ```

### Admin Panel Setup

1. Navigate to `absensi-dosen-admin/` directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Copy `.env.example` to `.env` and configure your database
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run database migrations:
   ```bash
   php artisan migrate
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

### API Setup

1. Navigate to `absensi-dosen-api/` directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Configure your email settings in the appropriate configuration files
4. Ensure Redis server is installed and running
5. Start the attendance worker:
   ```bash
   php worker_absensi.php
   ```

## System Requirements

### Mobile App Development

- Flutter SDK
- Android Studio / Xcode
- iOS Simulator / Android Emulator

### Backend Development

- PHP >= 7.4
- Composer
- MySQL
- Laravel CLI
- Redis Server

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

Project Link: [https://github.com/yourusername/absensi_dosen](https://github.com/yourusername/absensi_dosen)

## Acknowledgments

- Flutter framework
- Laravel framework
- PHPMailer
- SendGrid
