<?php
include "titres.php";


if (!isset($_GET["include"])) {
    //echo "Vous avez interrompu votre lecture. A bientôt...";
    echo "Il manque le titre";
    exit();
}
$titre = $titres[$_GET["include"]];

?>


<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
        <?php echo $titre; ?>
    </title>
    <link rel="stylesheet" type="text/css" href="css/base.css" />
</head>
<body>

<!-- Le titre, les menus, la sidebar  -->
<header>
    <!-- le titre et sous titre -->
    <div id="title">
        <h1>MOBILISEZ-VOUS</h1>
        <h2>''suivez en temps réel la fréquentation de votre bureau de vote''</h2>
    </div>
    <!-- les menus -->
    <ul id="menu">
        <li class="home"><a href="index.html"> <img class="home" src="images/home.svg" /> </a></li>
        <li><a href="frequentation.html">FREQUENTATION</a></li>
        <li><a href="affluence.html">AFFLUENCE</a></li>
        <li><a href="contribuez.html">CONTRIBUEZ</a></li>
        <li><a href="apropos.html">A PROPOS</a></li>
    </ul>
    <img id="bandeau1" src="images/triangle1.svg">
    <img id="bandeau2" src="images/triangle2.svg">
</header>

    <!-- la sidebar -->


<div id="main">

<?php
switch ($_GET["include"]) {
    case "accueil":
        include ("accueil.php");
        break;
    case "frequentation":
         include ("frequentation.php");
        break;
    case  "affluence":
         include ("affluence.php");
        break;
    case  "contribuez":
         include ("contribuez.php");
        break;
    case  "apropos":
         include ("apropos.php");
        break;
}
?>

</div>
<!--[if IE]>
<div id="ie">
Chrome, Firefox ou même Safari mais par pitiée, pas Internet Explorer<br>
<a href="https://www.google.com/intl/fr_fr/chrome/browser/">Installer Chorme</a><br>
<a href="http://www.mozilla.org/fr/firefox/new/">Istaller Firefox</a><br>
</div>
<![endif]-->
<footer>

</footer>

</body>
</html>