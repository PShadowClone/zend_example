<?php


class Auth_ProfileController extends Zend_Controller_Action
{

    public function init()
    {

        parent::init(); // TODO: Change the auto-generated stub
        if (!Zend_Auth::getInstance()->hasIdentity())
            return $this->redirect('auth/login');

        // change the context of some methods
        // consider delete method as a service which returns json result
        // so it needs no view
        $context = $this->getHelper('ContextSwitch');
        $context->addActionContext('delete', 'json')
            // publish the new context of delete method
            ->initContext('json');

        $locale = new Zend_Locale();
        $locale->setLocale('ar');

//        $this->translate->setLocale('ar');
//        $translate->setLocale("fr_CH");

    }

    /**
     *
     * show profile
     *     and
     * store profile
     *
     */
    public function indexAction()
    {

        $user = new _Model_User();
        $this->view->users = $user->get();

        $authCredintioals = Zend_Auth::getInstance()->getIdentity();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
//            var_dump($data);
//            die();
//            unset($data['submit']);
            unset($data['submit']);
            $user = new _Model_User($data);
            try {
                $user->validate();
                $avatar = $this->uploadFile();
                $user->data['avatar'] = $avatar;
                $user->data['email'] = $data['email'];
                $user->update('email="' . $authCredintioals . '"');
                Zend_Auth::getInstance()->clearIdentity();
            } catch (ValidationException $validationException) {
            } catch (Exception $validationException) {
            }
        }
        
    }

    /**
     *
     * this action deletes user's info from database
     *
     * @param int id
     * @type ajax request
     * @response json
     */
    public function deleteAction()
    {
        $this->_helper->layout()->disableLayout();

        // get the user's request
        $request = $this->getRequest();
        // check if the http method is delete or not
        if (!$request->isDelete()) {
            $this->_response->setBody(ajax_response(500, 'ILLEGAL ACCESS '));
            return;
        }

        // check if the request is ajax for normal web request
        if (!ajax()) {
            $this->_response->setBody(ajax_response(500, 'ILLEGAL ACCESS '));
            return;
        }
        // get the id value from url
        $id = $request->getParam('id');

        $user = new _Model_User();
        try {
            // delete user
            $result = $user->delete('id=' . $id);
            // return json
            $this->_response->setBody(ajax_response(200, 'User has been deleted successfully'));
        } catch (Exception $exception) {
            $this->_response->setBody(ajax_response(404, 'user not found'));
        }
    }


    /**
     *
     * upload form files
     *
     *
     * @return string
     * @throws UploadException
     * @throws Zend_File_Transfer_Exception
     */
    private function uploadFile()
    {
        // get files from http request
        $adapter = new Zend_File_Transfer_Adapter_Http();
        // get file's name
        $name = $adapter->getFileName();
        // explode the all destination to get the name of file which lies at the end of destination
        $originalDestination = explode('/', $name);
        // get the file's name
        $originalName = end($originalDestination);
        // the new name of file is time() to vanish any duplications
        $newName = time();
        // split the original name
        $extension = explode('.', $originalName);
        // get the extension of file
        $extension = end($extension);
        // move the uploaded file into the files directions
        $adapter->addFilter('Rename', APPLICATION_PATH . '/../public/' . config()->FILE_DIR . '/' . $newName . '.' . $extension);
        // check if file has been uploaded or not
        if (!$adapter->receive()) {
            throw new UploadException('something went wrong while uploading image');
        }
        // I want the file name concatenate with it's direction in public
        $destination = explode('/', $adapter->getFileName());
        // master / filename
        return config()->FILE_DIR . '/' . end($destination);
    }
}