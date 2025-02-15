<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Properties</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Container for search bar */
        .search-container {
            position: relative;
            width: 300px;
        }

        /* Search input field */
        .search-box {
            width: 100%;
            padding: 10px;
            padding-right: 40px; /* Make space for the icon */
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 16px;
            outline: none;
        }

        /* Search icon styling */
        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }

        /* Suggestions dropdown */
        .suggestions-dropdown {
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            position: absolute;
            background-color: white;
            width: 100%;
            z-index: 1000;
            border-radius: 5px;
        }

        .suggestions-dropdown div {
            padding: 8px;
            cursor: pointer;
        }

        .suggestions-dropdown div:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    <h2>Search for a Property</h2>
    <div class="search-container">
        <form id="searchForm" action="../listings.php" method="GET">
            <input type="text" id="searchBox" name="query" class="search-box" placeholder="Search location..." autocomplete="off">
            <button type="submit" class="search-button">
                <i class="fa fa-search search-icon"></i> <!-- Search Icon -->
            </button>
        </form>
        <div id="suggestions" class="suggestions-dropdown"></div>
    </div>

    

    <script>
        $(document).ready(function () {
            $("#searchBox").on("keyup", function () {
                let query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "search_suggestions1.php",
                        method: "GET",
                        data: { query: query },
                        success: function (data) {
                            let results = JSON.parse(data);
                            let suggestionsBox = $("#suggestions");
                            suggestionsBox.empty();
                            if (results.length > 0) {
                                results.forEach(function (item) {
                                    suggestionsBox.append(`<div onclick="selectSuggestion('${item.location}')">${item.location}</div>`);
                                });
                                suggestionsBox.show();
                            } else {
                                suggestionsBox.hide();
                            }
                        }
                    });
                } else {
                    $("#suggestions").hide();
                }
            });
        });

        function selectSuggestion(value) {
            $("#searchBox").val(value);
            $("#suggestions").hide();
        }

        document.addEventListener("DOMContentLoaded", function () {
    const searchBox = document.getElementById("searchBox");
    const searchForm = document.getElementById("searchForm");

    // Trigger search when pressing Enter
    searchBox.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent default form submission
            if (searchBox.value.trim() !== "") {
                searchForm.submit(); // Submit form if input is not empty
            }
        }
    });
});

    </script>

</body>
</html>
