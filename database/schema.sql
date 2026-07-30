CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE students (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE teachers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE grades (
  id INT AUTO_INCREMENT,
  student_id INT NOT NULL,
  teacher_id INT NOT NULL,
  subject VARCHAR(255) NOT NULL,
  grade DECIMAL(3, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

CREATE TABLE schedules (
  id INT AUTO_INCREMENT,
  teacher_id INT NOT NULL,
  subject VARCHAR(255) NOT NULL,
  day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO students (name, email) VALUES
('Student 1', 'student1@example.com'),
('Student 2', 'student2@example.com'),
('Student 3', 'student3@example.com');

INSERT INTO teachers (name, email) VALUES
('Teacher 1', 'teacher1@example.com'),
('Teacher 2', 'teacher2@example.com'),
('Teacher 3', 'teacher3@example.com');

INSERT INTO grades (student_id, teacher_id, subject, grade) VALUES
(1, 1, 'Math', 85.00),
(1, 2, 'Science', 90.00),
(2, 1, 'Math', 80.00),
(2, 2, 'Science', 95.00),
(3, 1, 'Math', 70.00),
(3, 2, 'Science', 80.00);

INSERT INTO schedules (teacher_id, subject, day_of_week, start_time, end_time) VALUES
(1, 'Math', 'Monday', '08:00:00', '09:00:00'),
(1, 'Science', 'Tuesday', '09:00:00', '10:00:00'),
(2, 'Math', 'Wednesday', '08:00:00', '09:00:00'),
(2, 'Science', 'Thursday', '09:00:00', '10:00:00'),
(3, 'Math', 'Friday', '08:00:00', '09:00:00'),
(3, 'Science', 'Saturday', '09:00:00', '10:00:00');