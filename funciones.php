<?php

include('db.php');

// Función para encriptar la contraseña con SHA-256
function encriptarContrasena($contrasena) {
    return hash('sha256', $contrasena);
}

 function insertarUsuario($nombre, $contrasena) {
    global $conn;

    // Verificar si el nombre de usuario ya existe
    $sql_verificar = "SELECT id FROM usuarios WHERE nombre = ?";
    if ($stmt_verificar = $conn->prepare($sql_verificar)) {
        $stmt_verificar->bind_param("s", $nombre);
        $stmt_verificar->execute();
        $stmt_verificar->store_result();

        if ($stmt_verificar->num_rows > 0) {
            //echo "Error: El nombre de usuario ya existe.";
            $stmt_verificar->close();
            return; // Salir de la función si el usuario ya existe
        }

        $stmt_verificar->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return;
    }

    // Insertar el usuario si no existe
    $contrasena_encriptada = encriptarContrasena($contrasena);
    $sql_insertar = "INSERT INTO usuarios (nombre, password) VALUES (?, ?)";

    if ($stmt_insertar = $conn->prepare($sql_insertar)) {
        $stmt_insertar->bind_param("ss", $nombre, $contrasena_encriptada);

        if ($stmt_insertar->execute()) {
            //echo "Usuario insertado correctamente.";
        } else {
            echo "Error al insertar el usuario: " . $stmt_insertar->error;
        }

        $stmt_insertar->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}


// Función para verificar la contraseña ingresada con la almacenada
function verificarContrasena($nombre, $contrasena_ingresada) {
    global $conn; 
    $contrasena_encriptada = encriptarContrasena($contrasena_ingresada);
    $sql = "SELECT password FROM usuarios WHERE nombre = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $stmt->store_result();
        $stmt->bind_result($password_almacenada);

        if ($stmt->fetch()) {
            if ($contrasena_encriptada === $password_almacenada) {
                //echo "La contraseña es correcta.";
                return true;
            } else {
                //echo "La contraseña es incorrecta.";
                return false;
            }
        } else {
            //echo "El usuario no existe.";
            return false;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }
}
?>
