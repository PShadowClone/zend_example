<?php

class ContactUs_IndexController extends Zend_Controller_Action
{

    public $validationMessages = [];
    const MIN_STRING_LENGTH = 3;
    private $flash;


    public function init()
    {

    }

    public function indexAction()
    {
        $this->sendEmail();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $contactUs = new Model_Contactus();
            $data = $request->getPost();
            unset($data['submit']);
            try {
                $result = $this->validation($data);
                $contactUs->createContactForm($data);
                $this->sendEmail();
                $this->view->success = "your complain registered successfully";// send validation message back to view for rendering it view
            } catch (Exception $exception) {
                $this->view->validationMessage = $this->validationMessages;// send validation message back to view for rendering it view
            }
        }
    }


    private function sendEmail()
    {
        $options = array(
            'auth' => 'LOGIN',
            'username' => 'a0726bc8738564',
            'password' => 'ebe9da34b5c7b5',
            'port' => 2525
        );
        $mailTransport = new Zend_Mail_Transport_Smtp('smtp.mailtrap.io', $options);
        Zend_Mail::setDefaultTransport($mailTransport);
        Zend_Mail::setDefaultFrom('sender@example.com', 'John Doe');
        Zend_Mail::setDefaultReplyTo('replyto@example.com', 'Jane Doe');
        $mail = new Zend_Mail();
        $mail->addTo('studio@example.com', 'Test');

        $mail->setSubject('Zend Reply');
        $mail->setBodyText('Thank you, for giving us your opinion we will go hard to make you satisfy');
        $mail->send($mailTransport);
    }


    private function validation(array $data)
    {
        $ref = $this;
        $error = false;
        foreach ($data as $key => $value) {
            $functionName = $key . "_validation";
            $result = $ref->$functionName($data);
            if (!$result)
                $error = $result;
        }
        if (!$error)
            throw new Exception('');

        return $error;
    }


    private function email_validation(array $data): bool
    {
        $fieldName = "email";
        $validator = new Zend_Validate_EmailAddress();
        if ($validator->isValid($data[$fieldName])) {
            return true;
        }
        $this->validationMessages[$fieldName] = $validator->getMessages();
        return false;
    }

    private function username_validation(array $data): bool
    {
        $fieldName = "username";
        $validator = new Zend_Validate_StringLength(array('min' => ContactUs_IndexController::MIN_STRING_LENGTH));
        if ($validator->isValid($data[$fieldName])) {
            return true;
        }
        $this->validationMessages[$fieldName] = $validator->getMessages();
        return false;
    }

    private function title_validation(array $data): bool
    {
        $fieldName = "title";
        $validator = new Zend_Validate_StringLength(array('min' => ContactUs_IndexController::MIN_STRING_LENGTH));
        if ($validator->isValid($data[$fieldName])) {
            return true;
        }
        $this->validationMessages[$fieldName] = $validator->getMessages();
        return false;
    }

    private function message_validation(array $data): bool
    {
        $fieldName = "message";
        $validator = new Zend_Validate_StringLength(array('min' => ContactUs_IndexController::MIN_STRING_LENGTH));
        if ($validator->isValid($data[$fieldName])) {
            return true;
        }
        $this->validationMessages[$fieldName] = $validator->getMessages();
        return false;
    }

}

