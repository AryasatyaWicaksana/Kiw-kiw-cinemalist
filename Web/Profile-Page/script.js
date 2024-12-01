const currentProfile = document.getElementById('current-profile');
const images = document.querySelectorAll('.images-container img');

images.forEach(image => {
    image.addEventListener('click', () => {
        const newProfilePic = image.getAttribute('data-image');
        currentProfile.src = `../Assets/profile-img/${newProfilePic}`;

        fetch('../Profile-Page/profile_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ profilePicture: newProfilePic })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.message === 'Profile picture updated successfully') {
                console.log(data.message);
            } else {
                console.error("Server error:", data.message);
            }
        })
        .catch(error => {
            console.error('Error updating profile picture:', error);
        });
    });
});