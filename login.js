document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  const statusMsg = document.getElementById("statusMsg");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!email || !password) {
      showMessage("Please enter email and password", "orange");
      return;
    }

    try {
      const response = await fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });

      const result = await response.json();
      if (result.success) {
        showMessage(result.message, "green");

        // Redirect based on role
        if (result.role === "admin") {
          window.location.href = "admin_dashboard.php";
        } else {
          window.location.href = "homepage.html";
        }
      } else {
        showMessage(result.message, "red");
      }
    } catch (error) {
      showMessage("Something went wrong", "red");
    }
  });

  function showMessage(msg, color) {
    statusMsg.textContent = msg;
    statusMsg.style.color = color;
    statusMsg.style.display = "block";
    setTimeout(() => {
      statusMsg.style.display = "none";
    }, 3000);
  }
});
