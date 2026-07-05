# TODO

## Phase: Connect Books data to User Dashboard (no UI changes)

- [ ] Step 1: Audit user dashboard views (dashboard.blade.php, home.blade.php, book-detail.blade.php, pdf-reader.blade.php) and identify dummy/hardcode data.
- [ ] Step 2: Create/Update controller for user dashboard to fetch:
  - [ ] latestBooks (latest order)
  - [ ] recommendedBooks (stock > 0)
  - [ ] categories (all)
- [ ] Step 3: Update user dashboard blade to use passed variables (covers from storage/covers, show placeholder if empty).
- [ ] Step 4: Update card links to open detail page, not PDF dummy.
- [ ] Step 5: Update book-detail blade to show book attributes from DB.
- [ ] Step 6: Update pdf-reader blade to load real PDF from storage/pdfs.
- [ ] Step 7: Discover page (if exists) to be DB-driven with search/filter/sort.
- [ ] Step 8: Homepage sections: Recomendasi minimal 8 terbaru.
- [ ] Step 9: Ensure php artisan storage:link is documented/run if needed.
- [ ] Output: list changed files, routes, controllers, models, and Eloquent queries used.

