# 🎥 **Youtuber-GPA**

## 📖 **Project Overview**
The **Youtuber-GPA Project** is a web application designed to manage user interactions, including user registration, login, commenting, and rating videos. It integrates a backend database to store user information, video ratings, and comments, offering a seamless experience for both administrators and users.

---

## 🛠️ **Features**
1. **User Registration and Authentication:**
   - Secure user registration with password validation.
   - Login/logout functionalities to manage user sessions.

2. **Video Interaction:**
   - Users can star (rate) videos.
   - Comment sections are available for video discussions.

3. **Session Management:**
   - User sessions are maintained securely.
   - Logout functionality clears session data.

4. **Responsive Design:**
   - Clean and user-friendly registration and login pages.

---

## 💻 **Setup Guide (Using XAMPP on macOS)**
Follow these steps to set up the project using XAMPP on macOS:

### 1️⃣ **Install XAMPP**
- Download and install XAMPP from [Apache Friends](https://www.apachefriends.org/index.html).
- Open XAMPP and start **Apache** and **MySQL** services.

### 2️⃣ **Set Up the Database**
1. Open **phpMyAdmin** from XAMPP.
2. Create a new database, e.g., `youtuber_gpa`.
3. Import the SQL schema (if available) to set up necessary tables.

### 3️⃣ **Place Project Files**
- Copy all project files into the `htdocs` folder located in `/Applications/XAMPP/htdocs`.
- Ensure the directory looks like:
```
/htdocs/Youtuber-GPA/
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── register.php
    ├── handle_comment.php
    ├── star_video.php
    ├── welcome.php
    ├── register.html
```

### 4️⃣ **Configure Database Connection**
- Open `register.php` or `login.php` and ensure the database connection details are correctly set:
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "youtuber_gpa";
```

### 5️⃣ **Run the Project**
- Open your browser and go to:
```
http://localhost/Youtuber-GPA/index.php
```

---

## 🚀 **Usage Instructions**
1. **User Registration:** Visit `/register.html` to create an account.
2. **Login:** Use `/login.php` to log in.
3. **Interact with Videos:** Rate and comment on videos.
4. **Logout:** End your session securely via `/logout.php`.

---

## 📝 **Project File Descriptions**
- **index.php:** Main landing page.
- **login.php:** User login functionality.
- **logout.php:** Ends user sessions.
- **register.html:** User registration page.
- **register.php:** Backend script for handling registration.
- **handle_comment.php:** Handles user comments.
- **star_video.php:** Handles video ratings.
- **welcome.php:** Post-login welcome page.
