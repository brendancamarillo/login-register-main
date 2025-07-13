<?php 
require_once "database.php";
$error = "";
$profile_picture = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Account Info
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Personal Info
    $church_id = $_POST["church_id"];
    $old_church_id = $_POST["old_church_id"];
    $gender = $_POST["gender"];
    $civil_status = $_POST["civil_status"];
    $birthdate = $_POST["birthdate"];
    $birthplace = $_POST["birthplace"];
    $blood_type = $_POST["blood_type"];
    $citizenship = $_POST["citizenship"];
    $ethnicity = $_POST["ethnicity"];
    $street_address = $_POST["street_address"];
    $state_province_region = $_POST["state_province_region"];
    $city_country_valley = $_POST["city_country_valley"];
    $country = $_POST["country"];
    $zipcode_postcode = $_POST["zipcode_postcode"];

    // Contact Info
    $phone_number = $_POST["phone_number"];
    $instagram_account = $_POST["instagram_account"];

    // Church Info
    $member_type = $_POST["member_type"];
    $baptism_date = $_POST["baptism_date"];
    $baptism_place = $_POST["baptism_place"];
    $former_religions = $_POST["former_religions"];
    $assisted_by = $_POST["assisted_by"];
    $indoctrinated_by = $_POST["indoctrinated_by"];
    $baptized_by = $_POST["baptized_by"];

    // Locale Info
    $division = $_POST["division"];
    $district = $_POST["district"];
    $zone = $_POST["zone"];
    $locale = $_POST["locale"];
    $locale_group = $_POST["locale_group"];
    $locale_group_joined_date = $_POST["locale_group_joined_date"];

    // Social Media
    $facebook_account = $_POST["facebook_account"];
    $twitter_account = $_POST["twitter_account"];
    $tiktok_account = $_POST["tiktok_account"];

    // Profile Picture Upload
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        $tmp = $_FILES["profile_picture"]["tmp_name"];
        $filename = basename($_FILES["profile_picture"]["name"]);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif"];

        if (in_array($ext, $allowed)) {
            if (!is_dir("uploads")) {
                mkdir("uploads", 0755, true);
            }
            $new_name = "uploads/" . uniqid() . "." . $ext;
            if (move_uploaded_file($tmp, $new_name)) {
                $profile_picture = $new_name;
            } else {
                $error = "Failed to upload profile picture.";
            }
        } else {
            $error = "Invalid image format. Only JPG, PNG, or GIF allowed.";
        }
    }

    // Password validation & DB insert
    if (!$error) {
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $res = mysqli_query($conn, "SELECT 1 FROM users WHERE email='$email'");
            if (mysqli_num_rows($res)) {
                $error = "Email already registered.";
            } else {
                $sql = "INSERT INTO users 
                (full_name,email,password,church_id,old_church_id,gender,civil_status,birthdate,birthplace,blood_type,citizenship,ethnicity,street_address,state_province_region,city_country_valley,country,zipcode_postcode,phone_number,instagram_account,member_type,baptism_date,baptism_place,former_religions,assisted_by,indoctrinated_by,baptized_by,division,district,zone,locale,locale_group,locale_group_joined_date,facebook_account,twitter_account,tiktok_account,profile_picture)
                VALUES
                ('$full_name','$email','$hashed','$church_id','$old_church_id','$gender','$civil_status','$birthdate','$birthplace','$blood_type','$citizenship','$ethnicity','$street_address','$state_province_region','$city_country_valley','$country','$zipcode_postcode','$phone_number','$instagram_account','$member_type','$baptism_date','$baptism_place','$former_religions','$assisted_by','$indoctrinated_by','$baptized_by','$division','$district','$zone','$locale','$locale_group','$locale_group_joined_date','$facebook_account','$twitter_account','$tiktok_account','$profile_picture')";
                if (mysqli_query($conn, $sql)) {
                    header("Location: login.php");
                    exit;
                } else {
                    $error = "Registration failed.";
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register | MCGI Guest Coordinator</title>
  <link rel="icon" href="gco.jpg" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root {--bg:#0e1a2b;--text:#fff;--card:#152a40;--input-bg:transparent;--border:#4b6b8a;--label:#aaa;--btn:#00bcd4;--btn-text:#fff;}
    body.light-mode {--bg:#f4f7fb;--text:#111;--card:#fff;--border:#ccc;--label:#777;--btn:#111;--btn-text:#fff;}
    body {font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);margin:0;padding:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:60px 20px;transition:0.3s;}
    .theme-toggle {position:fixed;top:20px;right:20px;padding:6px 14px;font-size:14px;border:1px solid var(--text);background:transparent;color:var(--text);border-radius:20px;cursor:pointer;z-index:99;}
    .card {background:var(--card);padding:40px;border-radius:16px;width:100%;max-width:960px;box-shadow:0 0 20px rgba(0,0,0,0.2);z-index:1;}
    h1 {text-align:center;margin-bottom:30px;font-weight:600;}
    .form-section {margin-bottom:30px;}
    .form-section h3 {font-size:18px;margin-bottom:16px;border-bottom:1px solid var(--border);padding-bottom:6px;color:var(--label);}
    .grid {display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;}
    .form-group {position:relative;}
    .form-group input, .form-group select {width:100%;padding:12px 1px 12px 10px;font-size:14px;background:var(--input-bg);border:1px solid var(--border);border-radius:6px;color:var(--text);transition:0.3s;}
    .form-group label {position:absolute;left:12px;top:12px;font-size:13px;color:var(--label);background:var(--card);padding:0 5px;pointer-events:none;transition:0.2s;}
    .form-group input:focus + label, .form-group input:not(:placeholder-shown) + label,
    .form-group select:focus + label, .form-group select:not([value=""])+label {top:-8px;left:10px;font-size:11px;color:var(--btn);}
    button[type=submit]{width:100%;padding:14px;font-size:16px;background:var(--btn);color:var(--btn-text);border:none;border-radius:8px;cursor:pointer;transition:0.3s;font-weight:600;}
    button[type=submit]:hover{opacity:0.9;}
    .login-link{text-align:center;margin-top:20px;font-size:14px;}
    .login-link a{color:var(--btn);text-decoration:none;}
    svg#bg-wave{position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:0;pointer-events:none;}
  </style>
</head>
<body>
<button class="theme-toggle" onclick="toggleTheme()">🌙</button>
<div class="card">
  <h1>MCGI Create Account</h1>
  <?php if($error): ?>
    <script>Swal.fire({icon:"error",title:"Oops...",text:"<?=htmlspecialchars($error)?>"});</script>
  <?php endif; ?>
  <form method="POST">
    <div class="form-section"><h3>Personal Info</h3><div class="grid">
      <?php foreach([
        ['text','full_name','Full Name'],['email','email','Email'],
        ['text','church_id','Church ID'],['text','old_church_id','Old Church ID'],
        ['text','gender','Gender'],['text','civil_status','Civil Status'],
        ['date','birthdate','Birthdate'],['text','birthplace','Birthplace'],
        ['text','blood_type','Blood Type'],['text','citizenship','Citizenship'],
        ['text','ethnicity','Ethnicity'],['text','street_address','Street Address'],
        ['text','state_province_region','State/Province/Region'],['text','city_country_valley','City/Valley'],
        ['text','country','Country'],['text','zipcode_postcode','Zip/Post Code']
      ] as $f): ?>
      <div class="form-group">
        <input type="<?= $f[0] ?>" name="<?= $f[1] ?>" placeholder=" " required />
        <label><?= $f[2] ?></label>
      </div><?php endforeach; ?>
    </div></div>

    <div class="form-group">
  <input type="file" name="profile_picture" accept="image/*" />
  <label style="top:-8px; font-size:11px;">Upload Profile Picture</label>
</div>

    <div class="form-section"><h3>Contact Info</h3><div class="grid">
      <?php foreach([
        ['text','phone_number','Phone Number'],
        ['text','instagram_account','Instagram']
      ] as $f): ?>
      <div class="form-group">
        <input type="<?= $f[0] ?>" name="<?= $f[1] ?>" placeholder=" " required />
        <label><?= $f[2] ?></label>
      </div><?php endforeach; ?>
    </div></div>

    <div class="form-section"><h3>Church Info</h3><div class="grid">
      <?php foreach([
        ['text','member_type','Member Type'],
        ['date','baptism_date','Baptism Date'],
        ['text','baptism_place','Baptism Place'],
        ['text','former_religions','Former Religions'],
        ['text','assisted_by','Assisted By'],
        ['text','indoctrinated_by','Indoctrinated By'],
        ['text','baptized_by','Baptized By']
      ] as $f): ?>
      <div class="form-group">
        <input type="<?= $f[0] ?>" name="<?= $f[1] ?>" placeholder=" " />
        <label><?= $f[2] ?></label>
      </div><?php endforeach; ?>
    </div></div>

    <div class="form-section"><h3>Locale Info</h3><div class="grid">
      <?php foreach([
        ['text','division','Division'],['text','district','District'],
        ['text','zone','Zone'],['text','locale','Locale'],
        ['text','locale_group','Locale Group'],['date','locale_group_joined_date','Locale Group Joined Date']
      ] as $f): ?>
      <div class="form-group">
        <input type="<?= $f[0] ?>" name="<?= $f[1] ?>" placeholder=" " />
        <label><?= $f[2] ?></label>
      </div><?php endforeach; ?>
    </div></div>

    <div class="form-section"><h3>Social Media</h3><div class="grid">
      <?php foreach([
        ['text','facebook_account','Facebook'],
        ['text','twitter_account','Twitter'],
        ['text','tiktok_account','TikTok']
      ] as $f): ?>
      <div class="form-group">
        <input type="<?= $f[0] ?>" name="<?= $f[1] ?>" placeholder=" " />
        <label><?= $f[2] ?></label>
      </div><?php endforeach; ?>
    </div></div>

    <div class="form-section"><h3>Account Security</h3><div class="grid">
      <?php foreach([
        ['password','password','Password'],['password','confirm_password','Confirm Password']
      ] as $f): ?>
      <div class="form-group">
        <input type="<?= $f[0] ?>" name="<?= $f[1] ?>" placeholder=" " required />
        <label><?= $f[2] ?></label>
      </div><?php endforeach; ?>
    </div></div>

    <button type="submit">Register</button>
    <div class="login-link">Already registered? <a href="login.php">Login here</a></div>
  </form>
</div>

<svg id="bg-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
  <defs><linearGradient id="bg"><stop offset="0%" stop-color="rgba(130,158,249,0.06)" /><stop offset="50%" stop-color="rgba(76,190,255,0.6)" /><stop offset="100%" stop-color="rgba(115,209,72,0.2)" /></linearGradient><path id="wave" fill="url(#bg)" d="M-363.852,502.589c0,0,236.988-41.997,505.475,0s371.981,38.998,575.971,0s293.985-39.278,505.474,5.859s493.475,48.368,716.963-4.995v560.106H-363.852V502.589z"/></defs>
  <g><use xlink:href="#wave" opacity="0.3"><animateTransform attributeName="transform" type="translate" dur="10s" values="270 230; -334 180; 270 230" repeatCount="indefinite"/></use><use xlink:href="#wave" opacity="0.6"><animateTransform attributeName="transform" type="translate" dur="8s" values="-270 230;243 220;-270 230" repeatCount="indefinite"/></use><use xlink:href="#wave" opacity="0.9"><animateTransform attributeName="transform" type="translate" dur="6s" values="0 230;-140 200;0 230" repeatCount="indefinite"/></use></g>
</svg>

<script>
function toggleTheme(){
  const isLight = document.body.classList.toggle("light-mode");
  document.querySelector(".theme-toggle").textContent = isLight? "🌙":"☀️";
  localStorage.setItem("theme", isLight? "light":"dark");
}
window.addEventListener("DOMContentLoaded",()=>{
  if(localStorage.getItem("theme")==="light"){
    document.body.classList.add("light-mode");
    document.querySelector(".theme-toggle").textContent="🌙";
  }
});
</script>
</body>
</html>
