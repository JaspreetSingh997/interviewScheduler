CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  candidate_name VARCHAR(255) NOT NULL,
  candidate_email VARCHAR(255) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (appointment_date, appointment_time)
);

CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);

-- Insert a sample admin user (password: admin123 using MD5)
INSERT INTO admin_users (username, password) VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3');
