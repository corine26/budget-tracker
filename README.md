# Budget Tracker

## Description
Budget Tracker is a simple Laravel-based personal finance management
system. It allows a user to record daily income and expense entries, each
with an amount, category, and date, and automatically computes running
totals for the current day and the current month. Users can view all
entries in one list, add new entries, edit existing ones, and delete
entries that are no longer needed. The goal of the system is to help a
user stay aware of their spending habits and manage their daily and
monthly budget more responsibly.

## Student Information
- Names: Hugh Vale, Corine Dino
- Course, Year & Section: BSIT 4-3

## Software Requirements
- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Git

## Installation
1. Clone the repository:
   git clone https://github.com/<username>/budget-tracker.git
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
https://github.com/corine26/budget-tracker

---

## Request Data Model (Laboratory 2)

### requests Table Fields
- id (PK, auto-increment)
- requester_name (string, 100)
- requester_email (string, 255)
- item_name (string, 150)
- quantity (unsigned integer, must be > 0)
- purpose (text)
- status (string, 20, default: pending)
- created_at / updated_at (timestamps)

### Migration Command
   php artisan make:migration create_requests_table
   php artisan migrate

### Verifying the Table
1. php artisan migrate:status  (confirm it is marked Ran)
2. Open phpMyAdmin > budget_tracker_db > requests > Structure tab
3. Run: SELECT id, requester_name, item_name, quantity, status FROM requests;
4. Confirm a row with an omitted status shows the default 'pending'

### User Stories

**As a budget requester**, I want to submit a request for an item or
reimbursement with a quantity and purpose so that I can get it approved and
charged against the budget through a recorded, trackable process.
- Given a requester provides a name, email, item name, a quantity greater
  than zero, and a purpose, when the request is saved, then a new row is
  created in the requests table.
- Given no status is specified, when the row is saved, then it defaults to
  'pending'.

**As a staff reviewer (budget approver)**, I want to view all submitted
budget requests along with their current status so that I can identify
which ones still need review before funds are released.
- Given at least one request exists, when I query id, requester_name,
  item_name, quantity, and status, then all matching rows are returned.
- Given a request has not been reviewed, when I view it, then its status
  still reads 'pending'.

**As a record keeper (accountant)**, I want every budget request to
automatically store when it was created and last updated so that I can
maintain an accurate audit trail of spending approvals.
- Given a new request is inserted, when I inspect the row, then created_at
  and updated_at are both automatically populated.
- Given an existing request is later modified (e.g., approved), when I
  inspect it again, then updated_at reflects a newer timestamp than
  created_at.
## Laboratory 3 Verification
## Secure Request Access (Laboratory 3)
 
### Ownership and access rules
| Action | Student (owner) | Student (not owner) | Administrator | Guest |
|---|---|---|---|---|
| List requests | Own records only | Own records only | All records | Redirect to login |
| View a request | Allowed | 403 Forbidden | Allowed | Redirect to login |
| Create a request | Allowed | Allowed (own) | Not allowed | Redirect to login |
| Update status | 403 Forbidden | 403 Forbidden | Allowed | Redirect to login |
 
Ownership is decided by `requests.user_id`, never by requester_name or requester_email.
 
### Denial response
A student opening another student's record receives **403 Forbidden**.
This is applied consistently to view and status-update denials.
 
### Route summary
| Method | URI | Controller method | Access |
|---|---|---|---|
| GET | /login | AuthController@showLogin | Guests |
| POST | /login | AuthController@login | Guests |
| POST | /logout | AuthController@logout | Signed-in |
| GET | /requests | ServiceRequestController@index | Signed-in (scoped) |
| GET | /requests/create | ServiceRequestController@create | Students |
| POST | /requests | ServiceRequestController@store | Students |
| GET | /requests/{id} | ServiceRequestController@show | Owner or admin |
| PATCH | /requests/{id}/status | ServiceRequestController@updateStatus | Admin only |
 
### File responsibilities
- Policy (app/Policies/ServiceRequestPolicy.php): Corine Dino
- Controller (app/Http/Controllers/ServiceRequestController.php): Corine Dino
- Routes (routes/web.php): Corine Dino
- Views (resources/views/): Hugh Vale
- Tests (manual access/input test matrix T01-T10): Hugh Vale
 
### Setup, migration and seeding
1. composer install
2. cp .env.example .env
3. php artisan key:generate
4. Set DB_* values and the two LAB_*_PASSWORD values in your local .env
   (use fictional passwords; never commit .env)
5. Create the database budget_tracker_db in MySQL
6. php artisan migrate
7. php artisan db:seed --class=LabAccountsSeeder
8. php artisan serve
 
Fictional accounts: student.a@example.com, student.b@example.com,
admin@example.com (passwords come from your local .env).
 
### Testing steps
Run the manual matrix T01-T10 with three separate browser sessions
(Student A, Student B, Administrator). Compare database values before and
after each write attempt. Test CSRF rejection (419) through live browser
requests, not automated feature tests.
 
### Security notes
- Passwords are stored with Laravel hashing (bcrypt).
- .env is excluded by .gitignore; .env.example holds no real credentials.
- APP_DEBUG must be false in deployed or shared environments.
- Run `composer audit` and review findings before upgrading dependenciesgit status
Verification instruction: Test administrator access and administrator-only status updates.