# Lecturer Attendance System (Sistem Absensi Dosen)

A comprehensive lecturer attendance management system consisting of a mobile application for lecturers to mark their attendance, an admin panel for management, and a backend API for handling attendance data.

## Project Structure

The project is divided into three main components:

### 1. Mobile Application (`absensi dosen app/`)
- Built with Flutter
- Handles lecturer login and attendance marking
- Cross-platform support (Android, iOS)

### 2. Admin Panel (`absensi-dosen-admin/`)
- Built with Laravel
- Manages lecturers, schedules, and attendance records
- Provides administrative dashboard and reports

### 3. Backend API (`absensi-dosen-api/`)
- Built with PHP Native
- Handles attendance data processing using Redis Queue system:
  - Uses Redis for queue management
  - Implements a worker system for processing attendance records
  - Asynchronously processes attendance data to prevent data loss
  - Stores attendance with location data (latitude/longitude)
  - Real-time processing with timestamp tracking

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

### API Features
- Authentication endpoints
- Attendance processing
- Email notifications
- Data validation

## Technology Stack

- **Mobile App:** Flutter/Dart
- **Admin Panel:** Laravel/PHP
- **API:** PHP
- **Database:** MySQL
  
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
5. Run database migrations:
   ```bash
   php migrate.php
   ```


## System Requirements

### Mobile App Development
- Flutter SDK
- Android Studio / Xcode

### Backend Development
- PHP >= 7.4
- Composer
- MySQL
- Laravel CLI


