<?php
namespace MyProject\Controller;

use MyProject\Models\Users\User;
use MyProject\Models\Users\UsersAuthService;
use MyProject\View\View;

class AbstractController
{
    /**
     * @var View
     */
    protected $view;
    /**
     * @var User|null
     */
    protected $user;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->user = UsersAuthService::getUserByToken();
        $this->view->setVar('user', $this->user);

    }
}