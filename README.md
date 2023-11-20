<details>
<summary>Teacher Manager Transcript Project</summary>

## Overview

The Teacher Manager Transcript project is a web application designed to manage and calculate transcripts for teachers. It allows teachers to register, add courses, students, assign courses to students, and perform various actions related to student records. This documentation provides information on the technologies used, database structure, setup instructions, and an overview of the application.

## Technologies Used

- **PHP:** Version 8.2.4
- **MySQL:** Version 8.0.35
- **Apache:** Version 2.4.56
- **phpMyAdmin:** Version 5.2.1
- **Version Control:** Git and GitHub

## Database Structure

The project utilizes a MySQL database with the following key tables:

- **Courses:** Contains details about different courses.
- **Students:** Stores information about students, including their details and the courses they attend.
- **Scores:** Records student scores in different courses.
- **StudentCourses:** Records students' scores along with additional details.
- **StudentAverages:** Stores calculated GPAs for students in various courses.
- **CourseAverages:** Stores average GPAs for each course.

## Setting Up the Project

### Prerequisites

- Web server with PHP support (e.g., Apache, Nginx).
- MySQL database server.
- PHP version 8.x.
- Composer for installing PHP dependencies.
- Git for version control.

### Installation Steps

1. **Clone the Repository:**
git clone https://github.com/Revinianz13/Manager-Trasnscript.git


2. **Database Setup:**
Import the provided database dump (Data_Teacher.sql) into your MySQL server.

3. **Access the Application:**

Open your web browser and navigate to the application URL.


### Teacher Functionalities

1. **Register:** Teachers can register in the app.
2. **Add Courses:** Teachers can add as many courses as they want.
3. **Add Students:** Teachers can add students to the system.
4. **Assign Courses:** Teachers can assign courses to students.
5. **Edit/Delete Student:** Teachers can edit or delete student records.
6. **Display Student GPA:** Teachers can view GPAs for a single student with attended courses and average GPAs for the courses.
7. **Display All Students:** Teachers can view all students, their attended courses, GPAs, and average GPAs for the courses.
8. **Export to PDF:** Teachers can export their data in PDF.

**Demo Account**
For testing purposes, you can use the following demo account:

Username: Revinianz13
Password: 123456A!
Note: Change the login credentials in a production environment.


Feel free to reach out for further assistance or customization.

</details>