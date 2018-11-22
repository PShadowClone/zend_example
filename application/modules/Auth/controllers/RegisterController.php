<?php


class Auth_RegisterController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init(); // TODO: Change the auto-generated stub

        if (Zend_Auth::getInstance()->hasIdentity()) {
            return $this->redirect('auth/profile');
        }
    }

    /**
     *
     * register new user into system
     *
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            unset($data['submit']); //
            try {
                $user = new _Model_User($data);
                $user->fill($data);
                $request = $user->validate();
                if (!$request) {
                    $this->view->data = $data;
                    $this->view->validationMessage = $user->messages;
                } else {
                    $user->save();
                }
            } catch (Exception $exception) {
                $this->view->error = $exception->getMessage();
            }
        }
    }
}