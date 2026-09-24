# Budget Tracker
 Description
Budget Tracker is a simple Laravel-based personal finance
management system. It allows a user to record daily income and
expense entries, each with an amount, category, and date, and
automatically computes running totals for the current day and
the current month. From the entry list, a user can add a new
entry, edit an existing one, or delete an entry that is no
longer needed. The system is meant to help a user stay aware of
their spending habits and manage their daily and monthly budget.

Corine Dino And Hugh Vale
 BSIT 4-3
 

- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Git
 
## Installation
1. Clone the repository:
   git clone https://github.com/cvbxian/budget-tracker.git
2. Install dependencies:
   composer install
3. Copy the environment file:
   cp .env.example .env
4. Generate the application key:
   php artisan key:generate
 
## Database
- Database name: budget_tracker_db
- Create the database in MySQL, then set DB_* values in your .env
- Run migrations to build the schema:
   php artisan migrate
 
## Running the Project
   php artisan serve
Then open http://127.0.0.1:8000 in your browser.
 
## Repository Link
https://github.com/cvbxian/budget-tracker
