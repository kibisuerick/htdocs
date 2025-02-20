<?php
require '../db.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Custom styling -->
</head>
<body>
<div class="container mt-4">
    <h2>Create Listing</h2>
    <form action="save_listing.php" method="POST" enctype="multipart/form-data">
        
        <!-- Location Section -->
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto">Location</legend>
            <div class="row">
                <div class="col-md-6">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>State</label>
                    <input type="text" name="state" class="form-control" required>
                </div>
            </div>
        </fieldset>

        <!-- Detailed Information Section -->
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto">Detailed Information</legend>
            <div class="row">
                <div class="col-md-6">
                    <label>Property Type</label>
                    <select name="property_type" class="form-control">
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Bedrooms</label>
                    <input type="number" name="bedrooms" class="form-control" required>
                </div>
            </div>
        </fieldset>

        <!-- Amenities and Features -->
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto">Amenities and Features</legend>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="amenities[]" value="parking">
                <label class="form-check-label">Parking</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="amenities[]" value="gym">
                <label class="form-check-label">Gym</label>
            </div>
        </fieldset>

        <!-- Property Media -->
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto">Property Media</legend>
            <label>Upload Images</label>
            <input type="file" name="property_images[]" class="form-control" multiple>
        </fieldset>

        <!-- Floor Plans -->
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto">Floor Plans</legend>
            <label>Upload Floor Plans</label>
            <input type="file" name="floor_plans[]" class="form-control" multiple>
        </fieldset>

        <!-- Save Button -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Save Listing</button>
        </div>
    </form>
</div>

<!-- Floating Save Progress Reminder -->
<div id="save-progress" class="position-fixed bottom-0 end-0 m-3 p-2 bg-warning rounded shadow">Auto-saving...</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        setInterval(() => {
            document.getElementById('save-progress').style.display = 'block';
            setTimeout(() => document.getElementById('save-progress').style.display = 'none', 2000);
        }, 15000);
    });
</script>
</body>
</html>
