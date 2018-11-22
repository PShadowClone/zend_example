<?php


class Auth_ServiceController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $context = $this->getHelper('ContextSwitch');
        $context->addActionContext(['index', 'show', 'create', 'update', 'delete'], 'json')
            ->initContext();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

    }

    /**
     *
     * show all users
     *
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'application/json');
        if (!$request->isGet()) {
            $response->setBody(api_response('method not found'), [], 1);
            return;
        }
        $user = new _Model_User();
        $users = $user->get();
        $response->setBody(api_response('users have been fetched successfully', $users));

    }

    /**
     *
     * show a specific user
     *
     */
    public function showAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'application/json');
        if (!$request->isGet()) {
            $response->setBody(api_response('method not found'), [], 1);
            return;
        }
        $values = $request->getParam(1);
        $user = new _Model_User();
        $result = $user->get($values);

        $response->setBody(api_response('users have been fetched successfully', $result));
    }

    /**
     *
     * create user's model
     *
     * @throws Exception
     * @throws ValidationException
     * @throws Zend_Json_Exception
     */
    public function createAction()
    {

        $request = $this->getRequest();
        $response = $this->getResponse();

        if ($request->isPost()) {
            $data = Zend_Json::decode($request->getRawBody());
            $user = new _Model_User();
            $user->fill($data);
            $result = $user->validate();
            if (!$result) {
                $key = key($user->messages);
                $response->setBody(api_response('validation error', $user->messages[$key]));
                return;
            }
            $result = $user->save();
            $user->id = $result;

            $response->setBody(api_response('user\'s data have been saved successfully', $user->getModel()));
        }
    }

    /**
     *
     * update user's info
     *
     * @return void|Zend_Controller_Response_Abstract
     * @throws Zend_Json_Exception
     */
    public function updateAction()
    {

        $request = $this->getRequest();
        $response = $this->getResponse();

        if (!$request->isPut()) {
            return $response->setBody(api_response('method not allowed', [], 1));
        }
        $data = Zend_Json::decode($request->getRawBody());
        $userId = $request->getParam(1);
        $user = new _Model_User();

        $user->fill($data);
        try {

            $result = $user->validate($userId);
            if (!$result) {
                $key = key($user->messages);
                $response->setBody(api_response('validation error', $user->messages[$key]));
                return;
            }
            $user->update('id=' . $userId);
            $user->id = $userId;
        } catch (ValidationException $validationException) {

        }

        return $response->setBody(api_response('user\'s info has been updated successfully', $user->getModel()));
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if (!$request->isDelete()) {
            return $response->setBody(api_response('method not allowed', [], 1));
        }
        $user = new _Model_User();
        $userId = $request->getParam(1);
        $deletedUser = $user->get($userId);
        if (!$deletedUser) {
            $response->setBody(api_response('user is not found', ''));
            return;
        }
        $result = $user->delete('id=' . $userId);
        if ($result == 1) {
            $response->setBody(api_response('user has been deleted successfully', $deletedUser));
            return;
        }
        $response->setBody(api_response('something went wrong while deleting user\'s info', $deletedUser, 1));
    }


}