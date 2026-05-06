<?php

defined('BASEPATH') or exit('No direct scripts access allowed');

class Register_model extends MY_Model
{
    protected $table = 'users';

    public function getDefaultValues()
    {
        return [
            'name' => '',
            'email' => '',
            'password' => '',
            'role' => '',
            'is_active' => '',
        ];
    }

    public function getValidationRules()
    {
        $validationRules = [
            ['field' => 'name', 'label' => 'Nama', 'rules' => 'trim|required'],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => '%s tidak boleh kosong',
                    'valid_email' => '%s tidak valid',
                    'is_unique' => '%s sudah terdaftar'
                ]
            ],
            ['field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[8]'],
            ['field' => 'password_confirmation', 'label' => 'Konfirmasi Password', 'rules' => 'required|matches[password]'],
        ];

        return $validationRules;
    }

    public function run($input)
    {
        $data = [
            'name' => $input->name,
            'email' => strtolower($input->email),
            'password' => hashEncrypt($input->password),
            'role' => 'member',
            'is_active' => 1,
        ];

        $user = $this->create($data);

        $sess_data = [
            'id' => $user,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_login' => true
        ];

        $this->session->set_userdata($sess_data);

        return true;
    }
}
