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
    <!-- Include Dropzone CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Create Listing</h2>
        <form action="../admin/save-listings.php" method="POST" enctype="multipart/form-data">

            <!-- Location Section -->
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Location</legend>
                <div class="row">
                    <div class="col-md-6">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" required="">
                    </div>
                    <div class="col-md-6">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" required="">
                    </div>
                </div>
            </fieldset>
            <!-- title section -->
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Title</legend>
                <div class="row">
                    <div class="col-md-6">
                        <label>title</label>
                        <input type="text" name="title" class="form-control" required="">
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
                <!-- Dropzone File Upload -->
                <div class="dropzone" id="propertyImageUpload">
                    <div class="dz-message">
                        Drag & Drop files here or click to upload
                    </div>
                </div>
                <input type="hidden" name="property_images" id="propertyImages">
            </fieldset>

            <!-- Video Upload Section -->
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Videos</legend>
                <!-- Dropzone File Upload for Videos -->
                <div class="dropzone" id="videoUpload">
                    <div class="dz-message">
                        Drag & Drop video files here or click to upload
                    </div>
                </div>
                <input type="hidden" name="videos" id="videos">
            </fieldset>

            <!-- Floor Plans -->
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Floor Plans</legend>
                <!-- Dropzone File Upload -->
                <div class="dropzone" id="floorPlansUpload">
                    <div class="dz-message">
                        Drag & Drop files here or click to upload
                    </div>
                </div>
                <input type="hidden" name="floor_plan_images" id="floorPlanImages">
            </fieldset>

            <!-- Save Button -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save Listing</button>
            </div>
        </form>
    </div>

    <!-- Floating Save Progress Reminder -->
    <div id="save-progress" class="position-fixed bottom-0 end-0 m-3 p-2 bg-warning rounded shadow" style="display: none;">
        Auto-saving...
    </div>

    <!-- Include Dropzone.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let formChanged = false;

            document.querySelector("form").addEventListener("input", function() {
                formChanged = true;
            });

            setInterval(() => {
                if (formChanged) {
                    document.getElementById('save-progress').style.display = 'block';
                    setTimeout(() => document.getElementById('save-progress').style.display = 'none', 2000);
                    formChanged = false;
                }
            }, 15000);
        });

        // Initialize Dropzone for Property Media
        Dropzone.options.propertyImageUpload = {
            url: "upload.php",
            paramName: "image",
            maxFilesize: 5, // 5MB limit
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictRemoveFile: "Remove",
            init: function() {
                let uploadedFiles = [];

                this.on("success", function(file, response) {
                    uploadedFiles.push(response.filePath); // Store file paths
                    console.log("File uploaded:", response);
                });
            }
        };

        // Initialize Dropzone for Video Upload
        Dropzone.options.videoUpload = {
            url: "upload.php",
            paramName: "video",
            maxFilesize: 50, // 50MB limit for videos
            acceptedFiles: "video/*",
            addRemoveLinks: true,
            dictRemoveFile: "Remove",
            init: function() {
                let uploadedVideos = [];

                this.on("success", function(file, response) {
                    uploadedVideos.push(response.filePath); // Store video paths
                    console.log("Video uploaded:", response);
                });

                this.on("removedfile", function(file) {
                    // Handle video deletion
                    fetch("delete.php", {
                        method: "POST",
                        body: JSON.stringify({ filePath: file.name }),
                        headers: {
                            "Content-Type": "application/json"
                        }
                    }).then(response => response.json())
                      .then(data => {
                          console.log("Video deleted:", data);
                      });
                });
            }
        };

        // Initialize Dropzone for Floor Plans
        Dropzone.options.floorPlansUpload = {
            url: "upload.php",
            paramName: "image",
            maxFilesize: 5, // 5MB limit
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictRemoveFile: "Remove",
            init: function() {
                let uploadedFiles = [];

                this.on("success", function(file, response) {
                    uploadedFiles.push(response.filePath); // Store file paths
                    console.log("File uploaded:", response);
                });
            }
        };
    </script>
</body>

</html>