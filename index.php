<?php
session_start();
include "config/db.php";
include "includes/header.php";
$result = $conn->query("SELECT DISTINCT type, description FROM subscriptions");

$subscriptions = [];
while ($row = $result->fetch_assoc()) {
    $subscriptions[] = $row;
}
?>

<style> 
    .hero{
        background-image: url("/assets/images/banner.png");
    }
    .map-container {
        text-align: center;
        margin: 2rem auto;
        max-width: 800px;
        padding: 0 20px;
    }

    .map-container iframe {
        width: 100%;
        height: 450px;
        border: 2px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<body>

<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-text">
        <h2>Vrei să faci o schimbare?</h2>
    </div>
</section>


<h2 class='subtitle'>Abonamente disponibile</h2>

<div class="subscriptions-container">
    <?php foreach ($subscriptions as $sub): ?>
        <div class="subscription-card">
            <h3><?php echo htmlspecialchars($sub['type']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($sub['description'])); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<h2 class='subtitle'>Clase disponibile</h2>
<div class="subscriptions-container">
<?php
$result = $conn->query("SELECT name, trainer_name, description FROM classes");

while ($row = $result->fetch_assoc()):

    $imgName = strtolower(str_replace(' ', '', $row['name'])) . '.jpg';
    $bgImage = "/assets/images/" . $imgName;
?>
    <div class="subscription-card class-card-bg"
         style="background-image:
         linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
         url('<?php echo $bgImage; ?>');">

        <h3><?php echo htmlspecialchars($row['name']); ?></h3>

        <p style="color:white" class="trainer-name">
            Antrenor: <?php echo htmlspecialchars($row['trainer_name']); ?>
            <p style="color:white"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        </p>
    </div>
<?php endwhile; ?>
</div>

<h2 class='subtitle'>Antrenori</h2>
<div class="subscriptions-container">
<?php
$result = $conn->query("
    SELECT DISTINCT trainer_name
    FROM classes
");

while ($row = $result->fetch_assoc()):

    $imgName = strtolower(str_replace(' ', '', $row['trainer_name'])) . '.jpg';
    $bgImage = "/assets/images/" . $imgName;
?>
    <div class="subscription-card trainer-card-bg"
         style="background-image:
         linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
         url('<?php echo $bgImage; ?>');">

        <div class="trainer-name-bottom">
            <?php echo htmlspecialchars($row['trainer_name']); ?>
        </div>
    </div>
<?php endwhile; ?>
</div>
<div style="max-width:560px; margin: 20px auto 0 auto; text-align:center;">
    <h2 class='subtitle'>Antrenament De Încercat</h2>
    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <iframe 
            src="https://www.youtube.com/embed/6vlP9xPJbaQ" 
            title="Antrenamentul Saptamanii" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen
            style="position: absolute; top:0; left:0; width:100%; height:100%; border-radius:12px;">
        </iframe>
    </div>
</div>

<div class="map-container">
    <h2 class='subtitle'>Locația GOLD</h2>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2847.43702452095!2d26.087198599999997!3d44.465213999999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b2020fb53b21f9%3A0x9d2d0fd13928264d!2sCharles%20de%20Gaulle%20Plaza!5e0!3m2!1sen!2sro!4v1768501408149!5m2!1sen!2sro" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
</body>

<?php
include "includes/footer.php";
?>




