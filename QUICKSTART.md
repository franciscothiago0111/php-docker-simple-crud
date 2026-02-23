# 🚀 QUICK START GUIDE - Car Management System

## Step-by-Step Setup (5 minutes)

### 1️⃣ Start Docker Containers
```bash
docker-compose up -d
```
**Wait:** 30-60 seconds for containers to fully start

### 2️⃣ Initialize Database
```bash
docker exec -it php_apache_container php /var/www/html/setup.php
```

### 3️⃣ Access the Application
Open your browser:
- **App:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8081

### 4️⃣ Login
Use the demo account:
- **Email:** demo@example.com
- **Password:** demo123

Or register a new account at: http://localhost:8080/auth/register.php

---

## ✅ What You Can Do

1. **Dashboard** - View statistics and recent cars
2. **Manage Cars** - Add, edit, delete your cars
3. **Manage Brands** - Toyota, Honda, Ford, etc.
4. **Manage Categories** - Sedan, SUV, Truck, etc.

---

## 🛠️ Common Commands

### Stop Containers
```bash
docker-compose down
```

### Restart Containers
```bash
docker-compose restart
```

### View Logs
```bash
docker-compose logs -f
```

### Access PHP Container
```bash
docker exec -it php_apache_container bash
```

### Access MySQL
```bash
docker exec -it mysql_container mysql -u root -proot crud_php
```

---

## 📊 Default Data Included

### Brands (10)
Toyota, Honda, Ford, Chevrolet, BMW, Mercedes-Benz, Volkswagen, Nissan, Audi, Hyundai

### Categories (8)
Sedan, SUV, Truck, Coupe, Hatchback, Convertible, Minivan, Electric

### Demo User
- Email: demo@example.com
- Password: demo123

---

## 🆘 Troubleshooting

### Database Connection Error
```bash
# Restart MySQL container
docker-compose restart db

# Wait 30 seconds, then run setup again
docker exec -it php_apache_container php /var/www/html/setup.php
```

### Port Already in Use
Edit `docker-compose.yml` and change ports:
```yaml
ports:
  - "8082:80"  # Change 8080 to 8082
```

### Reset Everything
```bash
docker-compose down -v
docker-compose up -d
# Wait 60 seconds
docker exec -it php_apache_container php /var/www/html/setup.php
```

---

## 🎯 Quick Test Flow

1. Login with demo account
2. Go to **Cars** → **Add New Car**
3. Fill in the form:
   - Brand: Toyota
   - Model: Camry
   - Category: Sedan
   - Year: 2024
   - Plate: ABC-1234
   - Color: Blue
   - Mileage: 1000
   - Price: 25000
4. Click **Save Car**
5. View your car in the dashboard!

---

## 📝 File Structure Summary

```
/auth          → Login, Register, Logout
/cars          → Car CRUD operations
/brands        → Brand management
/categories    → Category management
/config        → Database & auth configuration
/layout        → Reusable HTML components
/database      → SQL initialization
index.php      → Dashboard
setup.php      → Database setup script
```

---

## 🔐 Security Features

✅ Password hashing (bcrypt)  
✅ PDO prepared statements  
✅ Session protection  
✅ Input sanitization  
✅ XSS prevention  
✅ User ownership verification  

---

## 💡 Pro Tips

1. **Add brands first** before adding cars
2. **Add categories first** before adding cars
3. Each user can only see **their own cars**
4. **Cannot delete** brands/categories used by cars
5. Use **phpMyAdmin** to view all database tables

---

## 📧 Need Help?

Check the full README-NEW.md for detailed documentation.

---

**Ready to go! 🎉**
