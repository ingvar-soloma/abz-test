# Test Assignment - REST API Server
This project is a test assignment for the PHP Developer position at ABZ Agency. It implements a simple REST API server using Laravel, handling user registration with image processing, authentication, and a minimal frontend for demonstration.

---
## 🚀 Live Demo
🔗 Deployed Project: [ABZ Agency - Test Assignment](https://abz-test-assignment.vikinglingo.online/)

---
## 📜 Features
### ✅ Backend (Laravel API)
- REST API following OpenAPI documentation.

- User data generation using seeders (45 users).

- Image Processing:

  - Cropped to 70x70px (center/center).

  - Optimized using TinyPNG API for better performance.

- JWT Authentication (only for demonstration).

- Validation: All input validation is handled on the backend.

- Pagination: Users list displayed with 6 users per page.

### 🎨 Frontend (Vue.js)
- Vue and Vuetify for frontend development.

- Users List with "Show More" button (lazy loading).

- User Registration Form (with enableable frontend validation, handled by backend).

---
## 🛠️ Tech Stack
### Backend
- PHP 8.2 
- Laravel 12
- MySQL
- JWT Authentication
- TinyPNG API (for image optimization)
- Laravel Seeders (for test data)
- AI Assistants (for writing tests, documentation and frontend)

### Frontend
- Vue.js 3 (for basic UI)
- Vuetify 3 (for UI components)
- Axios (for API requests)

---
## 📌 Installation & Setup
1️⃣ Clone the Repository
```bash
git clone https://github.com/ingvar-soloma/abz-test.git
cd abz-test
```

2️⃣ Install Dependencies
```bash
composer install
npm install
```

3️⃣ Set Up Environment
```bash
cp .env.example .env
php artisan key:generate
```
- Configure .env with your database credentials and [TinyPNG API Key](docs/tinypng.md).

4️⃣ Run Migrations & Seed Database
```bash
php artisan migrate --seed
```

5️⃣ Start the Server
```bash
php artisan serve
```

6️⃣ Run Frontend
```bash
npm run dev
```

---
## 🔥 API Endpoints
| Method | Endpoint       | 	Description                | Auth Required |
|--------|----------------|-----------------------------|---------------|
| POST   | /api/users     | Register a new user         | ✅ Yes         |
| GET    | /api/users     | Get paginated list of users | ❌ No          |
| GET    | /api/users{id} | Get User by id              | ❌ No          |
| GET    | /api/positions | Get positions list          | ❌ No          |

---
## ⏳ Time Spent & Challenges

⏳ Hours Spent:
- 21 hours

### 🤔 Challenges & Solutions
- Image Processing Delay → Offloaded to a Laravel Queue Job.
- Frontend Setup → Used Vuetify for quick UI prototyping.

1. ✅ Architecture & Code Reusability
   - Challenge: Needed a structured and reusable codebase.
   - Solution: Implemented abstract classes for Controller, Service, and Repository along with interfaces to ensure flexibility and maintainability.
2. ✅ Test-Driven Development (TDD)
   - Challenge: Ensuring code quality and reducing bugs.
   - Solution: Followed TDD approach by writing tests first and then implementing the code. This improved stability and helped catch edge cases early.
3. ✅ Manual Testing
    - Challenge: Needed to identify issues that automated tests might miss.
    - Solution: Conducted manual testing to uncover UI/UX issues and edge cases in API behavior.
4. ✅ Frontend Setup Issues
    - Challenge: Faced unexpected issues while installing Vue, configuring Vite, and importing it into a Blade template, even though I had done it multiple times before.
    - Solution: reread the documentation and guides.

---
## 📎 Additional Information

Contact: ingvar.soloma@gmail.com

This project follows the ABZ Agency guidelines and is intended solely for evaluation purposes.
