# BioData App - Enhanced Version

A modern, responsive bio data management system built with PHP, MySQL, and modern CSS. Users can register, login, manage their bio data profiles, and view others' profiles in a beautiful interface.

## Features

### 🎨 Modern UI/UX
- **Responsive Navbar** with user avatar/icon
- **Gradient backgrounds** and modern card designs
- **Smooth animations** and hover effects
- **Mobile-friendly** responsive design
- **Modal popups** for detailed bio views

### 👤 User Management
- **Secure Registration** with password validation
- **Login/Logout** functionality with session management
- **Profile avatars** with fallback initials
- **Dropdown menus** on user avatar hover

### 📊 Bio Data Management
- **Complete profile forms** with validation
- **Image upload** with security checks
- **Profile editing** capabilities
- **Secure deletion** with confirmations
- **View all profiles** in beautiful cards

### 🔐 Security Features
- **Password hashing** using PHP's password_hash()
- **SQL injection protection** using prepared statements
- **File upload validation** (type, size checks)
- **User authorization** (users can only edit/delete their own data)
- **Session management** with proper cleanup

## Installation & Setup

### Prerequisites
- **XAMPP/WAMP/LAMP** server
- **PHP 7.4+**
- **MySQL 5.7+**
- **Modern web browser**

### Step-by-Step Setup

1. **Clone/Download** the project to your web server directory
   ```
   htdocs/bio-data-app/  (for XAMPP)
   ```

2. **Start your server**
   - Start Apache and MySQL in XAMPP/WAMP

3. **Create the database**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Run the SQL queries from `database.sql` file

4. **Configure database connection**
   - Open `db.php`
   - Update database credentials if needed:
     ```php
     $host = "localhost";     // Usually localhost
     $user = "root";          // Default MySQL username
     $pass = "";              // Default MySQL password (empty)
     $dbname = "bio_data_app"; // Database name
     ```

5. **Set up file permissions**
   - Make sure `uploads/` folder is writable (755 or 777 permissions)

6. **Access the application**
   - Open your browser and go to `http://localhost/bio-data-app/`

## File Structure

```
├── css/
│   └── style.css           # Modern responsive styles
├── uploads/                # User uploaded images
├── db.php                  # Database connection & session
├── navbar.php              # Reusable navigation component
├── index.php               # Dashboard/Home page
├── login.php               # User login
├── register.php            # User registration
├── profile.php             # User profile management
├── save_bio.php           # Bio data save handler
├── get_bio_details.php    # Modal content handler
├── delete.php             # Bio data deletion
├── logout.php             # Session cleanup
├── database.sql           # Database setup queries
└── README.md              # This file
```

## Usage Guide

### For New Users
1. **Visit the homepage** - See all existing bio data
2. **Click user icon** in navbar → Select "Register"
3. **Fill registration form** with username, email, password
4. **Login** with your credentials
5. **Complete your profile** by filling bio data form
6. **Upload profile picture** (optional)

### For Existing Users
1. **Login** through navbar dropdown
2. **Access your profile** via navbar dropdown → "Profile"
3. **Edit your bio data** anytime
4. **View others' profiles** by clicking bio cards on homepage
5. **Delete your data** from profile page (if needed)

### Navigation Features
- **Hover over user icon** in navbar to see login/logout options
- **After login**: Avatar shows your profile picture or initial
- **Profile dropdown** provides quick access to profile and logout
- **Click any bio card** on homepage to view detailed modal

## Customization

### Styling
- All styles are in `css/style.css`
- Uses CSS Grid and Flexbox for responsive layouts
- Gradient backgrounds and modern card designs
- Easy to customize colors by changing CSS variables

### Database Schema
- `users` table: Basic user authentication
- `bio_data` table: Detailed profile information
- Foreign key relationship ensures data integrity

## Security Features

✅ **Password hashing** with PHP's `password_hash()`  
✅ **SQL injection protection** via prepared statements  
✅ **File upload validation** (type, size, security)  
✅ **User session management**  
✅ **Authorization checks** (users can only modify their own data)  
✅ **Input sanitization** and validation  
✅ **Error handling** with user-friendly messages  

## Browser Support

- ✅ Chrome 60+
- ✅ Firefox 60+
- ✅ Safari 12+
- ✅ Edge 79+
- ✅ Mobile browsers

## Troubleshooting

### Common Issues

**Database Connection Error**
- Check if MySQL is running
- Verify database credentials in `db.php`
- Ensure database exists and tables are created

**Image Upload Issues**
- Check `uploads/` folder permissions
- Verify file size limits in PHP configuration
- Ensure supported image formats (JPG, PNG, GIF)

**Session Issues**
- Clear browser cookies/cache
- Check if sessions are enabled in PHP configuration
- Verify session save path is writable

### Error Messages
The application provides user-friendly error messages for:
- Registration validation errors
- Login failures
- File upload issues
- Database connection problems

## Contributing

Feel free to fork this project and submit pull requests for improvements!

## License

This project is open source and available under the MIT License.
