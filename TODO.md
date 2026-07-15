# TODO - Real Rating & Ulasan (Book Reviews)

## Step 1 - Analyze existing detail page
- [x] Identify dummy rating/ulasan usage in `resources/views/book-detail.blade.php`
- [x] Identify `BookDetailController` current data loading (book + category only)

## Step 2.0 - Initial backend scaffolding
- [x] Create migration + model: `book_reviews`, `BookReview`
- [x] Create ReviewController (store/update/destroy)
- [x] Add relations in `Book` and `User`
- [x] Add routes for storing/updating/deleting reviews

## Step 2 - Database
- [x] Create migration for `book_reviews` table
- [x] Add unique constraint: (book_id, user_id)


## Step 3 - Models / Relations
- [x] Create `app/Models/BookReview.php`
- [x] Add `Book::reviews()` relationship
- [x] Add `User::bookReviews()` relationship


## Step 4 - Controller
- [x] Create `app/Http/Controllers/User/ReviewController.php`
- [x] Implement `store()` and `update()` (1 user per book: update existing)

## Step 5 - Routes
- [x] Register routes for storing/updating/deleting reviews

## Step 6 - Controller data for page
- [x] Update `BookDetailController@show` to fetch:
  - AVG rating
  - COUNT ulasan
  - List reviews latest first
  - Current user review (if exists)

## Step 7 - Blade UI replacement (no dummy data)
- [x] Update `resources/views/book-detail.blade.php`:
  - Remove dummy rating summary + dummy review cards
  - Show DB-driven avg/count
  - Show review form only if authenticated
  - Form prefilled when user already reviewed
  - Show edit/delete buttons for reviews owned by current user
  - Render review list latest first with diffForHumans()


## Step 8 - DB migration + manual testing
- [x] Run migrations
- [ ] Test flows:
  - [ ] Guest: no form + shows login button
  - [ ] Auth user: create review
  - [ ] Auth user again same book: update review (no duplicate)
  - [ ] After submit: redirect back to detail and all values update from DB


## Step 9 - Deliver file list
- [ ] Provide list of files created/changed with short summaries

