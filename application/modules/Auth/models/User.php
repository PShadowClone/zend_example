<?php


class _Model_User extends _Model_DbModel
{

    public $data;
    public $userTableModel;
    public $fillable = ['id', 'username', 'email', 'password'];
    public $hidden = ['password'];

    /**
     * _Model_User constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->userTableModel = new _Model_DbTable_User();

    }

    public function delete($where)
    {
        return $this->userTableModel->delete($where);

    }

    /**
     *
     * get all users
     *
     * @return array
     */
    public function get($id = null)
    {
        if (!$id)
            return $this->userTableModel->fetchAll()->toArray();
        $user = $this->userTableModel->fetchRow($this->userTableModel->select()->where('id=?', $id));
        if (!$user)
            return null;
        $user = $user->toArray();
        unset($user['password']);

        return $user;
    }

    /**
     * @throws Exception
     */
    public function save()
    {
        if (!$this->data)
            throw new Exception('There is not data to be saved');
        if (isset($this->data['password']) && !is_null($this->data['password'])) {
            $this->data['password'] = md5($this->data['password']); //password_hash($this->data['password'], PASSWORD_DEFAULT);
        }
        return $this->userTableModel->insert($this->data);
    }

    /**
     *
     * update user's data
     *
     * @param $where
     * @return int
     * @throws ValidationException
     */
    public function update($where)
    {
        if (!$this->data)
            throw new ValidationException('There is not data to be updated');
        return $this->userTableModel->update($this->data, $where);

    }

    /**
     *
     * fill user's form to user's attributes
     *
     * @param $data
     */
    public function fill($data)
    {

        foreach ($this->fillable as $key => $value) {
            if (isset($data[$value])) {
                $this->data[$value] = trim($data[$value]);
                if ($value == 'password') {
//                    $this->data[$value] = password_hash($data[$value], PASSWORD_DEFAULT);
                }
            }
        }
    }

    public function validate($where = '')
    {
        $error = true;
        if (sizeof($this->data) == 0)
            throw new ValidationException('Data is not supported');
        foreach ($this->data as $key => $value) {
            $functionName = $key . 'Validation';
            $result = $this->$functionName($where);
            if (!$result) {
                $error = false;
            }
        }
        return $error;
    }

    /**
     * validate user's email
     *
     * @return bool
     */
    public function emailValidation($where = '')
    {
        $validation = new Zend_Validate_EmailAddress();
        if ($validation->isValid($this->data['email'])) {
            $row = $this->userTableModel->fetchRow($this->userTableModel->select()->where('email = ?', $this->data['email'])->where('id != ?', $where));
            if (!is_null($row)) {
                $this->messages['email'] = 'email is already been taken';
                return false;
            }
        } else {
            $this->messages['email'] = 'Invalid email format';
            return false;
        }
        return true;
    }

    /**
     * validate user's username
     *
     * @return bool
     */
    public function usernameValidation($where = '')
    {
        $validation = new Zend_Validate_StringLength(['min' => 3]);
        if (!$validation->isValid($this->data['username'])) {
            $this->messages['username'] = 'invalid username';
            return false;
        }
        return true;
    }

    /**
     * validate user's password
     *
     * @return bool
     * @throws Zend_Validate_Exception
     */
    public function passwordValidation($where = '')
    {
        $validation = new Zend_Validate_Regex('/^[a-zA-Z0-9]{6,}$/');
        if (!$validation->isValid($this->data['password'])) {
            $this->messages['password'] = 'invalid password';
            return false;
        }
        return true;
    }

    /**
     *
     * validate confirm password
     *
     * @return bool
     */
    public function confirm_passwordValidation($where = '')
    {
        if (trim($this->data['password']) != trim($this->data['confirm_password'])) {
            $this->messages['confirm_password'] = 'password and confirmation are not matched';
            return false;
        }
        return true;
    }

    /**
     *
     * get the attributes
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        try {
            return $this->data[$name];
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     *
     * specify the set of model
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * get the data of model
     *
     * @return array
     */
    public function getModel()
    {
        $data = $this->data;
        try {
            foreach ($this->hidden as $value) {
                unset($data[$value]);
            }
        } catch (Exception $exception) {
        }
        return $data;
    }

}

