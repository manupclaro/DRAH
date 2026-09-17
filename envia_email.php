<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

function enviarCodigoEmail($destinatario, $codigo) {
    $mail = new PHPMailer(true);

    try {
       
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        
        
        $mail->Username   = 'drah.support4@gmail.com'; 
        $mail->Password   = 'pusx bdks byam awce'; 
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

     
        $mail->setFrom('drah.support4@gmail.com', 'DRAH');
        $mail->addAddress($destinatario);

      
        $mail->addEmbeddedImage('logo_laranja.png', 'logo_DRAH');

        $mail->isHTML(true);
        $mail->Subject = 'Código de Verificação - DRAH';
        
        
        $mail->Body = '
            <div style="font-family: Montserrat, Arial, sans-serif; max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center;">
                <img src="cid:logo_check" alt="Logo Check" style="max-width: 120px; margin-bottom: 20px;">
                <h2 style="color: #333;">Código de Verificação</h2>
                <p style="color: #555; font-size: 16px;">Use o código de 4 dígitos abaixo para continuar a operação no sistema:</p>
                
                <div style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #004aad; margin: 25px 0;">
                    ' . $codigo . '
                </div>
                
                <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
                
                <footer style="font-size: 12px; color: #888;">
                    <p><strong>Laboratório de Hardware</strong><br>Sistema CHECK de Gestão e Acesso</p>
                    <p>Contato: drah.support@gmail.com</p>
                </footer>
            </div>
        ';

        $mail->send();
    } catch (Exception $e) {
        
        error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
    }
}
?>
