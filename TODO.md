# TODO: Fix farmer_id references to farmer_uuid

## Files to Update:
- [x] delete-farmer.php: Update queries to use farmer_uuid
- [x] manage_farmer.php: Update links to use uuid
- [ ] inventory_report.php: Update JOIN and WHERE to use farmer_uuid
- [ ] manage_collection.php: Update JOIN to use farmer_uuid
- [ ] payment_report.php: Update JOIN to use farmer_uuid
- [ ] milk_collection_report.php: Update JOIN to use farmer_uuid
- [ ] process_add_collection.php: Already updated
- [ ] user_dashboard.php: Update queries to use farmer_uuid
- [ ] user_profile.php: Update WHERE to use farmer_uuid
- [ ] user_report.php: Update WHERE to use farmer_uuid
- [ ] user_payment.php: Update WHERE to use farmer_uuid
- [ ] user_milk_record.php: Update WHERE to use farmer_uuid
- [ ] add_collection.php: Update farmer selection if needed
- [ ] index.php: Update session to use farmer_uuid
- [x] view-farmer.php: Updated to use uuid instead of id
- [x] edit-farmer.php: Updated to use uuid instead of id
- [x] process_edit_farmer.php: Updated to use uuid instead of id

## Steps:
1. Update each file to replace farmer_id with farmer_uuid in queries.
2. Test login and functionality to ensure no "Unknown column" errors.
3. Clean up temporary files created during development.
