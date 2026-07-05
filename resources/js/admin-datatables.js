/*
  Admin DataTables bootstrap (NO global init)
  - Only initializes tables that exist on the current page
  - Prevents reinitialise warnings via DataTables.isDataTable()
*/

(function () {
  function initTable(selector, options) {
    if (!selector) return;
    const $el = window.jQuery ? window.jQuery(selector) : null;
    if (!$el || $el.length === 0) return;

    if (window.jQuery.fn.DataTable && window.jQuery.fn.DataTable.isDataTable($el)) {
      return;
    }

    $el.DataTable(options || {});
  }

  function init() {
    if (!window.jQuery || !window.jQuery.fn.DataTable) return;

    initTable('#booksTable', { pageLength: 10 });
    initTable('#usersTable', { pageLength: 10 });
    initTable('#borrowingsTable', { pageLength: 10 });
    initTable('#categoriesTable', { pageLength: 10 });
    initTable('#membershipTable', { pageLength: 10 });
    initTable('#recentBorrowings', { pageLength: 5 });
  }

  document.addEventListener('DOMContentLoaded', init);
})();

