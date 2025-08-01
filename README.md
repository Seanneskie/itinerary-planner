# Itinerary Planner

Itinerary Planner is a web application built with **Laravel** that lets you organize trips and their day-to-day activities. It provides an intuitive interface for managing itineraries, adding activities on a map and keeping travel notes in one place.

## Features
- **User authentication** – register and log in to manage your own itineraries.
- **Itinerary management** – create itineraries with a title, description and date range.
- **Activity planning** – attach activities to an itinerary with time, notes and an optional map location.
- **Interactive map** – drop pins using [Leaflet](https://leafletjs.com/) to visualize where activities take place. The map respects light and dark themes.
- **Dashboard overview** – view all of your itineraries and quickly add new ones.
- **Budget tracking** – record expenses for each itinerary, view totals and charts.
- **Category filtering** – filter budget entries by category to refine reports.

## Requirements
- PHP 8.2 or higher
- Node.js and npm
- A database supported by Laravel (SQLite is fine for local development)

## Installation
1. Clone the repository and install PHP dependencies:
   ```bash
   composer install
   ```
2. Install the JavaScript dependencies and compile assets:
   ```bash
   npm install
   npm run dev
   ```
3. Copy the example environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations and start the development server:
   ```bash
   php artisan migrate
   php artisan serve
   ```
The application will be available at `http://localhost:8000`.

## Testing
Run the test suite with:
```bash
composer test
```

## Project Structure
- `app/` – application logic, controllers and models
- `resources/views/` – Blade templates and UI components
- `resources/js/` – front-end scripts powered by Vite and Alpine.js
- `routes/web.php` – web routes for the application

## Budget Tracking
Each itinerary has a budget page where you can log expenses. Entries can be edited or deleted, and the page displays the total spent along with helpful charts.
You can also filter entries by category to focus on specific types of spending.

## Contributing
Feel free to open issues or submit pull requests. For major changes, please open an issue first to discuss what you would like to change.

## License
This project is open-sourced software licensed under the MIT license.
