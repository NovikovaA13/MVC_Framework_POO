<?php include __DIR__ . '../../header.php';
?>
<?php if(!empty($flushMessage)):?>
    <p style="color: blueviolet"><?=$flushMessage?></p>
<?php endif;?>
    <h1><?=$article->getTitle()?></h1>
    <p><?=$article->getText()?></p>
    <p>Par <?=$article->getAuthor()->getNickname()?></p>
    <hr>
<?php
if($user->isAdmin() || $user->getId() == $article->getAuthorId()):
?>
    <a href="/phpMVC/articles/<?=$article->getId()?>/edit">Edit</a></p>
    <p><a href="/phpMVC/articles/<?=$article->getId()?>/delete">Delete</a></p>
<?php endif;?>

<?php
if($user):
?>
   <div>
       <form method="post">
           <input type="text" name="text">
           <input type="submit">
       </form>
   </div>
<?php endif;?>
<?php include __DIR__ . '../../footer.php'; ?>