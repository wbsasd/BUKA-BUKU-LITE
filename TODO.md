# TODO - Membership Request Dashboard Quick Action

## Step 1
- [x] Analyze current dashboard and membership admin controllers/routes.
- [x] Check DB migration for membership_upgrades to find payment proof field.

## Step 2
- [ ] Update `resources/views/admin/dashboard.blade.php`:
  - [ ] Replace Approve/Reject dummy buttons with real POST forms using existing routes/logic (no new controller methods, no duplicated logic).
  - [ ] Add Bootstrap Modal "Detail" for each membership request card; no page navigation.
  - [ ] Populate modal fields with request data.
  - [ ] If payment proof field not present in DB, show fallback text "Belum upload bukti pembayaran."

## Step 3
- [ ] Ensure after approve/reject, dashboard data refreshes via redirect (pending counter + request card list + membership menu update through existing pages).

## Step 4
- [ ] Quick manual test checklist:
  - [ ] Approve from dashboard updates membership status correctly.
  - [ ] Reject from dashboard updates correctly.
  - [ ] Detail modal opens and shows correct data.
  - [ ] Membership menu reflect updates.

