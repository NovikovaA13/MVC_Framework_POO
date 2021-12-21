<?php
try{
    //la gestion des namespaces
    spl_autoload_register(function ($className){
        require_once  __DIR__ . '/src/' . $className . '.php';
    });
    //On fait un système de routage
    $route = $_GET['route'] ??  '';
    $routes = require(__DIR__. '/src/routes.php');//les routes disponibles
    $routeIsFound = false;
    //On parcourt les routes pour trouver la première route
    foreach ($routes as $pattern => $controllerAndAction){
        preg_match($pattern, $route, $matches);
        if(!empty($matches)){
            $routeIsFound = true;
            break;
        }
    }
    //Si route n'est pas trouvée on jette une exception
    if(!$routeIsFound){
        throw new \MyProject\Exceptions\NotFoundException('La page n\'est pas trouvée');
   }
    unset($matches[0]);//La correspondance complète, on supprime

    //À partir des $controllerAndAction on crée le controller et son action
    $controllerName = $controllerAndAction[0];
    $controllerAction = $controllerAndAction[1];
    $controller = new $controllerName();
    $controller->$controllerAction(...$matches);

}
//On gere les exceptions
catch(\MyProject\Exceptions\DbException $exception){
    $view = new MyProject\View\View('templates');
    $view->renderHtml('errors/500.php', ['error' =>$exception->getMessage()], 500);
}
catch (\MyProject\Exceptions\NotFoundException $exception){
    $view = new MyProject\View\View('templates');
    $view->renderHtml('errors/404.php', ['error' =>$exception->getMessage()], 404);
}
catch (\MyProject\Exceptions\UnauthorizedException $exception){
    $view = new MyProject\View\View('templates');
    $view->renderHtml('errors/401.php', ['error' =>$exception->getMessage()], 401);
}
catch (\MyProject\Exceptions\Forbidden $exception){
    $view = new MyProject\View\View('templates');
    $view->renderHtml('errors/403.php', ['error' =>$exception->getMessage()], 403);
}