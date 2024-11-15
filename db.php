<?php
// Configuración de la base de datos
$servername = "by0pvku1jdod0itjfxl8-mysql.services.clever-cloud.com"; 
$username = "uuczyemiqw5gwb5b";        
$password = "fqE7iWnpfTZjhE61dWHa";            
$dbname = "by0pvku1jdod0itjfxl8"; 

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
//echo "Conexión exitosa";
?>
