document.addEventListener("DOMContentLoaded", () => {
    const filter = document.getElementById("categoryFilter");
    const tableBody = document.getElementById("menuTableBody");

    filter.addEventListener("change", () => {
        const categoryId = filter.value;

        fetch(`menu_list.php?ajax=1&category_id=${categoryId}`)
            .then(res => res.text())
            .then(html => {
                tableBody.innerHTML = html;
            })
            .catch(err => console.error(err));
    });
});
