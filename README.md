📂 University_dbms/        # Root project folder
│── 📂 assets/             # Static assets (CSS, JS, images)
│   │── css/              # All stylesheets
│   │   ├── styles.css    # Main CSS file
│   │── js/               # JavaScript files
│   │   ├── scripts.js    # (Optional) JavaScript for UI interactions
│   │── images/           # Folder for storing images (logos, icons)
│
│── 📂 config/             # Configuration & Database
│   │── db.php            # Database connection
│   │── session.php       # Session management (for admin authentication)
│
│── 📂 includes/           # Common reusable UI components
│   │── header.php        # Common header (Navbar)
│   │── footer.php        # Common footer
│   │── sidebar.php       # Admin Sidebar Menu
│
│── 📂 admin/             # Admin Panel for University Management
│   │── login.php         # Admin Login Page
│   │── dashboard.php     # Admin Dashboard (Summary of University)
│   │── department.php    # Manage Departments (Add, View, Edit, Delete)
│   │── course.php        # Manage Courses (Add, View, Edit, Delete)
│   │── faculty.php       # Manage Faculty (Add, View, Edit, Delete)
│   │── student.php       # Manage Students (Add, View, Edit, Delete)
│   │── classroom.php     # Manage Classrooms (Assign Faculty, View Rooms)
│   │── lab.php           # Manage Labs (Assign Faculty, View Labs)
│   │── logout.php        # Logout & Destroy Session
│
│── 📂 actions/           # Handles Database Operations (All Backend Processing)
│   │── add_department.php  # Insert department into DB
│   │── update_department.php # Update department details
│   │── delete_department.php # Delete department
│   │── add_course.php      # Insert course into DB
│   │── update_course.php   # Update course details
│   │── delete_course.php   # Delete course
│   │── add_faculty.php     # Add faculty
│   │── add_student.php     # Add student
│
│── 📂 database/          # Database scripts (if needed)
│   │── init_db.sql       # SQL script for initial database setup
│
│── index.php             # Main homepage (Redirects to login.php)
│── .gitignore            # Ignore sensitive files (like config)
│── README.md             # Project documentation