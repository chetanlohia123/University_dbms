📂 University_dbms/
│── 📂 assets/             # Static assets (CSS, JS, images)
│   │── css/              
│   │   ├── global.css     # Global styles
│   │   ├── login.css      # Login page styles
│   │   ├── dashboard.css  # Dashboard styles
│   │   ├── admin.css      # Admin management styles
│   │── js/               
│   │   ├── scripts.js     # Optional JavaScript for UI
│   │── images/           # Logos, icons, and other images
│
│── 📂 config/             # Configuration & Database
│   │── db.php            # Database connection
│   │── session.php       # Session management
│   │── auth.php          # Authentication helper (checks role access)
│
│── 📂 includes/           # Common UI components
│   │── header.php        # Header (Navigation)
│   │── footer.php        # Footer
│   │── sidebar.php       # Sidebar (Admin Panel)
│
│── 📂 admin/             # Admin Panel
│   │── login.php         # Admin login page
│   │── dashboard.php     # Admin dashboard (University Overview)
│   │── department.php    # Manage departments (Add, View, Edit, Delete)
│   │── course.php        # Manage courses (Add, View, Edit, Delete)
│   │── faculty.php       # Manage faculty (Add, View, Edit, Delete)
│   │── student.php       # Manage students (Add, View, Edit, Delete)
│   │── manage_admins.php # Manage Admins (Super Admin Only)
│   │── logout.php        # Logout page
│
│── 📂 actions/           # Database Operations (Insert, Update, Delete)
│   │── add_department.php  
│   │── update_department.php
│   │── delete_department.php
│   │── add_course.php      
│   │── update_course.php  
│   │── delete_course.php  
│   │── add_faculty.php    
│   │── add_admin.php      # Super Admin can create new admins
│
│── 📂 database/          # SQL Scripts
│   │── init_db.sql       # Database setup script
│
│── index.php             # Homepage redirect
│── README.md             # Project documentation
│── .gitignore            # Ignore sensitive files


📂 University_dbms/ - Root Project Folder

│── 📂 assets/ - Static assets (CSS, JS, images)
│   │── 📂 css/ - All stylesheets
│   │   ├── styles.css - Main CSS file
│   │── 📂 js/ - JavaScript files
│   │   ├── scripts.js - (Optional) JavaScript for UI interactions
│   │── 📂 images/ - Folder for storing images (logos, icons)
│
│── 📂 config/ - Configuration & Database
│   │── db.php - Database connection
│   │── session.php - Session management (for admin authentication)
│
│── 📂 includes/ - Common reusable UI components
│   │── header.php - Common header (Navbar)
│   │── footer.php - Common footer
│   │── sidebar.php - Admin Sidebar Menu with Role-based Access
│
│── 📂 admin/ - Admin Panel for University Management
│   │── login.php - Admin Login Page
│   │── register.php - Admin Registration with Email Verification
│   │── reset_password.php - Admin Password Reset
│   │── dashboard.php - Admin Dashboard (Summary of University)
│   │── department.php - Manage Departments (Add, View, Edit, Delete)
│   │── course.php - Manage Courses (Add, View, Edit, Delete)
│   │── faculty.php - Manage Faculty (Add, View, Edit, Delete)
│   │── student.php - Manage Students (Add, View, Edit, Delete)
│   │── classroom.php - Manage Classrooms (Assign Faculty, View Rooms)
│   │── lab.php - Manage Labs (Assign Faculty, View Labs)
│   │── logout.php - Logout & Destroy Session
│
│── 📂 actions/ - Handles Database Operations (All Backend Processing)
│   │── add_department.php - Insert department into DB
│   │── update_department.php - Update department details
│   │── delete_department.php - Delete department
│   │── add_course.php - Insert course into DB
│   │── update_course.php - Update course details
│   │── delete_course.php - Delete course
│   │── add_faculty.php - Add faculty
│   │── add_student.php - Add student
│   │── verify_email.php - Handles email verification for admin signup
│   │── process_reset.php - Handles password reset
│
│── 📂 database/ - Database scripts
│   │── init_db.sql - SQL script for initial database setup
│
│── index.php - Main homepage (Redirects to login.php)
│── .gitignore - Ignore sensitive files (like config)
│── README.md - Project documentation