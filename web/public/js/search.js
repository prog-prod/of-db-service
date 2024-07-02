window.dataLayer = window.dataLayer || [];
function submitSearch() {
    const searchInput = document.getElementById("landing-input");
    if (!searchInput.value || searchInput.value.trim() === "") {
        window.location.href = '/featured/profiles/';
        return;
    }
    document.getElementById("searchForm").submit();
}