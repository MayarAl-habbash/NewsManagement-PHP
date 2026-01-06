# News Management System – Dockerized PHP Application

## 📌 Project Overview
This project is a **News Management System** built using **PHP and MySQL**, designed to manage news articles, categories, and users.

The primary goal of this assignment is not only application functionality, but to demonstrate the ability to **containerize a real-world project using Docker** and publish it in a **professional GitHub repository** with a clean structure and clear documentation.

The project is fully Dockerized, allowing any developer to run it reliably without installing PHP, Apache, or MySQL locally.

---

## 🎯 Assignment Objective
This project fulfills the requirements of **OS LAB – Assignment #2 (Docker & GitHub)** by:
- Running a real, functional project inside Docker
- Using Docker Compose to manage multiple services
- Providing clear documentation for reproducibility
- Following professional GitHub structure and commit practices

---

## 🛠️ Tech Stack
- **PHP 8.2**
- **Apache Web Server**
- **MySQL 8.0**
- **PDO (PHP Data Objects)**
- **Docker & Docker Compose**

---


## 📂 Project Structure
news-management/
├─ src/ # Application source code (PHP)
├─ Dockerfile # PHP + Apache Docker image
├─ docker-compose.yml # Multi-container setup (Web + DB)
├─ .dockerignore # Excluded files from Docker image
├─ .gitignore # Excluded files from GitHub
├─ README.md # Project documentation
├─ docs/
│ ├─ screenshots/ # Assignment proof screenshots
│ └─ notes.md # Technical notes and reflections


---

## 🐳 Docker Setup Explanation

### Dockerfile
- Base image: `php:8.2-apache`
- Working directory: `/var/www/html`
- Installed PHP extensions: `mysqli`, `pdo`, `pdo_mysql`
- Only necessary project files copied to reduce image size

### Docker Compose
- Services:
  - **web** → PHP + Apache container
  - **db** → MySQL container
- Ensures:
  - Proper networking between containers
  - Automatic database creation on startup
  - Consistent environment across machines

---

## ▶️ How to Build and Run the Project

### 1️⃣ Clone the Repository
```bash
git clone <your-repository-link>
cd news-management

2️⃣ Build and Start Containers
`docker compose up --build`

3️⃣ Access the Application
Open your browser and visit:
`http://localhost:8000`
If the page loads successfully, the project is running correctly.

⏹️ Stop Containers and Clean Up
Stop containers:
`docker compose down`

Stop containers and remove volumes:
`docker compose down -v`

⚙️ Configuration Notes

Web Port: 8000

Database Port: 3306

Database Name: news_management3

Database User: admin

Database Password: admin123

Database Host (inside Docker): db (MySQL service name in Docker Compose)

⚠️ Important: Inside Docker, the database host must be the service name (db), not localhost.

🧪 Testing the Application

Open the browser at http://localhost:8000

Verify that the page loads without errors

Database tables are automatically created on first run

Check MySQL container logs for successful initialization

📸 Proof of Work

Screenshots demonstrating:

Successful Docker image build

Running containers

Working application in the browser

GitHub commit history

All screenshots are included in:
`docs/screenshots/`

📌 Notes

This project was developed specifically for OS LAB – Assignment #2 to demonstrate:

Practical Docker usage

Clean repository structure

Professional documentation practices

👤 Author

Mayar
Web Computing Student
Operating Systems Lab – Docker & GitHub Assignment