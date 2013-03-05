<a href="./announcements/show/userid/<?php echo $this->user['id']; ?>">moje ogłoszenia</a><br />
<a href="./photos/show/userid/<?php echo $this->user['id']; ?>">moje zdjęcia</a><br />
><a href="./photos/add">dodaj zdjecie</a><br />
<a href="./places/show/userid/<?php echo $this->user['id']; ?>">moje miejsca</a><br />
><a href="./places/add">dodaj miejsce</a><br />
<a href="./user/editmain">edytuj profil</a><br />
<a href="./user/editother">edytuj pozostałe</a><br />
<a href="./user/blackList">pokaż blacklistę</a><br />
<a href="./plans/add">dodaj powiadomienie</a><br />
<a href="./user/logout">wyloguj</a><br />

<br /><br />
<a href="./pm/new">nowa wiadomość</a><br />
<a href="./pm/inbox">odebrane</a><br />
<a href=./pm/sent"">wysłane</a><br />
<hr />


<b>Znajomi online:<br /></b>
<?php if(!empty($this->online[0])): ?>
<?php foreach($this->online as $friend): ?>
<a href="./user/show/<?php echo $friend['id']; ?>"><?php echo $friend['login']?></a>
<?php endforeach; ?>
<?php else: ?>
Brak znajomych online.
<?php endif; ?>
<hr />
<b>Ostatnio Twoj profil odwiedzili:</b><br />
<?php if(!empty($this->visitors[0])): ?>
<?php foreach($this->visitors as $v): ?>
<a href="./user/show/<?php echo $v['id']; ?>"><?php echo $v['login']?> (<?php echo $v['last_visit_time']?>)</a>
<?php endforeach; ?>
<?php else: ?>
Brak odwiedzin.
<?php endif; ?>
<hr />
<b>Ostatnio dodane zdjecia:</b><br />
<?php if(!empty($this->photos[0])): ?>
<?php foreach($this->photos as $p): ?>
<a href="./photo/show/<?php echo $p['id']; ?>">
    <img alt="" src="../upload/photos/<?php echo $p['source']?>" />
</a>
<?php endforeach; ?>
<?php else: ?>
Brak ostatnio dodanych zdjęć.
<?php endif; ?>
<hr />

<b>Ostatnio dodane plany:</b><br />
<?php if(!empty($this->plans[0])): ?>
<?php foreach($this->plans as $plan): ?>
<?php echo $plan['content']?> data:<?php echo $plan['add_date']?><br />
<?php endforeach; ?>
<?php else: ?>
Brak.
<?php endif; ?>
<hr />
<b>Oczekujące zaproszenia do znajomych:</b><br />
<?php if(!empty($this->invitations[0])): ?>
<?php foreach($this->invitations as $inv): ?>
<?php echo $inv['first_name']?> <?php echo $inv['second_name']?> <a href="./user/acceptFriend/<?php echo $inv['id']?>">akceptuj</a> | <a href="./user/denyFriend/<?php echo $inv['id']?>">odrzuć</a><br />
<?php endforeach; ?>
<?php else: ?>
Brak.
<?php endif; ?>