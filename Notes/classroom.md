🏫 Classroom Management Module — Summary

The Classroom Management Module is a feature of the University Track System that helps administrators manage and monitor university classrooms.

Main Purpose
Manage classroom information
Track students in each class
View assigned teachers
Manage courses and schedules
Provide a complete classroom overview
✨ Main Features
1. Classroom List

Admin can view:

Class Name
Department
Academic Year
Semester
Room
Number of Students
Status
2. Dashboard Statistics

Displays:

🏫 Total Classrooms
👨‍🎓 Total Students
📖 Total Courses
👨‍🏫 Total Teachers
3. Search & Filter

Admin can search and filter classrooms by:

Class name
Department
Academic Year
Semester
Teacher
Course
4. Classroom Actions

Each classroom provides:

Button	Function
👁	View classroom overview
👨‍🎓	View students
📖	View courses
🕒	View schedule
📋 Classroom Detail Modal

The modal provides:

Overview Tab

Shows:

Classroom name
Department
Academic year
Semester
Room
Student count
Status
Students Tab

Displays:

Student code
Student name
Gender
Phone
Email
Status
Teachers Tab

Displays:

Teacher code
Teacher name
Phone
Email
Photo
Courses Tab

Displays:

Course code
Course name
Credits
Teacher
Schedule Tab

Displays:

Course
Teacher
Day
Start time
End time
Room
🛠 Technology
Frontend
React.js
Axios
React Hot Toast
CSS
Backend
Laravel REST API
Laravel Sanctum
Eloquent ORM
MySQL
📂 React Components
Classroom/
│
├── ClassroomIndex.jsx
├── ShowModal.jsx
├── ClassroomCard.jsx
├── StudentTab.jsx
├── TeacherTab.jsx
├── CourseTab.jsx
├── ScheduleTab.jsx
├── classroom.css
└── showModule.css
🔗 API Endpoints
GET /api/web/classrooms

GET /api/web/classrooms/{id}/students

GET /api/web/classrooms/{id}/teachers

GET /api/web/classrooms/{id}/courses

GET /api/web/classrooms/{id}/schedule
🔐 Security

Uses:

Laravel Sanctum Authentication
Bearer Token Authorization
🔄 User Flow
Admin Login

     ↓

Classroom Management

     ↓

Classroom List

     ↓

View / Students / Courses / Schedule

     ↓

Classroom Detail Modal