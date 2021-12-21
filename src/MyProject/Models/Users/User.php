<?php
namespace MyProject\Models\Users;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
class User extends ActiveRecordEntity
{
    /**
     * @var string
     */
    protected $nickname;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var bool
     */
    protected $isConfirmed;
    /**
     * @var string
     */
    protected $role;
    /**
     * @var string
     */
    protected $passwordHash;
    /**
     * @var string
     */
    protected $authToken;
    /**
     * @var string
     */
    protected $createdAt;

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'users';
    }
    public static function signUp(array $userData)
    {
        //On fait les vérifications
        if(empty($userData['nickname'])){
            throw new InvalidArgumentException('Le nickname est vide.');
        }
        if(!preg_match('/^[A-Za-z0-9]+$/', $userData['nickname'])){
            throw new InvalidArgumentException('Le nickname ne doit contenir que les lettres latines et les chiffres.');
        }
        if(empty($userData['email'])){
            throw new InvalidArgumentException('L\'email est vide.');
        }
        if(!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException('L\'email est invalid.');
        }
        if(empty($userData['password'])){
            throw new InvalidArgumentException('Le password est vide.');
        }
        if(strlen($userData['password']) < 8){
            throw new InvalidArgumentException('Le password doit être au moins 8 caracthères.');
        }
        if(User::findOneByColumn('nickname', $userData['nickname'])){
            throw new InvalidArgumentException('L\'utilisateur avec ce nickname existe déjà.');
        }
        if(User::findOneByColumn('email', $userData['email'])){
            throw new InvalidArgumentException('L\'utilisateur avec cet email existe déjà.');
        }
        //et on enregistre l' utilisateur dans BDD
        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->isConfirmed = false;
        $user->role = 'user';
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();
        return $user;

    }

    /**
     * @return bool
     */
    public function getConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
    public function isAdmin()
    {
        return $this->role == 'admin';
    }
    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
    }

    /**
     * @param array $userData
     * @return User
     * @throws InvalidArgumentException
     */
    public static function login(array $userData): User
    {
        if(empty($userData['email'])){
            throw new InvalidArgumentException('L\'email est vide.');
        }
        if(empty($userData['password'])){
            throw new InvalidArgumentException('Le mot de passe est vide.');
        }
        $user = User::findOneByColumn('email', $userData['email']);

        if($user === null) {
            throw new InvalidArgumentException('L\'utilisateur avec cet email n\'existe pas.');
        }
        var_dump($user);
        if(!password_verify($userData['password'], $user->getPasswordHash())){
           throw new InvalidArgumentException('Le mot de passe est incorrect.');
        }
        if(!$user->isConfirmed){
            throw new InvalidArgumentException('L\'utilisateur n\'est pas activé.');
        }
        $user->refreshAuthToken();
        $user->save();
        return $user;
    }
    protected function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
    public function getAuthToken()
    {
        return $this->authToken;
    }
}
