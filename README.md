# Laravel + Vue.js Application

Technology Stack:

- **PHP**: 8.4
- **Laravel Framework**: 11.31
- **Docker** for containerization
- **Nginx** as the web server
- **PostgreSQL** as the database
- **Vue.js 3** for the frontend
- **TailwindCSS** for styling
- **Vite** for asset building

## Requirements

- Docker must be installed and running on your system.

---

## Getting Started

1. **Prepare the Country Languages File**  
   Place the required file containing country languages in the following path: `%projectDir%/storage/app/private`

2. **Start the Project**  
   Run the following commands to set up and start the project:

- **Build Docker Containers**
  ```bash
  docker-compose up --build
  ```

- **Install Dependencies**  
  Inside the PHP container, run:
  ```bash
  docker-compose exec php composer install && npm install
  ```

- **Run database migrations**
  Inside the PHP container, run:
  ```bash
  docker-compose exec php php artisan migrate
  ```

- **Build Frontend Assets**  
  Inside the PHP container, run:
  ```bash
  docker-compose exec php npm run build
  ```

3. **Access the Application**  
   Open your browser and navigate to the application URL as configured in your Docker environment. By default it is http://127.0.0.1:8080/
