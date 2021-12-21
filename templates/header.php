<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Blog</title>
    <link rel="stylesheet" src="../public/styles.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="/phpMVC/">Tous les articles</a></li>
        <li><a href="/phpMVC/articles/add">Ajouter un article</a></li>
    </ul>
</nav>
<table class="layout">
    <tr>
        <td colspan="2" class="header">
       <h2>Mon Blog</h2>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right">
       <p><?php if(!empty($user)) :?>
           Hello, <?=$user->getNickname()?> | <a href="/phpMVC/users/logout">Logout</a>
           <?php else :?>
           <a href="/phpMVC/users/login">S'identifier</a> | <a href="/phpMVC/users/register">S'enregistrer</a>
           <?php endif;?>
       </p>
            </td>
    </tr>
        <td>