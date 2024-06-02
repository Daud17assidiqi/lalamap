<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form'];
    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }

    public function loginHandler()
    {
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' :  'username';

        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Email is not valid',
                        'is_not_unique' => 'Email is not registered'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'min_length' => 'password must have atleast 5 characters in length',
                        'max_length' => 'password must not have characters more than 45 in length'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'Username is required',
                        'is_not_unique' => 'Username is not registered'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'min_length' => 'password must have atleast 5 characters in length',
                        'max_length' => 'password must not have characters more than 45 in length'
                    ]
                ]
            ]);
        }
        if (!$isValid) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => $this->validator
            ]);
        } else {
            $user = new User();
            $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first();
            $check_password = Hash::check($this->request->getVar('password'), $userInfo['password']);

            if (!$check_password) {
                return redirect()->route('admin.login.form')->with('fail', 'Wrong password')->withInput();
            } else {
                CIAuth::setCIAuth($userInfo);
                return redirect()->route('admin.home');
            }
        }
    }
    public function forgotForm()
    {
        $data = array(
            'pageTitle' => 'Forgot password',
            'validation' => null,
        );
        return view('backend/pages/auth/forgot', $data);
    }
}
