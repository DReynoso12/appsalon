<?php

namespace Controllers;

use Classes\Email;
use Model\usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new usuario($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                $usuario = usuario::where('email', $auth->email);
                if ($usuario) {
                    //verificar password
                    if ($usuario->ComprobarPasswordAndVerificado($auth->password)) {
                        //Autenticar usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        //Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    usuario::setAlerta('error', 'usuario no encontrado');
                }
            }
        }
        $alertas = usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }
    public static function logout()
    {
        $_SESSION = [];
        header('Location: /');
    }
    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new usuario($_POST);
            $alertas = $auth->validarEmail();
            if (empty($alertas)) {
                $usuario = usuario::where('email', $auth->email);
                if ($usuario && $usuario->confirmado === "1") {
                    //generar token único
                    $usuario->crearToken();
                    $usuario->guardar();

                    //TODO: Enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();
                    //Alerta exito
                    usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }
        }
        $alertas = usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        //Buscar usuario por token
        $usuario = usuario::where('token', $token);

        if (empty($usuario)) {
            usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //LEER NUEVO PASSWORD Y GUARDAR
            $password = new usuario($_POST);
            $alertas = $password->validarPassword();
            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location /');
                }
            }
        }
        $alertas = usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router)
    {
        $usuario = new usuario;
        //alertas vacías
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarnuevacuenta();

            //Revision de alertas vacío
            if (empty($alertas)) {
                //Verificar que el usuario no esté registrado
                $resultado = $usuario->existeusuario();
                if ($resultado->num_rows) {
                    $alertas = usuario::getAlertas();
                } else {
                    //No está registrado
                    $usuario->hashPassword();

                    //Generar token único
                    $usuario->crearToken();

                    //Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = usuario::where('token', $token);

        if (empty($usuario)) {
            //Mostrar mensaje de error
            usuario::setAlerta('error', 'token no válido');
        } else {
            //Actualiza usuario cómo confirmado
            $usuario->confirmado = "1";
            $usuario->token = NULL;
            $usuario->guardar();

            usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
