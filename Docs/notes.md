
---

### **2️⃣ notes.md (التفاصيل التقنية والملاحظات)**

```markdown
# Notes & Reflections – News Management System (Dockerized)

## 1️⃣ Project Setup
- Cloned project repo and created local environment using Docker.
- Project structure carefully organized to separate `src` code, Docker setup, and documentation.

## 2️⃣ Docker & Docker Compose
### Dockerfile
- Base image: `php:8.2-apache`
- Working directory: `/var/www/html`
- Installed PHP extensions: `mysqli`, `pdo`, `pdo_mysql`
- Copied only `src` and necessary config files to minimize image size

### docker-compose.yml
- Services:
  - **web** → PHP + Apache container
  - **db** → MySQL container
- Mapped ports:
  - `8000:80` → web
  - `3306:3306` → db
- Environment variables set for MySQL (`MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_ROOT_PASSWORD`)

## 3️⃣ Challenges & Solutions
| Problem | Solution |
|---------|---------|
| Database connection failed using `localhost` | Use Docker service name `db` as host |
| Docker Compose YAML validation errors | Fixed indentation and environment variable mapping |
| Database initialization conflicts | Use `docker compose down -v` to reset volumes |
| `.dockerignore` missing | Added `.dockerignore` to exclude unnecessary files from image |
| `.gitignore` missing | Added `.gitignore` to prevent committing temporary files |

## 4️⃣ Key Steps in Assignment
1. Built Docker image for PHP + Apache
2. Configured MySQL container with correct credentials
3. Created `docker-compose.yml` for multi-container setup
4. Tested container communication (`web` → `db`)
5. Validated application loads and database tables created automatically
6. Documented workflow in `README.md` and captured screenshots for proof

## 5️⃣ Lessons Learned
- Docker service names are crucial for container-to-container communication.
- `.dockerignore` significantly reduces image build time and size.
- Consistent project structure improves reproducibility and professional appearance.
- YAML indentation mistakes can break Compose files — attention to detail is key.
- Automated container setup ensures any developer can run project locally without manual PHP/DB installation.

---

**Author:** Mayar  
**Course:** Web Computing  
**Assignment:** OS Lab – Docker & GitHub Assignment #2
