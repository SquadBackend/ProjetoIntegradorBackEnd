<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthLogin extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $userModel = new UserModel();
        $email = $this->request->getVar('usuario');
        $senha = $this->request->getVar('senha');
        $usuario = $userModel->where('Email', $email)->first();

        if(is_null($usuario)){
            session()->setFlashdata('erro', 'Usuário ou senha incorretos');
            return redirect()->to(site_url('','http'));
        }

        $authVerify = password_verify($senha, $usuario['Senha']);

        if(!$authVerify){
            session()->setFlashdata('erro', 'Usuário ou senha incorretos');
            return redirect()->to(site_url('','http'));
        }

        $ses_data = [
            'id' => $usuario['id'],
            'nome' => $usuario['Nome'],
            'tipo' => $usuario['Tipo_usuario'],
            'email' => $usuario['Email'],
            'bloqueado' => $usuario['Bloqueado'],
            'verificado' => $usuario['Verificado'],
            'isLoggedIn' => TRUE
        ];

        $session->set($ses_data);

        if($usuario['Tipo_usuario'] == 0){
            return redirect()->to(site_url('aluno/','http'));
        }else if($usuario['Tipo_usuario'] == 1){
            return redirect()->to(site_url('cantina/','http'));
        }else if($usuario['Tipo_usuario'] == 2){
            return redirect()->to(site_url('cae/','http'));
        }
        

    }

    public function logout() {
        session_destroy();
        return redirect()->to(site_url('','http'));
    }
}
