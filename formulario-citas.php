<?php
/*
    Plugin Name:  formulario Citas
    Plugin URI: 
    Description: Enviar cita y agregar a la base de datos la informacion del cliente
    Version: 1.0.0
    Author: Emerson Israel Sanchez Hernandez
    Author URI: https://www.instagram.com/emersonhdz94/
    Text Domain: 
*/

//======= creacion de script para cargar js en el plugin=====//



function formulario_citas_js(){
    wp_enqueue_script('js-citas', plugin_dir_url(__FILE__) . 'formulario-citas.js', array('jquery'), '1.0', true);
    wp_enqueue_style('css-citas', plugin_dir_url(__FILE__) . 'formulario-citas.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'formulario_citas_js' );


//======= insertar Cita en base de datos=====//
function insertar_cita_en_bd() {
    global $wpdb;

    $tabla = $wpdb->prefix . 'formulario_citas'; // Nombre completo de la tabla personalizada

    $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
    $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);

    $email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Limpiar caracteres especiales

    $telefono = isset($_POST['telefono']) ? sanitize_text_field($_POST['telefono']) : ''; 
    $telefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT); // Limpiar caracteres no numéricos

    $fecha = isset($_POST['fecha']) ? sanitize_text_field($_POST['fecha']) : '';
    $fecha = filter_var($fecha, FILTER_SANITIZE_STRING); // Limpiar caracteres especiales y etiquetas HTML

    $hora = isset($_POST['hora']) ? sanitize_text_field($_POST['hora']) : '';
    $hora = filter_var($hora, FILTER_SANITIZE_STRING); // Limpiar caracteres especiales y etiquetas HTML

    $datos = array(
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => $telefono,   
        'fecha' => $fecha,
        'hora' => $hora,
    );

    $wpdb->insert($tabla, $datos);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//======= Enviar correo electronico=====//
function enviar_correo_cita($datos) {
   
//Load Composer's autoloader
require 'vendor/autoload.php';

    $mail = new PHPMailer(); 
    $mail->isSMTP();
    $mail->Host = 'smtp.ionos.co.uk';
    $mail->SMTPAuth = true;
    $mail->Username = 'thaimassagedarlington@thaimassagedarlington.co.uk';
    $mail->Password = 'Emersonhdz1994*';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587 ;

    $mail->setFrom( $datos['email'], 'Karma T. Massage');
    $mail->addAddress('karmatmassage@gmail.com', 'Karma T. Massage');
    $mail->Subject = 'New scheduled appointment';
    $mail->Body = 'A new appointment has been scheduled with the following information:' . PHP_EOL .
                  '- Name: ' . $datos['nombre'] . PHP_EOL .
                  '- Email: ' . $datos['email'] . PHP_EOL .
                  '- Phone: ' . $datos['telefono'] . PHP_EOL .
                  '- Date: ' . $datos['fecha'] . PHP_EOL .
                  '- Time: ' . $datos['hora'] . PHP_EOL;

    if ($mail->send()) {
        echo '<p class="exito">The email has been sent successfully.</p>';
    } else {
        echo '<p class="error">An error occurred while sending the email.</p>';
    }
   
}




//======= Creacion de formulario y guardar informacion en mi base de datos=====//
function crear_formulario_citas() {
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        insertar_cita_en_bd();
        enviar_correo_cita($_POST);
    }

    ?>
    <div class="formulario-citas">
    <form class="formulario" method="POST">
       <div class="campo">
       <label for="nombre">Name:</label>
        <input type="text" id="nombre" name="nombre" required>
       </div>
  
       <div class="campo">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
       </div>

       <div class="campo">
      <label for="telefono">Phone:</label>
     <input type="tel" id="telefono" name="telefono" pattern="[0-9]+" required>
      </div>

       <div class="campo">
        <label for="fecha">Date:</label>
        <input type="date" id="fecha" name="fecha" required min="<?php echo date('Y-m-d');?>">
       </div> 
  
       <div class="campo">
        <label for="hora">Time:</label>
        <input type="time" id="hora" name="hora" required>
       </div>  
  
        <input type="submit" value="Make Appointment">
    </form>
    </div>

    <?php
}

add_shortcode( 'formulario_citas_shortcode', 'crear_formulario_citas' );



