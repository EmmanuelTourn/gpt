<?php

namespace App\Controllers;

use App\Models\usuario_Model;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function auth()
    {
        $session = session();
        $model = new usuario_Model();

        // Obtener datos del formulario
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('pass');

        // Verificar que los datos se están recibiendo
        if (!$email || !$password) {
            $session->setFlashdata('msg', 'Email o contraseña no proporcionados');
            return redirect()->to('login');
        }

        // Buscar el usuario por email
        $data = $model->where('email', $email)->first();

        // Depurar el resultado de la base de datos
        if ($data) {
            $pass = $data['pass'];

            $verify_pass = password_verify($password, $pass);

            if ($verify_pass) {
                $ses_data = [
                    'id_usuario' => $data['id_usuario'],
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'],
                    'email' => $data['email'],
                    'usuario' => $data['usuario'],
                    'perfil_id' => $data['perfil_id'],
                    'active' => TRUE,
                ];

                $session->set($ses_data);
                $session->setFlashdata('msg', 'Bienvenido!!');
                return redirect()->to(base_url('principal'));
            } else {
                $session->setFlashdata('msg', 'Contraseña incorrecta');
                return redirect()->to('login');
            }
        } else {
            $session->setFlashdata('msg', 'Email no encontrado');
            return redirect()->to('login');
        }
    }
}