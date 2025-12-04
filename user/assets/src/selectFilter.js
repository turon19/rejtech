const selectFilter = document.querySelector(".user-selectFilter");
const filterForm = document.querySelector(".user-filterForm");

selectFilter.addEventListener("change", () => {
  filterForm.submit();
});
