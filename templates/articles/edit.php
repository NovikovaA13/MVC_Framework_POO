<?php include __DIR__ . '/../header.php'; ?>
<div style="text-align: center;">
    <h1>Modification d'un article</h1>
    <?php if(!empty($error)):?>
        <p style="color: red"><?=$error?></p>
    <?php endif;?>
    <form action="/phpMVC/articles/<?=$article->getId()?>/edit" method="post">
        <label>Title: <input type="text" name="title" value="<?=$_POST['title'] ?? $article->getTitle()?>"></label><br><br>
        <label>Text: <textarea name="text"><?=$_POST['text'] ?? $article->getText()?></textarea>
            <br><br>
            <input type="submit" value="Modifier">
    </form>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
