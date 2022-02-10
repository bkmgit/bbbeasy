<?php

namespace Actions\Account;

use Enum\ResponseCode;
use Mail\MailSender;
use Models\User;
use Actions\Base as BaseAction;

class Reset extends  BaseAction
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute($f3): void
    {
        $user = new User();
        $form = $this->getDecodedBody();

        $email = $form['email'];

        if ($user->emailExists($email)) {
            $user = $user->getByEmail($email);

            // valid credentials
            $this->session->authorizeUser($user);

            $this->session->set('locale', $user->locale);

            $mail     = new  MailSender();
            $template = 'common/reset_password';
            $mail->send($template, [], $email, 'reset password', 'reset password');

            if ($mail) {
                $this->renderJson(['message'=> 'Please check your email to reset your password '], ResponseCode::HTTP_OK);
            }
        } else {
            // email invalid or user no exist
            $message = 'User does not exist with this email';
            $this->logger->error('Login error : user could not logged', ['error' => $message]);
            $this->renderJson(['message' => $message], ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}