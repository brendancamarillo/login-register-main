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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile | MCGI Guest Coordinator</title>
  <link rel="icon" href="gco.jpg" type="image/jpg" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      position: relative;
      z-index: 2;
    }
    .container {
      background-color: rgba(21, 42, 64, 0.95);
      padding: 30px;
      border-radius: 16px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 0 12px rgba(255, 255, 255, 0.08);
    }
    body.light-mode .container {
      background-color: #ffffff;
      color: #111;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }
    .profile-info {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      margin-bottom: 30px;
    }
    .profile-info img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #00bcd4;
    }
    .section {
      margin-bottom: 30px;
    }
    .section h3 {
      margin-bottom: 10px;
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
    }
    .info-item {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      padding: 10px;
      border-radius: 6px;
    }
    .info-item strong {
      font-size: 12px;
      color: #aaa;
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
  </style>
</head>
<body>
  <div class="sidebar">
    <a href="index.php"><span data-lucide="layout-dashboard"></span> Dashboard</a>
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
      <div class="profile-info">
        <?php
        $imagePath = 'uploads/' . htmlspecialchars($user['profile_picture']);
        if (!empty($user['profile_picture']) && file_exists($imagePath)) {
            echo '<img src="' . $imagePath . '" alt="Profile Picture">';
        } else {
            echo '<div class="no-image">No Image</div>';
        }
        ?>
        <h2><?= htmlspecialchars($user['full_name']) ?></h2>
        <p><?= htmlspecialchars($user['email']) ?></p>
      </div>

      <?php
      $sections = [
        'Personal Info' => [
          'full_name' => 'Full Name', 'email' => 'Email', 'church_id' => 'Church ID', 'old_church_id' => 'Old Church ID',
          'gender' => 'Gender', 'civil_status' => 'Civil Status', 'birthdate' => 'Birthdate', 'birthplace' => 'Birthplace',
          'blood_type' => 'Blood Type', 'citizenship' => 'Citizenship', 'ethnicity' => 'Ethnicity',
          'street_address' => 'Street Address', 'state_province_region' => 'State/Province/Region',
          'city_country_valley' => 'City/Valley', 'country' => 'Country', 'zipcode_postcode' => 'Zip Code'
        ],
        'Church Info' => [
          'member_type' => 'Member Type', 'baptism_date' => 'Baptism Date', 'baptism_place' => 'Baptism Place',
          'former_religions' => 'Former Religions', 'assisted_by' => 'Assisted By', 'indoctrinated_by' => 'Indoctrinated By',
          'baptized_by' => 'Baptized By'
        ],
        'Locale Info' => [
          'division' => 'Division', 'district' => 'District', 'zone' => 'Zone',
          'locale' => 'Locale', 'locale_group' => 'Locale Group', 'locale_group_joined_date' => 'Joined Date'
        ],
        'Social Media' => [
          'facebook_account' => 'Facebook', 'twitter_account' => 'Twitter', 'tiktok_account' => 'TikTok', 'instagram_account' => 'Instagram'
        ]
      ];
      foreach ($sections as $title => $fields):
      ?>
        <div class="section">
          <h3><?= $title ?></h3>
          <div class="info-grid">
            <?php foreach ($fields as $key => $label): ?>
            <div class="info-item">
              <strong><?= $label ?>:</strong>
              <?= htmlspecialchars($user[$key]) ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <svg id="bg-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
    <defs>
      <linearGradient id="bg">
        <stop offset="0%" style="stop-color: var(--svg-stop1);" />
        <stop offset="50%" style="stop-color: var(--svg-stop2);" />
        <stop offset="100%" style="stop-color: var(--svg-stop3);" />
      </linearGradient>
      <path id="wave" fill="url(#bg)" d="M-363.852,502.589c0,0,236.988-41.997,505.475,0
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