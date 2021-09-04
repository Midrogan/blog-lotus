<?php

namespace controller;

use model\UsersModel;
use core\DB;
use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

class AuthController extends BaseController
{
    public function signupAction()
    {
        $this->title = 'Signup | Lotus';

        $errors['name_users'][0] = '';
        $errors['password_users'][0] = '';

        if ($this->request->isPost()) {
            $mUsers = new UsersModel(
                new DBDriver(DB::connect()),
                new Validator()
            );


            $auth = $mUsers->checkLogin($this->request->post('login'));

            if (count($auth) < 1) {
                if ($this->request->post('password') == $this->request->post('passwordAgain')) {
                    try {
                        $id = $mUsers->add([
                            // 'email_users' => $this->request->post('email'),
                            'name_users' => $this->request->post('login'),
                            'password_users' => $this->request->post('password'),
                            'role_users' => '0',
                            'info_users' => sprintf(
                                'Мы почти ничего не знаем о %s, но мы уверены, что %s — отличный человек.',
                                $this->request->post('login'),
                                $this->request->post('login')
                            ),
                            'avatar_users' => ''
                        ]);
                        // можно создать профиль с занятым никнеймом
                        // не регает но входит в сессию        или нет?

                        $_SESSION['userAvatar'] = $mUsers->checkAvatar('');
                        $_SESSION['userName'] =  $this->request->post('login');
                        $_SESSION['userRole'] = '0';
                        $_SESSION['userId'] = $id;

                        $this->redirect(sprintf('/profile/%s', $id));
                    } catch (ModelException $e) {
                        // var_dump($e->getErrors());
                        //Вывести ошибки в шаблон

                        $errors = $e->getErrors();
                    }
                } else {
                    $errors['passwordAgain_users'][0] = 'Пароли не совпадают';
                }
            } else {
                $errors['name_users'][0] = 'Такое имя уже занято';
            }
        }

        $this->content = $this->build(__DIR__ . '/../views/v_registration.php', ['errors' => $errors]);
    }

    public function signinAction()
    {
        $this->title = 'Signin | Lotus';

        $name = $this->request->session('userName');

        $mUsers = new UsersModel(
            new DBDriver(DB::connect()),
            new Validator()
        );

        $errors['name_users'][0] = '';
        $errors['password_users'][0] = '';

        if (isset($name)) {
            $id = $this->request->session('userId');

            $this->redirect(sprintf('/profile/%s', $id));
            exit();
        } else {
            if ($this->request->isPost()) {
                try {
                    $auth = $mUsers->checkUser(
                        $this->request->post('login'),
                        $this->request->post('password')
                    );

                    // hash('sha512', $this->request->post('password')

                    if (count($auth) > 0) {
                        $id = $auth[0]['id_users'];

                        $_SESSION['userAvatar'] = $mUsers->checkAvatar($auth[0]['avatar_users']);
                        $_SESSION['userName'] =  $this->request->post('login');
                        $_SESSION['userRole'] = $auth[0]['role_users'];
                        $_SESSION['userId'] = $id;

                        $this->redirect(sprintf('/profile/%s', $id));
                        exit();
                    } else {
                        $errors['name_users'][0] = 'Неверный логин или пароль ';
                    }
                } catch (ModelException $e) {
                    // var_dump($e->getErrors());
                    //Вывести ошибки в шаблон

                    $errors = $e->getErrors();
                }
            }

            $this->content = $this->build(__DIR__ . '/../views/v_login.php', ['errors' => $errors]);
        }
    }

    public function signoutAction()
    {
        unset($_SESSION['userAvatar']);
        unset($_SESSION['userName']);
        unset($_SESSION['userId']);
        session_destroy();
        $this->redirect(ROOT);
    }
}
