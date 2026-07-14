# TODO

## Membership Premium Upgrade Flow Fix
- [x] Audit `MembershipUpgradeController` current logic (guardCanRequestUpgrade + pay)
- [x] Update `guardCanRequestUpgrade()` rules (guest, membership_status active only, deny premium role, deny existing pending membership_upgrades)
- [x] Fix `MembershipUpgradeController::pay()` so it NEVER changes `users.membership_status`
- [ ] Audit `Admin\AdminMembershipController` approve/reject to match requirements:
  - [ ] Approve: membership_upgrades.status=active, users.role=premium, start_date/end_date
  - [ ] Reject: membership_upgrades.status=rejected, users.role=pengguna, users.membership_status tetap active
- [ ] Run quick manual verification checklist (route access + status changes)


