<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";
$page = basename($_SERVER['PHP_SELF']);

$stmt = $conn->prepare(
    "INSERT INTO site_stats (page, visits) VALUES (?,1)
     ON DUPLICATE KEY UPDATE visits = visits + 1"
);
$stmt->bind_param("s", $page);
$stmt->execute();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . "/PHPMailer/src/Exception.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PHPMailer/src/PHPMailer.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PHPMailer/src/SMTP.php";

$success_message = '';
$error_message = '';

if (isset($_POST['send'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $recaptcha_secret = '6LcNuUssAAAAACeurNyJCsh2YNieouCq7qdIlQsL'; 
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    if(intval($response_keys["success"]) !== 1) {
        $error_message = "Te rugăm să bifezi reCAPTCHA.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $name, $email, $message);
        if($stmt->execute()) {

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alexandrabalann420@gmail.com'; 
                $mail->Password = 'faag oojo xdpg sdxr';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('alexandrabalann420@gmail.com', 'Site-ul meu'); 
                $mail->addReplyTo($email, $name); 
                $mail->addAddress('alexandrabalann420@gmail.com'); 

                $mail->isHTML(true);
                $mail->Subject = 'Mesaj din formular contact';
                $mail->Body = nl2br($message);

                $mail->send();

                $success_message = "Mesaj trimis cu succes!";
            } catch (Exception $e) {
                $error_message = "Mesajul nu a putut fi trimis. Eroare: {$mail->ErrorInfo}";
            }

        } else {
            $error_message = "Eroare la salvarea mesajului în baza de date.";
        }
    }
}
?>

<style>
h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 36px;
    color: #333;
}

form {
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}

form input[type="text"],
form input[type="email"],
form textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    box-sizing: border-box;
}

form textarea {
    height: 120px;
    resize: vertical;
}

.success-message {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
    color: green;
}

.error-message {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
    color: red;
}
</style>

<h2>Contact</h2>

<?php
if($success_message) {
    echo "<p class='success-message'>$success_message</p>";
}
if($error_message) {
    echo "<p class='error-message'>$error_message</p>";
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Nume" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <textarea name="message" placeholder="Mesaj" required></textarea><br>

    <div class="g-recaptcha" data-sitekey="6LcNuUssAAAAACnY0o0P5LErqe9UlEq7-AKSuhwF"></div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <button name="send">Trimite</button>
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
