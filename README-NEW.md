# Car Management System 🚗

A simple yet complete web application built with PHP and MySQL for managing cars, brands, and categories. Features a full authentication system and CRUD operations.

## 🌟 Features

- **Authentication System**
  - User registration and login
  - Password hashing with `password_hash()` and `password_verify()`
  - Session-based authentication
  - Protected routes

- **Car Management**
  - Add, edit, view, and delete cars
  - Each user can only manage their own cars
  - Track car details: brand, model, year, plate number, color, mileage, price

- **Brand & Category Management**
  - Manage car brands
  - Manage car categories with descriptions
  - Prevent deletion if referenced by cars

- **Dashboard**
  - Statistics overview (total cars, value, brands, categories)
  - Recent cars list
  - Quick action links

- **Security Features**
  - PDO prepared statements (SQL injection prevention)
  - Input sanitization
  - Session protection
  - Password hashing
  - User ownership verification

## 📁 Project Structure

```
/
├── auth/
│   ├── login.php          # Login page
│   ├── register.php       # Registration page
│   └── logout.php         # Logout functionality
├── cars/
│   ├── index.php          # List user's cars
│   ├── create.php         # Add new car
│   ├── edit.php           # Edit car
│   └── delete.php         # Delete car
├── brands/
│   ├── index.php          # List brands
│   ├── create.php         # Add brand
│   ├── edit.php           # Edit brand
│   └── delete.php         # Delete brand
├── categories/
│   ├── index.php          # List categories
│   ├── create.php         # Add category
│   ├── edit.php           # Edit category
│   └── delete.php         # Delete category
├── config/
│   ├── database.php       # Database connection (PDO)
│   ├── auth.php           # Authentication helpers
│   └── functions.php      # Utility functions
├── layout/
│   ├── header.php         # HTML header
│   ├── navbar.php         # Navigation bar
│   └── footer.php         # HTML footer
├── database/
│   └── init.sql           # Database schema and sample data
├── index.php              # Dashboard
├── setup.php              # Database setup script
└── docker-compose.yml     # Docker configuration
```

## 🗄️ Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email
- `password` - Hashed password
- `created_at` - Timestamp

### Brands Table
- `id` - Primary key
- `name` - Brand name (unique)

### Categories Table
- `id` - Primary key
- `name` - Category name (unique)
- `description` - Optional description

### Cars Table
- `id` - Primary key
- `user_id` - Foreign key to Users
- `brand_id` - Foreign key to Brands
- `category_id` - Foreign key to Categories
- `model` - Car model
- `year` - Manufacturing year
- `plate_number` - License plate
- `color` - Car color
- `mileage` - Mileage in km
- `price` - Price in USD
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## 🚀 Installation

### Prerequisites
- Docker and Docker Compose

### Setup Steps

1. **Clone or navigate to the project directory:**
   ```bash
   cd php-docker-simple-crud
   ```

2. **Start Docker containers:**
   ```bash
   docker-compose up -d
   ```

3. **Wait for containers to be ready** (about 30 seconds)

4. **Initialize the database:**
   ```bash
   docker exec -it php_apache_container php /var/www/html/setup.php
   ```

5. **Access the application:**
   - Main app: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

### Demo Account

After running the setup, you can login with:
- **Email:** demo@example.com
- **Password:** demo123

Or create a new account via the registration page.

## 🔧 Services

The Docker setup includes:

- **PHP 8.x with Apache** - Port 8080
- **MySQL 8.0** - Port 3306
- **phpMyAdmin** - Port 8081

### Default Database Credentials

- Host: `db` (from container) or `localhost` (from host)
- Database: `crud_php`
- User: `root`
- Password: `root`

## 💻 Usage

### First Time Setup

1. Start the application with Docker
2. Run the setup script to create tables
3. Register a new account or use the demo account
4. Start adding brands, categories, and cars!

### Adding Data

1. **Add Brands:** Navigate to Brands → Add New Brand
2. **Add Categories:** Navigate to Categories → Add New Category
3. **Add Cars:** Navigate to Cars → Add New Car

### Managing Cars

- View all your cars in the Cars section
- Edit car details by clicking the Edit button
- Delete cars you no longer need
- Only you can see and manage your own cars

## 🔒 Security Features

- **Password Security:** All passwords are hashed using PHP's `password_hash()`
- **SQL Injection Prevention:** All database queries use PDO prepared statements
- **Input Sanitization:** All user inputs are sanitized
- **Session Management:** Secure session handling for authentication
- **Access Control:** Users can only access and modify their own cars
- **XSS Prevention:** Output is escaped using `htmlspecialchars()`

## 🎨 UI/UX

- Built with **Bootstrap 5** for responsive design
- **Bootstrap Icons** for visual elements
- Flash messages for user feedback
- Clean and intuitive interface
- Mobile-friendly design

## 📝 Technologies Used

- **PHP 8.x** - Backend language
- **MySQL 8.0** - Database
- **PDO** - Database abstraction
- **Bootstrap 5** - Frontend framework
- **Bootstrap Icons** - Icon library
- **Docker** - Containerization
- **Apache** - Web server

## 🐛 Troubleshooting

### Container Issues

If containers fail to start:
```bash
docker-compose down
docker-compose up -d --build
```

### Database Connection Issues

Ensure the database container is running:
```bash
docker ps
```

You should see `mysql_container` in the list.

### Permission Issues

If you encounter permission issues:
```bash
chmod +x setup.php
```

## 📚 API Reference

This application doesn't expose a REST API, but you can extend it by:
1. Creating an `/api` folder
2. Adding endpoint files
3. Implementing JSON responses
4. Adding authentication tokens

## 🤝 Contributing

Feel free to fork this project and submit pull requests for:
- Bug fixes
- New features
- Documentation improvements
- UI/UX enhancements

## 📄 License

This project is open source and available under the MIT License.

## 👨‍💻 Author

Created as a demonstration of PHP CRUD operations with authentication.

## 🎯 Future Enhancements

Potential improvements:
- [ ] Car image uploads
- [ ] Advanced search and filtering
- [ ] Export data to CSV/PDF
- [ ] User profile management
- [ ] Email verification
- [ ] Password reset functionality
- [ ] Car maintenance tracking
- [ ] Multi-language support
- [ ] Dark mode
- [ ] REST API implementation

---

Made with ❤️ using PHP and MySQL
