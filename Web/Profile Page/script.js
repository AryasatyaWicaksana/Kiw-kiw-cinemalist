// Function to preview the uploaded profile photo
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('previewImage');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Attach event listener to the file input for real-time preview
document.getElementById('profilePhoto').addEventListener('change', previewImage);
