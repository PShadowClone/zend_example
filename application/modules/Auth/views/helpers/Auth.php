<?php


class Zend_View_Helper_Auth
{
    /**
     *
     * return the authenticated user's object
     *
     * @return mixed
     */
    public function auth()
    {
        $user = new _Model_User([]);
        $result = $user->userTableModel->fetchRow($user->userTableModel->select()->where('email=?', Zend_Auth::getInstance()->getIdentity()));
        $data = $result->toArray();
        unset($data['password']); // security reasons
        unset($data['id']); // security reasons
        return json_decode(json_encode($data));
    }
}