<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Users extends AppModel
{
    public $name = 'Users';

    public $validate = [
            'username' => [
                    'rule' => 'notEmpty',
                    'message' => 'Vui lòng nhập tên đăng nhập.'
            ],
            'password' => [
                    'rule' => 'notEmpty',
                    'message' => 'Vui lòng nhập mật khẩu.'
            ]
    ];
    public function beforeSave($options = [])
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        return true;
    }

    public function getCryptKey() {
        return Configure::read('AppConfig.MEMBER_CRYPT_KEY');
    }

    /**
     * decrypt AES 256
     *
     * @param data $edata
     * @param string $password
     * @return dencrypted data
     */
    public function decrypt($edata, $password = null) {
        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);

        $password = is_null($password) ? $this->getCryptKey() : $password;

        $rounds = 3; // depends on key length
        $data00 = $password.$salt;
        $hash = array();
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
            $result .= $hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv  = substr($result, 32, 16);

        return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
    }

    /**
     * crypt AES 256
     *
     * @param data $data
     * @param string $password
     * @return base64 encrypted data
     */
    public function encrypt($data, $password = null) {
        // Set a random salt
        $salt = openssl_random_pseudo_bytes(16);

        $password = is_null($password) ? $this->getCryptKey() : $password;

        $salted = '';
        $dx = '';
        // Salt the key(32) and iv(16) = 48
        while (strlen($salted) < 48) {
            $dx = hash('sha256', $dx.$password.$salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);

        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
        return base64_encode($salt . $encrypted_data);
    }
}
