<style> 
    h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 36px;
    color: #333;
}

.rss-card {
    background-color: #e4dee3ff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

.rss-card h3 {
    margin-top: 0;
    font-size: 24px;
}

.rss-card p {
    margin: 10px 0;
    line-height: 1.5;
    color: #555;
}

.rss-card a {
    text-decoration: none;
    font-weight: bold;
}

.rss-card a:hover {
    text-decoration: underline;
}

hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 20px 0;
}
</style>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";
?>
<?php
$page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['visited_pages'][$page])) {
    $_SESSION['visited_pages'][$page] = true;

    $stmt = $conn->prepare("
        INSERT INTO site_stats (page, visits)
        VALUES (?, 1)
        ON DUPLICATE KEY UPDATE visits = visits + 1
    ");
    $stmt->bind_param("s", $page);
    $stmt->execute();
}
?>

<?php
$rss_url = "https://www.womenshealthmag.com/rss/fitness.xml";

$rss = simplexml_load_file($rss_url);

if (!$rss) {
    die("Nu se pot încărca sfaturile de sănătate.");
}

echo "<h2>Sfaturi de sănătate & fitness</h2>";

$count = 0;
foreach ($rss->channel->item as $item) {
    if ($count == 5) break;

    echo "<div class='rss-card'>";
    echo "<h3>" . htmlspecialchars($item->title) . "</h3>";
    echo "<p>" . htmlspecialchars(strip_tags($item->description)) . "</p>";
    echo "<a href='" . htmlspecialchars($item->link) . "' target='_blank'>Citește mai mult</a>";
    echo "</div><hr>";


    $count++;
}
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

