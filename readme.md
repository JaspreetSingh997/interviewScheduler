# Interview Scheduler

A PHP-MySQL based interview scheduler that allows candidates to book 15-minute interview slots and provides an admin panel for managing appointments.

## Features

- **Public Scheduler Page:**

  - View available 15-minute slots between 09:00 and 19:00.
  - Date picker restricted to today (with past time slots disabled) and future dates.
  - Booking form requires candidate name and email.
  - Prevents duplicate bookings by the same email.
  - Disables past and booked slots.

- **Admin Panel:**
  - Secure admin login (using MD5 for password hashing).
  - View all scheduled appointments in a sortable table.
  - Edit appointment details including date, time, candidate name, and email.
  - Delete individual appointments.
  - Bulk deletion with checkbox selection and "Select All" functionality (similar to Gmail).

## Technologies Used

- **Backend:** PHP, MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Additional:** MD5 for password hashing (admin login)

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/JaspreetSingh997/interviewScheduler.git
   cd interviewScheduler
   ```
