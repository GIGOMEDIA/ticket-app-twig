# 🎫 Ticket Management System

A **responsive Ticket Management Web Application** built with **PHP**, **Twig**, and **Bootstrap 5**. Users can **register, login, create tickets**, and **view ticket lists**.

---

## 🌟 Features

- User Registration & Login (local storage for demo purposes)
- Responsive design using Bootstrap 5
- Dashboard with ticket summary cards
- Create and view tickets
- Reusable navbar and footer partials
- Simple routing using `index.php`

---

## 📁 Project Structure

ticket-app-twig/
│
├── templates/
│ ├── partials/
│ │ ├── navbar.twig
│ │ └── footer.twig
│ ├── layout.twig
│ ├── login.twig
│ ├── register.twig
│ ├── dashboard.twig
│ └── ticket-list.twig
│
├── vendor/ # Composer dependencies
├── index.php # Main router
├── login.php # Handles login POST requests
├── register.php # Handles registration POST requests
└── create_ticket.php # Handles ticket creation