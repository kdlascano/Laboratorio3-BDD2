<?php
include('db.php'); // Archivo de conexión a la base de datos
include('funciones.php'); // Archivo con funciones de inserción y verificación

// Variables para los mensajes de alerta
$mensaje_registro = "";
$tipo_alerta_registro = "";

$mensaje_verificacion = "";
$tipo_alerta = "";

// Si se envió el formulario de agregar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    
    // Validar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE nombre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario ya registrado
        $mensaje_registro = "El usuario ya está registrado.";
        $tipo_alerta_registro = "danger"; // Clase de Bootstrap para error
    } else {
        // Insertar el usuario si no existe
        insertarUsuario($nombre, $password);
        $mensaje_registro = "Usuario registrado correctamente.";
        $tipo_alerta_registro = "success"; // Clase de Bootstrap para éxito
    }
    $stmt->close();
}

// Si se envió el formulario de verificar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'verificar') {
    $nombre_verificar = $_POST['nombre_verificar'];
    $password_verificar = $_POST['password_verificar'];
    
    // Llama a la función de verificación
    $resultado = verificarContrasena($nombre_verificar, $password_verificar);
    if ($resultado) {
        $mensaje_verificacion = "Usuario verificado correctamente.";
        $tipo_alerta = "success"; // Clase de Bootstrap para éxito
    } else {
        $mensaje_verificacion = "Usuario o contraseña incorrectos.";
        $tipo_alerta = "danger"; // Clase de Bootstrap para error
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario con Registros</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Alerta de registro -->
    <?php if (!empty($mensaje_registro)): ?>
        <div class="alert alert-<?= $tipo_alerta_registro; ?> text-center mb-0" role="alert">
            <?= $mensaje_registro; ?>
        </div>
    <?php endif; ?>

    <!-- Alerta de verificación -->
    <?php if (!empty($mensaje_verificacion)): ?>
        <div class="alert alert-<?= $tipo_alerta; ?> text-center mb-0" role="alert">
            <?= $mensaje_verificacion; ?>
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <h2 class="text-center">Gestión de Usuarios</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Formulario para agregar usuario -->
                <form method="POST" class="card p-4 shadow">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Agregar Usuario</button>
                </form>
            </div>
            <div class="col-md-6">
                <!-- Formulario para verificar usuario -->
                <form method="POST" class="card p-4 shadow mt-4 mt-md-0">
                    <input type="hidden" name="accion" value="verificar">
                    <div class="mb-3">
                        <label for="nombre_verificar" class="form-label">Nombre</label>
                        <input type="text" name="nombre_verificar" id="nombre_verificar" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_verificar" class="form-label">Contraseña</label>
                        <input type="password" name="password_verificar" id="password_verificar" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Verificar Usuario</button>
                </form>
            </div>
        </div>
        
        <!-- Tabla de registros -->
        <div class="row mt-5">
            <h3 class="text-center">Lista de Usuarios</h3>
            <div class="col-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Contraseña</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostrar todos los registros de la tabla usuarios
                        $sql = "SELECT * FROM usuarios";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['nombre'] . "</td>";
                                echo "<td>" . $row['password'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No hay usuarios registrados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
