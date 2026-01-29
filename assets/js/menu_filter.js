// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", () => {
     // Get the category dropdown and the table body
    const filter = document.getElementById("categoryFilter");
    const tableBody = document.getElementById("menuTableBody");
    // Listen for change events on category dropdown
    filter.addEventListener("change", () => {
        const categoryId = filter.value;// Get selected category ID
        // Use fetch API to send a GET request to PHP endpoint for filtered menu
        fetch(`menu_list.php?ajax=1&category_id=${categoryId}`)
            .then(response => {  
                fetch(`menu_list.php?ajax=1&category_id=${categoryId}`)
        // Get the response as HTML/text
            .then(res => res.text())
            .then(html => {
                // Replace table body content with fetched HTML
                tableBody.innerHTML = html;
            })
            .catch(err => console.error(err));
    });
});
