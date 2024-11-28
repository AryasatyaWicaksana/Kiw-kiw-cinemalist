<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/Logo kiw-kiw.png">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Profile</h1>
        <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
            <!-- Profile Photo -->
            <div class="mb-4 text-center">
                <label for="profilePhoto" class="form-label">Profile Photo</label>
                <div>
                    <img id="previewImage" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" 
                         alt="Profile Preview" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <input type="file" class="form-control mt-2" id="profilePhoto" name="profilePhoto" accept="image/*">
            </div>
            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="">
            </div>
            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter a new password">
            </div>
            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your new password">
            </div>
            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
