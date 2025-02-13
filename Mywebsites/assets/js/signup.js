document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("signupForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        let formData = new FormData(this);

        fetch("signup.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let messageBox = document.getElementById("message");
            if (data.status === "success") {
                messageBox.innerHTML = `<p style="color: green;">✅ ${data.message}</p>`;
                setTimeout(() => {
                    window.location.href = "signup.php"; // Redirect after success
                }, 2000);
            } else {
                messageBox.innerHTML = `<p style="color: red;">❌ ${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
});
