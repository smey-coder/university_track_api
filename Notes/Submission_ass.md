# University Track
## Assignment & Assignment Submission Module

---

# Overview

The Assignment module allows teachers to create assignments for their courses, while students can submit their work online. Teachers can review submissions, provide feedback, and update submission status. Administrators have full access to all assignments and submissions.

---

# User Roles

## Administrator

Permissions:

- View all assignments
- Create assignments
- Update assignments
- Delete assignments
- View all submissions
- Update submissions
- Delete submissions
- View statistics

---

## Teacher

Permissions:

- View only assignments that belong to their courses
- Create assignments
- Update their own assignments
- Delete their own assignments
- View submissions for their own assignments
- Update submission feedback/status
- Cannot create submissions on behalf of students

---

## Student

Permissions:

- View assignments for enrolled courses
- Submit an assignment once
- Update submission before grading (if allowed)
- View submission status
- View score and feedback after review
- Cannot view other students' submissions

---

# Assignment Flow

```
Teacher/Admin
        │
        ▼
Create Assignment
        │
        ▼
Assignment Available
        │
        ▼
Student Views Assignment
        │
        ▼
Student Submits Assignment
        │
        ▼
Submission Stored
        │
        ▼
Teacher Reviews Submission
        │
        ▼
Teacher Updates Feedback
        │
        ▼
Student Views Result
```

---

# Assignment Status

| Status | Description |
|---------|-------------|
| Open | Students can submit |
| Closed | Submission closed |

---

# Submission Status

| Status | Description |
|---------|-------------|
| Submitted | Student submitted successfully |
| Reviewed | Teacher reviewed |
| Completed | Final evaluation completed |

---

# Assignment Database

## assignments

| Field |
|---------|
| id |
| assignment_code |
| course_id |
| teacher_id |
| title |
| description |
| due_date |
| total_score |
| status |
| created_at |
| updated_at |

---

# Assignment Submission Database

## assignment_submissions

| Field |
|---------|
| id |
| submission_code |
| assignment_id |
| student_id |
| content |
| file |
| submitted_at |
| score |
| feedback |
| status |
| created_at |
| updated_at |

---

# Assignment API

Base URL

```
/api/web/assignments
```

---

## Get Assignments

GET

```
/
```

Role

- Admin
- Teacher
- Student

---

## Create Assignment

POST

```
/create
```

Role

- Admin
- Teacher

Request

```json
{
    "course_id":1,
    "teacher_id":2,
    "title":"React Assignment",
    "description":"Build Dashboard",
    "due_date":"2026-08-01",
    "total_score":100
}
```

---

## Show Assignment

GET

```
/show/{id}
```

---

## Update Assignment

PUT

```
/update/{id}
```

---

## Delete Assignment

DELETE

```
/delete/{id}
```

---

# Assignment Submission API

Base URL

```
/api/web/assignment-submissions
```

---

## Available Assignments

GET

```
/available
```

Student only

Returns assignments that:

- belong to student's class
- are still Open
- have not been submitted

---

## Submit Assignment

POST

```
/create
```

Request

multipart/form-data

Fields

```
assignment_id
content
file
```

Flow

```
Student

↓

Check Assignment

↓

Already Submitted?

↓

Yes
↓

Return Error

No
↓

Generate Submission Code

↓

Upload File

↓

Save Database

↓

Success
```

---

## View Submission

GET

```
/show/{id}
```

---

## Update Submission

PUT

```
/update/{id}
```

Rules

- Only owner can update
- Cannot update after review if restricted by business rules
- Optional file replacement

---

## Delete Submission

DELETE

```
/delete/{id}
```

Administrator only

---

# Assignment Submission Flow

```
Teacher
      │
      ▼
Create Assignment
      │
      ▼
Student Login
      │
      ▼
View Available Assignments
      │
      ▼
Select Assignment
      │
      ▼
Write Content
      │
      ▼
Upload File
      │
      ▼
Submit
      │
      ▼
Submission Saved
      │
      ▼
Teacher Views Submission
      │
      ▼
Teacher Adds Feedback
      │
      ▼
Student Views Feedback
```

---

# Business Rules

## Assignment

- Assignment belongs to one Course
- Assignment belongs to one Teacher
- Due Date is required
- Total Score must be greater than zero

---

## Submission

One student can submit only once.

```
Student A
Assignment 1

✓ First Submission

Second Submission

❌ Not Allowed
```

---

Student can edit submission before review if your system policy allows it.

---

Teacher cannot submit assignments.

---

Teacher only views submissions of their own assignments.

---

Students only view their own submissions.

---

Administrators can manage everything.

---

# React Folder Structure

```
assignment_submissions/

│

├── index.jsx

├── CreateModal.jsx

├── UpdateModal.jsx

├── ShowModal.jsx

├── style.css

│

└── components/

    ├── SubmissionCard.jsx

    ├── SubmissionTable.jsx

    └── SubmissionStatusBadge.jsx
```

---

# Future Improvements

- Multiple file uploads
- File preview
- Download submitted files
- Late submission detection
- Submission history
- Rubric-based grading
- Inline comments
- AI plagiarism detection
- Email notifications
- Real-time dashboard statistics
- Assignment reminders
- Calendar integration

---

# Technology Stack

Backend

- Laravel 12
- Sanctum Authentication
- Spatie Permission
- MySQL

Frontend

- React.js
- Axios
- React Hot Toast
- React Router
- Bootstrap 5

---

# Module Summary

| Module | Admin | Teacher | Student |
|----------|-------|----------|----------|
| View Assignments | ✅ | ✅ Own | ✅ Enrolled |
| Create Assignment | ✅ | ✅ | ❌ |
| Update Assignment | ✅ | ✅ Own | ❌ |
| Delete Assignment | ✅ | ✅ Own | ❌ |
| View Submissions | ✅ All | ✅ Own | ✅ Own |
| Submit Assignment | ❌ | ❌ | ✅ Once |
| Update Submission | ✅ | ✅ Feedback/Status | ✅ Before review (optional) |
| Delete Submission | ✅ | ❌ | ❌ |
| View Feedback | ✅ | ✅ | ✅ |