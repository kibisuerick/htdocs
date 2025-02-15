document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const dropdown = document.getElementById("searchDropdown");

    searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim();

        if (query.length < 2) {
            dropdown.style.display = "none";
            return;
        }

        fetch(`search_suggestions1.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                dropdown.innerHTML = ""; // Clear previous suggestions
                
                if (data.length > 0) {
                    const ul = document.createElement("ul");
                    ul.classList.add("dropdown-list"); // Add class for styling

                    data.forEach(item => {
                        const li = document.createElement("li");
                        li.textContent = item.location;
                        li.classList.add("dropdown-item"); // Add class for styling
                        
                        li.addEventListener("click", function () {
                            searchInput.value = item.location;
                            dropdown.style.display = "none";
                            document.querySelector(".predictive__search--form").submit();
                        });

                        ul.appendChild(li);
                    });

                    dropdown.appendChild(ul);
                    dropdown.style.display = "block";
                } else {
                    dropdown.style.display = "none";
                }
            })
            .catch(error => console.error("Error fetching suggestions:", error));
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });
});
