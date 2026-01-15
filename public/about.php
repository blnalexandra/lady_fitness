<style>
    h2 {
        text-align: center;
        color: #000000;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }
    h1{
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 2.5rem;
    }

    p {
        text-align: center;
        color: #555;
        font-size: 1.1rem;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .container {
            padding: 20px;
        }
        
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
</style>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";

$page = basename($_SERVER['PHP_SELF']);
$stmt = $conn->prepare("
    INSERT INTO site_stats (page, visits)
    VALUES (?, 1)
    ON DUPLICATE KEY UPDATE visits = visits + 1
");
$stmt->bind_param("s", $page);
$stmt->execute();
?>
<h1>Despre Pagina Web</h1>
<p>Acest site este conceput pentru a gestiona o sala de fitness dedicata femeilor. 
Utilizatorii se pot autentifica, gestiona abonamentul si inscrie la diferite clase.
Adminul paginii poate vedea diferite statistici ale site-ului si poate gestiona utilizatorii si clasele.
Acesta este un proiect scolar!</p>
<h2>Diagrama entitate - relatie </h2>
 <div class="container">
    <img src="/assets/images/diagrama.png" alt="Diagrama">
</div>
<h2>Entitati principale</h2>
<p>
<ul>
  <li><b>user</b> - stocheaza utilizatorii platformei (nume, email, parola, rol, data creare)</li>
  <li><b>classes</b> - contine clasele disponibile (nume, instructor, descriere, data, ora, capacitate)</li>
  <li><b>class_registrations</b> - leaga utilizatorii de clasele la care s-au înscris (relatie many-to-many intre users si classes)</li>
  <li><b>subscriptions</b> - gestioneaza abonamentele utilizatorilor (tip abonament, descriere)</li>
  <li><b>site_stats</b> - statistici despre site (pagini vizitate, numar vizite, ultima vizita)</li>
  <li><b>contact_messages</b> - mesajele trimise prin formularul de contact (nume, email, mesaj, data)</li>
</ul>
</p>
<h2>Procese Principale</h2>
<p>
<ul>
  <li><b>Rezervare la curs: </b> Utilizatorul autentificat selectează un curs - sistemul verifică capacitatea - se creează rezervarea.</li>
  <li><b>Creare curs: </b>Administratorul completează detaliile cursului - sistemul validează și salvează - cursul devine vizibil membrilor.</li>
  <li><b>Gestionare abonament:</b>Utilizatorul selectează tipul de abonament - sistemul creează abonamentul.</li>
</ul>
</p>
<h2>Arhitectura aplicației</h2>
<p>Aplicația este construită pe o arhitectură de tip client–server, utilizând limbajul PHP pentru logica de server și MySQL pentru gestionarea datelor persistente. Interfața utilizator este realizată folosind HTML și CSS.</p>
<h2>Roluri principale</h2>
<p>
<ul>
  <li><b>Utilizator (client): </b> poate crea un cont, se poate autentifica, își poate gestiona abonamentul și se poate înscrie la clase.</li>
  <li><b>Administrator: </b>poate gestiona clasele de fitness, poate vizualiza statistici despre site și poate administra utilizatorii.</li>
</ul>
</p>
<h2>Componente principale</h2>
<p>
<ul>
  <li><b>Interfața web: </b> pagini dinamice care permit interacțiunea utilizatorului cu aplicația.</li>
  <li><b>Logica aplicației (PHP): </b>gestionează autentificarea, validarea datelor, procesarea cererilor și securitatea.</li>
  <li><b>Baza de date MySQL: </b>stochează informațiile despre utilizatori, clase, abonamente și statistici.</li>
  <li><b>Componenta de email: </b>utilizată pentru trimiterea mesajelor din formularul de contact prin PHPMailer și SMTP.</li>
</ul>
</p>
<h2>Descrierea soluției de implementare</h2>
<p>Implementarea aplicației s-a realizat utilizând PHP și MySQL, cu accent pe securitate și modularitate. Comunicarea cu baza de date se face exclusiv prin prepared statements, prevenind atacurile de tip SQL Injection. Afișarea datelor este protejată împotriva atacurilor XSS prin utilizarea funcției htmlspecialchars().

Aplicația permite operațiuni CRUD pentru toate entitățile principale și este concepută astfel încât să poată fi extinsă ușor cu funcționalități suplimentare.</p>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>