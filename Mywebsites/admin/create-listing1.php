<?php
require '../db.php'; // Database connection

if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">Listing saved successfully!</div>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Custom styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .dropzone {
            border: 2px dashed #007bff;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }

        .dz-message {
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2>Create Listing</h2>
        <form action="../admin/save-listings.php" method="POST" enctype="multipart/form-data">
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

            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Property Media</legend>
                <input type="file" name="image" class="form-control" required>
            </fieldset>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save Listing</button>
            </div>
        </form>
    </div>
</body>

</html>
