<?php
require '../vendor/autoload.php';

//template view
$loader = new Twig_Loader_Filesystem('../views');
$twig = new Twig_Environment($loader, [
    'cache' => false /* '../tmp',*/
]);


//routing
$page = 'home';
if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

switch ($page) {
    case 'home':
        echo $twig->render('home.twig');
        break;
    case 'blog':
        echo $twig->render('blog.twig', ['posts'=>posts()]);
        break;
    case 'register':
        echo $twig->render('register.twig');
        break;
    case 'connexion':
        echo $twig->render('connexion.twig');
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        break;
}


// Connexion à la base de données
function posts()
{
    $bdd = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $posts = $bdd->query('SELECT * FROM posts ORDER BY id DESC LIMIT 10');
    return $posts;
}

