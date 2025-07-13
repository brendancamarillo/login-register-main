<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

require_once "database.php";
$error = "";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Secure query using prepared statement
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user["password"])) {
            // Store full user info (ID & email)
            $_SESSION["user"] = [
                "id" => $user["id"],
                "email" => $user["email"]
            ];
            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not found.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Login | MCGI Guest Coordinator</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" type="image/jpg" href="gco.jpg">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg-color: #0e1a2b;
      --text-color: #fff;
      --card-bg: #152a40;
      --input-bg: transparent;
      --input-border: #4b6b8a;
      --label-color: #aaa;
      --btn-bg: #00bcd4;
      --btn-text: #fff;
    }

    body.light-mode {
      --bg-color: #f4f7fb;
      --text-color: #111;
      --card-bg: #fff;
      --input-bg: transparent;
      --input-border: #ccc;
      --label-color: #777;
      --btn-bg: #111;
      --btn-text: #fff;
    }

    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--bg-color);
      color: var(--text-color);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .theme-toggle {
      position: fixed;
      top: 20px;
      right: 20px;
      background: transparent;
      border: 2px solid var(--text-color);
      color: var(--text-color);
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
      cursor: pointer;
      z-index: 10;
    }

    .container {
      display: flex;
      width: 100%;
      max-width: 960px;
      flex-direction: row;
      justify-content: space-between;
      gap: 40px;
      z-index: 10;
    }

    .left-section {
      flex: 1;
      padding: 20px;
    }

    .left-section img {
      width: 100px;
      margin-bottom: 20px;
      border-radius: 12px;
    }

    .left-section h1 {
      font-family: 'Poppins', sans-serif;
      font-weight: bold;
      font-size: 24px;
      margin-bottom: 10px;
    }

    .right-section {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background: var(--card-bg);
      padding: 30px;
      border-radius: 10px;
      width: 100%;
      max-width: 360px;
      box-shadow: 0 0 12px rgba(255, 255, 255, 0.05);
      transition: background 0.3s ease, color 0.3s ease;
    }

    .login-card input {
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 6px;
      padding: 12px;
      width: 100%;
      color: var(--text-color);
      margin-bottom: 15px;
      font-size: 14px;
      transition: all 0.2s ease;
    }

    .login-card button {
      width: 100%;
      background: var(--btn-bg);
      color: var(--btn-text);
      border: none;
      padding: 12px;
      border-radius: 6px;
      font-size: 15px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .login-card a {
      text-align: center;
      display: block;
      margin-top: 12px;
      font-size: 14px;
      text-decoration: none;
      color: var(--text-color);
      opacity: 0.6;
    }

    svg#bg-wave {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: var(--svg-bg-start);
      background-image: linear-gradient(to bottom, var(--svg-bg-start), var(--svg-bg-end));
      z-index: 0;
      pointer-events: none;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        align-items: center;
        gap: 20px;
      }

      .left-section,
      .right-section {
        width: 100%;
        text-align: center;
        padding: 10px;
      }

      .left-section img {
        width: 80px;
        margin: 0 auto 15px auto;
      }

      .left-section h1 {
        font-size: 20px;
      }

      .login-card {
        max-width: 100%;
        padding: 20px;
      }
    }

    @media (max-width: 480px) {
      .theme-toggle {
        top: 10px;
        right: 10px;
        padding: 4px 10px;
        font-size: 12px;
      }

      .left-section h1 {
        font-size: 18px;
      }

      .login-card input,
      .login-card button {
        font-size: 13px;
        padding: 10px;
      }

      .login-card {
        padding: 15px;
      }
    }
  </style>
</head>
<body>

<button class="theme-toggle" onclick="toggleTheme()">🌙</button>

<div class="container">
  <div class="left-section">
    <img src="gco.jpg" alt="MCGI Logo" />
    <h1>MCGI Guest Coordinator</h1>
    <p>Monitoring Attendance</p>
  </div>
  <div class="right-section">
    <div class="login-card">
      <?php if ($error): ?>
        <script>
          Swal.fire({
            icon: "error",
            title: "Login Failed",
            text: "<?= htmlspecialchars($error) ?>"
          });
        </script>
      <?php endif; ?>

      <form method="POST">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login">Login</button>
      </form>
      <a href="registration.php">Not registered yet? Register Here</a>
    </div>
  </div>
</div>

<svg id="bg-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
  <defs>
    <linearGradient id="bg">
      <stop offset="0%" style="stop-color: var(--svg-stop1);" />
      <stop offset="50%" style="stop-color: var(--svg-stop2);" />
      <stop offset="100%" style="stop-color: var(--svg-stop3);" />
    </linearGradient>
    <path id="wave" fill="url(#bg)"
      d="M-363.852,502.589c0,0,236.988-41.997,505.475,0
         s371.981,38.998,575.971,0s293.985-39.278,505.474,5.859s493.475,48.368,716.963-4.995v560.106H-363.852V502.589z" />
  </defs>
  <g>
    <use xlink:href="#wave" opacity="0.3">
      <animateTransform attributeName="transform" type="translate" dur="10s"
        values="270 230; -334 180; 270 230" repeatCount="indefinite" />
    </use>
    <use xlink:href="#wave" opacity="0.6">
      <animateTransform attributeName="transform" type="translate" dur="8s"
        values="-270 230;243 220;-270 230" repeatCount="indefinite" />
    </use>
    <use xlink:href="#wave" opacity="0.9">
      <animateTransform attributeName="transform" type="translate" dur="6s"
        values="0 230;-140 200;0 230" repeatCount="indefinite" />
    </use>
  </g>
</svg>

<script>
  function updateSvgColors(isLight) {
    const stops = document.querySelectorAll("#bg stop");
    stops[0].style.stopColor = isLight ? "rgba(200, 200, 250, 0.2)" : "rgba(130, 158, 249, 0.06)";
    stops[1].style.stopColor = isLight ? "rgba(150, 180, 255, 0.8)" : "rgba(76, 190, 255, 0.6)";
    stops[2].style.stopColor = isLight ? "rgba(115, 209, 72, 0.4)" : "rgba(115, 209, 72, 0.2)";
    document.getElementById("bg-wave").style.backgroundImage = isLight
      ? "linear-gradient(to bottom, #e0e7ff, #fff)"
      : "linear-gradient(to bottom, rgba(14, 65, 102, 0.86), #0e4166)";
  }

  function toggleTheme() {
    document.body.classList.toggle("light-mode");
    const isLight = document.body.classList.contains("light-mode");
    localStorage.setItem("theme", isLight ? "light" : "dark");
    updateSvgColors(isLight);
    updateToggleIcon(isLight);
  }

  function updateToggleIcon(isLight) {
    const btn = document.querySelector('.theme-toggle');
    btn.textContent = isLight ? "🌙" : "☀️";
  }

  window.addEventListener("DOMContentLoaded", () => {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "light") {
      document.body.classList.add("light-mode");
      updateSvgColors(true);
      updateToggleIcon(true);
    } else {
      updateSvgColors(false);
      updateToggleIcon(false);
    }
  });
</script>

</body>
</html>
