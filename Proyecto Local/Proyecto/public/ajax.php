<?php
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', 'admin');
define('DB_DATABASE', 'loopz');

$connexion = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

$html = '';
$key = $_POST['key'];
$numero=1;

if(isset($_POST['compuesta'])){
    $prefijo="... ";
}
else{
    $prefijo="";
}

$result = $connexion->query(
    'SELECT DISTINCT keyword FROM keywords 
    WHERE keyword LIKE "%'.strip_tags($key).'%"
    LIMIT 0,5'
);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        $html .= '<div><a class="suggest-element"  id="keyword'.$numero.'">'.$prefijo.utf8_encode($row['keyword']).'</a></div>';
        $numero++;
    }
}
echo $html;
?>