// Register
document.getElementById('signupBtn').addEventListener('click', async () => {
  const name = document.getElementById('signupName').value.trim();
  const email= document.getElementById('signupEmail').value.trim();
  const pass = document.getElementById('signupPass').value.trim();
  const res = await fetch('php/register.php', {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({name,email,password:pass})
  });
  const data = await res.json();
  document.getElementById('signupMsg').innerText = data.message;
});

// Login
document.getElementById('loginBtn').addEventListener('click', async () => {
  const email= document.getElementById('loginEmail').value.trim();
  const pass = document.getElementById('loginPass').value.trim();
  const res = await fetch('php/login.php', {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({email,password:pass})
  });
  const data = await res.json();
  document.getElementById('loginMsg').innerText = data.message;
  if (data.success) {
    // redirect based on role
    if (data.role === 'admin') window.location = 'admin_dashboard.php';
    else window.location = 'user_dashboard.php';
  }
});
