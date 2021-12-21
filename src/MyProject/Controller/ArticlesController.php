<?php
namespace MyProject\Controller;

use MyProject\Exceptions\Forbidden;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;

class ArticlesController extends AbstractController
{
    public function main()
    {
        $articles = Article::findAll();
        $this->view->renderHtml('articles/main.php', ['articles' => $articles]);
    }
    public function view(int $articleId)
    {
        $article = Article::getOneById($articleId);
        if($article === null){
            throw new NotFoundException("L'article n'est pas trouvé.");
        }else{
            $this->view->renderHtml('articles/view.php', ['article' => $article]);
        }
    }
    public function edit(int $articleId)
    {
        $article = Article::getOneById($articleId);
        if($this->user === null){
            throw new UnauthorizedException('Vous n\'avez pas droit d\'accéder à cette page.');
        }
        if(!$this->user->isAdmin() || !($this->user->getId() == $article->getAuthorId())){
            throw new Forbidden('Vous  n\'êtes pas admin ou auteur de l\'article et vous n\'avez pas droit d\'accéder à cette page.');
        }

        if($article === null){
            throw new NotFoundException("L'article n'est pas trouvé");
        }
        if(!empty($_POST)){
            try{
                $article = $article->updatePostFromArray($_POST);
            }
            catch(InvalidArgumentException $exception)
            {
                $this->view->renderHtml('articles/edit.php', ['article' => $article, 'error' => $exception->getMessage(), 'user' => $this->user]);
                return;
            }

            header('Location: /phpMVC/articles/' . $article->getId(), true, 302);
            exit;
        }
        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function add(): void
    {
        if($this->user === null){
            throw new UnauthorizedException('Vous n\'avez pas droit d\'accéder à cette page.');
        }

        if(!empty($_POST)){
            try{
                $article = Article::createPostFromArray($_POST, $this->user);
            }
            catch(InvalidArgumentException $exception){
                $this->view->renderHtml('articles/add.php', ['error' => $exception->getMessage()]);
                return;
            }
            header('Location: /phpMVC/articles/' . $article->getId());
            exit();

        }

        $this->view->renderHtml('articles/add.php');
    }
    public function delete(int $articleId)
    {
        $article = Article::getOneById($articleId);

        if($article === null ){
            throw new NotFoundException("L'article n'est pas trouvé.");
        }else{
            $article->delete();
            $this->view->renderHtml('/articles/delete.php', ['id' => $articleId]);
        }
    }
}