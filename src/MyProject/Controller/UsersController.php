<?php
namespace MyProject\Controller;

use MyProject\Models\Users\UserActivationService;
use MyProject\Models\Users\UsersAuthService;
use MyProject\Models\Users\User;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Services\EmailSender;
class UsersController extends AbstractController
{

    public function register()
    {
        if(!empty($_POST)){
            try{
                $user = User::signUp($_POST);//On effectue les tests et enregistre user dans BDD
            }
            catch(InvalidArgumentException $exception){
                $this->view->renderHtml('users/signUp.php', ['error' => $exception->getMessage()]);
                return;
            }
            if($user instanceof User){//Si user est bien enregistré on envoie un email de la cofirmation
                $code = UserActivationService::createActivationCode($user);
                EmailSender::send($user, 'Activation', 'mail/userActivation.php',
                    [
                        'userId' => $user->getId(),
                        'code' => $code
                    ]);
                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }
        $this->view->renderHtml('users/signUp.php');
    }
    public function activate(string $user_id, string $code)
    {
        $user = User::getOneById($user_id);
        if(!$user){
            $message = 'Cet utilisateur n\'existe pas.';
        }
        elseif($user->getConfirmed()){
            $message = 'Cet utilisateur est déjà activé.';

        }elseif(!UserActivationService::checkActivationCode($user, $code)){
            $message = 'Ce code n\'est pas valid.';
        }
        else{
            $message = 'Votre compte a été bien confirmé !';
            UserActivationService::deleteActivationCode($user_id, $code);
            $user->activate();
        }
        $this->view->renderHtml('users/messageSignUp.php', ['message' => $message]);

    }
    public function login()
    {
        try{
            $user = User::login($_POST);//on fait les verifs et on récupère un utilisateur
            UsersAuthService::createToken($user);//on create son Token et l'enregistre dans cookies
            header('Location: /phpMVC/');
            return;
        }
        catch(InvalidArgumentException $exception){
            $this->view->renderHtml('users/login.php', ['error' => $exception->getMessage()]);
            return;
        }
        $this->view->renderHtml('users/login.php');
    }
    public function logout()
    {
        setcookie('token', '', -1, '/', false, true);
        header('Location: /phpMVC/');
    }
}
