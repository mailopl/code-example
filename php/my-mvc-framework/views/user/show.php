<?php if ($this->msg):?>
<?php echo $this->msg; ?>
<?php else: ?>
<h2>Profil: <?php echo $this->user['login']?></h2>
Imie: <?php echo $this->user['first_name']?><br />
Nazwisko:<?php echo $this->user['second_name']?><br />
Plec:<?php echo $this->user['sex'] ? 'Mezczyzna' : 'Kobieta'?><br />
Wiek:<?php echo $this->user['age']?><br />
Skad:<?php echo $this->user['cityName']?><br />
GG:<?php echo $this->user['gg']?><br />
Status:<?php echo strtotime($this->user['last_login_times']) +900 > time() ? 'online' : 'offline'?><br /><br />


<h2>Miejsca</h2>
<?php if (!empty($this->places[0])): ?>
<?php foreach($this->places as $place): ?>
<?php echo $place['name']; ?><br />
<?php endforeach; ?>
<?php else: ?>
Brak
<?php endif ?>
<h2>Ogloszenia</h2>
<?php if (!empty($this->adverts[0])): ?>
<?php foreach($this->adverts as $ad): ?>
<a href="./announcement/<?php echo $ad['id']; ?>/"><?php echo $ad['title']; ?></a><br />
<?php endforeach; ?>
<?php else: ?>
Brak
<?php endif ?>

<h2>Zdjecia</h2>
<?php if (!empty($this->photos[0])): ?>
<?php foreach($this->photos as $photo): ?>
    <img src="./upload/photos/<?php echo $photo['source']; ?>" title="<?php echo $photo['title']; ?>"/><a href="<?php echo $photo['source']; ?>"><?php echo $photo['title']; ?></a> </em>
<?php endforeach; ?>
<?php else: ?>
Brak
<?php endif ?>

<h2>Komentarze</h2>
<?php if (!empty($this->comments[0])): ?>
<?php foreach($this->comments as $comment): ?>
    Napisany przez <a href="./user/show/<?php echo $comment['user_id']; ?>"><?php echo $comment['login']; ?></a> <em><?php echo $comment['content']; ?></em>
<?php endforeach; ?>
<?php else: ?>
Brak
<?php endif ?>

<?php if($this->loggedin): ?>
    <h2>Komentuj</h2>
    <form action="./user/comment" method="post">
    <input type="hidden" name="refer_id" value="<?php echo $_GET['id']; ?>">
    <textarea name="content"></textarea>
    <p>Ile jest <?=$val1=rand(1,10);?> <img src="elements/img/secret.png" /> <?=$val2=rand(1,10);?> ? <input type="text" name="captcha"/></p>
    <input type="hidden" value="<?=md5($val1+$val2+5)?>" name="md5" />
    <input type="submit" name="submit" value="Dodaj" />
    </form>
<?php endif;?>

<?php if (!$this->self): ?>
Akcje:
<a href="./user/block/<?php echo $this->user['id']?>">zablokuj</a> |
<a href="./user/unblock/<?php echo $this->user['id']?>">odblokuj</a> |
<a href="./pm/new/<?php echo $this->user['id']?>">napisz wiadomosc</a> |
<?php if($this->isFriend): ?>
<a href="./user/deleteFriend/<?php echo $this->user['id']?>">usun ze znajomych</a>
<?php else: ?>
<a href="./user/addFriend/<?php echo $this->user['id']?>">dodaj do znajomych</a>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>