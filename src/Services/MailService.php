<?php
namespace App\Services;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use App\Views\AppHelper;
use App\Views\DateHelper;
use League\Plates\Engine as PhpRenderer;

class MailService extends AppServiceAbstract
{
    const FROM_EMAIL = 'noreply@example.com';
    const FROM_NAME = 'Noreply';
    const TEMPLATE_PATH = __DIR__."/../Templates/Emails";

    protected PhpRenderer $template;
    protected string $view;
    protected array $data;

    public function __construct()
    {
        $this->initTemplate();
        $this->data = [];
        $this->data['settings'] = app()->getConfig();
        $this->view = '';
    }

    public function initTemplate()
    {
        $this->template = new PhpRenderer(self::TEMPLATE_PATH);
        $this->template->loadExtension(new AppHelper());
        $this->template->loadExtension(new DateHelper());
    }

    public function getContent() : string
    {
        return $this->template->render($this->view, $this->data);
    }

    public function withData(array $data) : self
    {
        $this->data = $data;
        return $this;
    }

    public function setViewRender(string $view) : self
    {
        $this->view = $view;
        return $this;
    }

    public function sender() : PHPMailer
    {
        $settings = container()->get('settings');
        $mailConfig = $settings['smtp'];
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                              //Send using SMTP
        $mail->Host         = $mailConfig['host'];                    //Set the SMTP server to send through
        $mail->SMTPAuth     = true;                                   //Enable SMTP authentication
        $mail->Username     = $mailConfig['user'];                    //SMTP username
        $mail->Password     = $mailConfig['sercret'];                 //SMTP password
        if(!empty($mailConfig['secure'])) {
            $mail->SMTPSecure   = $mailConfig['secure'];            //Enable implicit TLS encryption
        }
        $mail->Port         = $mailConfig['port'];
        $mail->CharSet      = 'UTF-8';
        //$mail->Encoding     = 'base64';

        $mail->setFrom(self::FROM_EMAIL, self::FROM_NAME);
        $mail->addReplyTo('noreply@example.com', 'Noreply');
        return $mail;
    }

}
?>