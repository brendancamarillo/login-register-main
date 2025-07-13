<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require_once "database.php";

$user_id = $_SESSION["user"]["id"];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

$message = "";

if (isset($_POST['tap_attendance'])) {
    $date = date('Y-m-d');
    $check = mysqli_query($conn, "SELECT * FROM attendance WHERE user_id = '$user_id' AND date = '$date'");
    if (mysqli_num_rows($check) > 0) {
        $message = "<script>Swal.fire('Notice', 'Attendance already recorded today.', 'warning');</script>";
    } else {
        $time_in = date('H:i:s');
        $insert = mysqli_query($conn, "INSERT INTO attendance (user_id, date, time_in) VALUES ('$user_id', '$date', '$time_in')");
        if ($insert) {
            $message = "<script>Swal.fire('Success', 'Attendance recorded successfully!', 'success');</script>";
        } else {
            $message = "<script>Swal.fire('Error', 'Error recording attendance.', 'error');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard | MCGI Guest Coordinator</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" href="gco.jpg" type="image/jpg" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.css">
  <style>
    :root {
      --svg-stop1: rgba(130, 158, 249, 0.06);
      --svg-stop2: rgba(76, 190, 255, 0.6);
      --svg-stop3: rgba(115, 209, 72, 0.2);
    }

    body.light-mode {
      --svg-stop1: rgba(200, 200, 250, 0.2);
      --svg-stop2: rgba(150, 180, 255, 0.8);
      --svg-stop3: rgba(115, 209, 72, 0.4);
    }

    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #0e1a2b;
      color: white;
      display: flex;
      transition: all 0.3s ease;
      overflow-x: hidden;
    }

    body.light-mode {
      background-color: #f4f7fb;
      color: #111;
    }

    .sidebar {
      width: 250px;
      min-height: 100vh;
      background-color: #172b45;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 5;
      transition: transform 0.3s ease;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 10px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.3s ease;
    }

    .sidebar a:hover {
      background-color: #00bcd4;
    }

    body.light-mode .sidebar {
      background-color: #fff;
    }

    body.light-mode .sidebar a {
      color: #111;
    }

    .sidebar .bottom-menu {
      margin-top: auto;
    }

    .theme-toggle {
      background: transparent;
      border: 2px solid currentColor;
      padding: 6px 12px;
      border-radius: 20px;
      cursor: pointer;
      width: 100%;
      text-align: center;
      margin-top: 10px;
    }

    .main-content {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 40px;
    }

    .container {
      background-color: rgba(21, 42, 64, 0.95);
      padding: 40px;
      border-radius: 20px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 0 12px rgba(255, 255, 255, 0.08);
      z-index: 2;
    }

    body.light-mode .container {
      background-color: #ffffff;
      color: #111;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .profile-info img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #00bcd4;
    }

    .no-image {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: #ccc;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #333;
      font-weight: bold;
    }

    .logout {
      color: red;
      text-decoration: none;
      font-weight: bold;
    }

    .btn-primary {
      background-color: #00bcd4;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      color: white;
      cursor: pointer;
    }

    .btn-primary:hover {
      background-color: #0097a7;
    }

    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
      background-color: transparent;
    }

    table th, table td {
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 10px;
    }

    .light-mode table th, .light-mode table td {
      border-color: #ccc;
    }

    #bg-wave {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0;
      pointer-events: none;
    }

    @media screen and (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main-content {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <a href="#"><span data-lucide="layout-dashboard"></span> Dashboard</a>
    <a href="calendar.php"><span data-lucide="calendar"></span> Calendar</a>
    <a href="#"><span data-lucide="folder"></span> Documents</a>
    <a href="profile.php"><span data-lucide="user"></span> My Profile</a>
    <div class="bottom-menu">
      <a href="logout.php"><span data-lucide="log-out"></span> Logout</a>
      <button class="theme-toggle" onclick="toggleTheme()">🌙 Toggle Theme</button>
    </div>
  </div>

  <div class="main-content">
    <div class="container">
      <h1>Welcome!</h1>
      <div class="profile-info">
        <?php
        $imagePath = 'uploads/' . htmlspecialchars($user['profile_picture']);
        if (!empty($user['profile_picture']) && file_exists($imagePath)) {
            echo '<img src="' . $imagePath . '" alt="Profile Picture">';
        } else {
            echo '<div class="no-image">No Image</div>';
        }
        ?>
        <div>
          <h2><?= htmlspecialchars($user['full_name']) ?></h2>
          <p><?= htmlspecialchars($user['email']) ?></p>
        </div>
      </div>

      <form method="POST">
        <button type="submit" name="tap_attendance" class="btn-primary">Tap Attendance</button>
      </form>

      <?= $message ?>

      <h3 style="margin-top: 30px;">Your Attendance Records</h3>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Time In</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = mysqli_query($conn, "SELECT * FROM attendance WHERE user_id = '$user_id' ORDER BY date DESC");
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['date'] . "</td><td>" . $row['time_in'] . "</td></tr>";
          }
          ?>
        </tbody>
      </table>
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

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();

    function toggleTheme() {
      const isLight = document.body.classList.toggle("light-mode");
      localStorage.setItem("theme", isLight ? "light" : "dark");
    }

    window.addEventListener("DOMContentLoaded", () => {
      const savedTheme = localStorage.getItem("theme");
      if (savedTheme === "light") {
        document.body.classList.add("light-mode");
      }
    });
  </script>
</body>
</html>
