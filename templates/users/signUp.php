<?php include __DIR__ . '/../header.php'; ?>
    <div style="text-align: center;">
        <h1>Inscription</h1>
        <?php if(!empty($error)):?>
        <p style="color: red"><?=$error?></p>
        <?php endif;?>
        <form action="" method="post">
            <label>Nickname: <input type="text" name="nickname" value="<?=$_POST['nickname'] ?? ''?>"></label><br><br>
            <label>Email: <input type="text" name="email" value="<?=$_POST['email'] ?? ''?>"></label>
            <br><br>
            <label>Password: <input type="password" name="password" value="<?=$_POST['password'] ?? ''?>"></label>
            <br><br>
            <input type="submit" value="S'inscrire">
        </form>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>