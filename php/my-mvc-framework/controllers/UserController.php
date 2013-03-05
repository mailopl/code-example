<?php
/**
* Kontroler uzytkownika, rzeczy zwiazane tylko z uzytkownikiem, rejestracja, logowanie, komentowanie profilu...
**/

class UserController extends BaseController{
    public function __controller(){
        parent::_controller();
    }

    /**
    * Pokaz profil
    **/
    public function showAction(){
        /**
        * Jesli koles ktory chce obejrzec profil, jestn a czarnej liscie - nie moze go obejrzec.
        * Jesli jestes zalogowany i ogladasz profil, i ten profil jest twoim znajomym, zaktualizuj
        * date ostatniej wizyty.
        **/

        if ($this->_is_logged_in()){
              $c = $this->db->proc("
                SELECT user_id FROM users_friends_blacklist
                WHERE user_id=? AND banned_id=?", $_SESSION['id'], $_GET['id']
              );

              if ($c[0][0]){
                $this->view->assign("msg", "Jesteś zbanowany przez tego uzytkownika.");
                return;
              }

              $this->db->proc("
                UPDATE users_friends SET last_visit_time = NOW()
                WHERE (user_id=? AND friend_id=?) OR
                (user_id=? AND friend_id=?)",
                $_SESSION['id'], $data['id'],$data['id'], $_SESSION['id']
              );

        }
        /**
        * Pobierz dane usera
        **/
        $data = $this->db->proc('
        SELECT users.*, cities.name as cityName FROM users
        INNER JOIN cities ON cities.id = users.city_id
        WHERE users.id=?', $_GET['id']);

        $this->view->assign('user', $data[0]);

        /**
        * Pobierz komentarze usera
        **/
        $comments =
            $this->db->proc("
            SELECT comments.user_id, comments.content, users.login FROM comments
            INNER JOIN users ON comments.refer_id = users.id
            WHERE comments.type='profile'");
        $this->view->assign('comments', $comments);

        /**
        * Pobierz ogloszenia usera
        **/
        $adverts =
            $this->db->proc("
            SELECT announcements.* FROM announcements
            INNER JOIN users ON announcements.user_id = users.id
            WHERE users.id = ?
           ", intval($_GET['id']));
        $this->view->assign('adverts', $adverts);

        /**
        * Pobierz zdjecia usera
        **/
        $photos =
            $this->db->proc("
            SELECT photos.title, photos.views, photos.source, photos.add_date
            FROM users
            INNER JOIN users_photos ON users.id = users_photos.user_id
            INNER JOIN photos ON photos.id = users_photos.photo_id
            WHERE photos.type =  'profile' AND  users.id = ?
           ", intval($_GET['id']));
        $this->view->assign('photos', $photos);

        /**
        * Pobierz miejsca usera
        **/
        $places =
            $this->db->proc("
           SELECT places.* FROM places
            INNER JOIN users_places ON users_places.place_id = places.id
            INNER JOIN users ON users.id = users_places.user_id
            WHERE users.id = ?
           ", $_GET['id']);

        $this->view->assign('places', $places);
        $this->view->assign("loggedin", $this->_is_logged_in() ? 1 : 0);

        if ($_SESSION['id'] == $_GET['id']){
          $this->view->assign("self", 1);
          return;
        }

        $c = $this->db->proc("SELECT COUNT(1) FROM users_friends WHERE (user_id = ? and friend_id=?) or (user_id = ? and friend_id=?)", $_SESSION['id'], $_GET['id'], $_GET['id'], $_SESSION['id']);
        if ($c[0][0]){
          $this->view->assign("isFriend", 1);
        }
    }

    /**
    * Komentarz do profilu
    **/
    public function commentAction(){
        if (!$this->_is_logged_in())
        {
            $this->view->assign("msg", "Musisz byc zalogowany by komentowac.");
        }else
        {
            $this->db->proc("
            INSERT INTO comments
            (user_id, refer_id, type, content)
            VALUES(?, ?, ?, ?);",
            intval($_SESSION['id']),
            intval($_POST['refer_id']),
            'profile',
            $_POST['content']);

            $this->view->assign("msg", "Komentarz dodany.");
            //dodac anty-spam
        }
    }

    /**
    * Sprawdza, czy uzytkownik jest zalogowany
    **/
    public function _is_logged_in(){
        global $_SESSION;
        global $_SERVER;

        if (
            $_SESSION['ip'] == $_SERVER['REMOTE_ADDR'] &&
            $_SESSION['ua'] == $_SERVER['HTTP_USER_AGENT'] &&
            $_SESSION['id'] &&
            $_SESSION['loggedin']){
              return true;
            }
        return false;
    }

    public function logoutAction(){
        session_destroy();
        header("Location: /");
    }

    /**
    * Przeprowadza login
    **/
    public function loginAction(){
        if ($this->_is_logged_in()) header("Location: /");

        if (isset($_POST))
        {
            if ($this->_login($_POST['login'], $_POST['password'])){
                header("Location: " . $this->config->baseURL);
            }else{
              $errors[]='Niepoprawne dane.';
              $this->view->assign("errors", $errors);
            }
        }
    }

    /**
    * Właściwy login
    **/
    public function _login($login, $pass){
        global $_SERVER, $_SESSION;
        if (!empty($_POST))
        {
          $count = $this->db->proc("SELECT id FROM users WHERE login=? AND password=?", $login, md5($pass));
          if ($count[0][0]){
              $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
              $_SESSION['id'] = $count[0][0];
              $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
              $_SESSION['loggedin'] = true;
              $_SESSION['login_time'] = time();
              return true;
          }
        }
        return false;

    }

    /**
    * Rejestracja uzytkownika
    **/
    public function registerAction(){
       global $_POST;
       if ($this->_is_logged_in()) header("Location: /");

        if (isset($_GET['slug']))
        {
            $this->db->proc("UPDATE users SET active=1 AND confirm_code ='' WHERE confirm_code=?", $_GET['slug']);
            $this->view->assign("msg", "Twoje konto zostalo niniejszym aktywowane.");
        }
        else if ( isset( $_POST ) )
        {
            $user = $_POST;
            if (
                empty( $user[ 'login' ] ) or
                strlen( $user[ 'login' ] ) < 3 or
                strlen( $user[ 'login' ] ) > 20 or
                $this->_user_exists( $user[ 'login' ] ) )
                {
                    $errors[] = 'user login';
                }
            else if (
                empty( $user[ 'password' ] ) or
                strlen( $user[ 'password' ] ) < 3 or
                strlen( $user[ 'password' ] ) > 20 )
                {
                    $errors[] = 'password';
                }
            else if (
                empty( $user[ 'email' ] ) or
                strlen( $user[ 'email' ] ) < 5 or
                strlen( $user[ 'email' ] ) > 40 or
                $this->_email_ok( $user[ 'email' ] ) )
                {
                    $errors[] = 'email';
                }
            else if (
                empty( $user[ 'login' ] ) or
                strlen( $user[ 'login' ] ) < 3 or
                strlen( $user[ 'password' ] ) > 20 )
                {
                    $errors[] = 'login';
                }
            else if (md5($_POST['captcha']+5) != $_POST['md5'])
                {
                    $errors[] = 'captcha';
                }
             //Sprawdz czy takie maisto jest, jesli nie - dodaj je
            $city = $db->proc("SELECT id FROM cities WHERE name=?", $user['city']);
            $city = $city[0][0];

            if (!$city){
              $db->proc("INSERT INTO cities (name) VALUES(?)", $user['city']);
              $city = $db->proc("SELECT LAST_INSERT_ID() FROM cities");
              $city = $city[0][0];
            }

            if ( empty( $errors ) ) {
              $code = rand(1000,9999);

              $this->db->proc( "
                  INSERT INTO users (login, first_name, second_name, email, password, region_id, city_id,confirm_code)
                  VALUES(?, ?, ?, ?, ?, ?,?,?);",
                  $user[ 'login' ],
                  $user[ 'first_name' ],
                  $user[ 'second_name' ],
                  $user[ 'email' ],
                  md5($user[ 'password' ]),
                  $user[ 'region_id' ],
                  $city,
                  $code
              );

              @mail(
                $user[ 'email' ],
                'Aktywacja widziani.pl',
                'W celu aktywowania konta kliknij <a href="http://widziani.pl/user/register/'.$code.'">tutaj</a>.'
              );

              $this->view->assign("msg", "Twoje konto zostalo zalozone. Pora je aktywowac poprzez link który dostales na poczte.");
            }
        }
    }
     /**
     * Sprawdzanie formatu emaila
     **/
    public function _email_ok($email){
        if (!preg_match("/^( [a-zA-Z0-9] )+( [a-zA-Z0-9\._-] )*@( [a-zA-Z0-9_-] )+( [a-zA-Z0-9\._-] +)+$/" , $email)) {
            return false;
        }
        return true;
    }

    /**
    * Sprawdzanie czy login istnieje
    **/
    public function _user_exists( $login )
    {
        $count = $this->db->proc( "SELECT COUNT(id) FROM users WHERE login=?", $login );
        return $count[ 0 ][ 0 ];
    }

    /**
    * Edycja podstawowych danych profilu
    **/
    public function editMainAction(){
        if (!$this->_is_logged_in()){
            $this->view->assign("msg", "Musisz być zalogowany.");
            return;
        }

        if (!empty($_POST)){
            if (empty($_POST['password']))
            {
                $msg = 'Musisz podać hasło.';
            }else if (empty($_POST['email']) || $this->_email_ok($_POST['email']))
            {
                $msg = 'Musisz podać email.';
            }else if (empty($_POST['city']))
            {
                $msg = 'Musisz podać email.';
            }

            //Sprawdzanie czy miasto istnieje, jesli nie - dodaje
            $city_id = $this->db->proc("SELECT id FROM cities WHERE name=?", $_GET['city']);
            if (!$city_id[0][0]){
              $db->proc("INSERT INTO cities (name) VALUES(?)", $_GET['city']);
              $city = $db->proc("SELECT LAST_INSERT_ID() FROM cities");
              $city_id = $city[0][0];
            }

            $this->db->proc(
                "UPDATE users SET
                login=?, first_name=?, second_name=?,
                email=?, password=?, region_id=?, city_id=?,
                confirm_code=?;",
                $user[ 'login' ],
                $user[ 'first_name' ],
                $user[ 'second_name' ],
                $user[ 'email' ],
                md5($user[ 'password' ]),
                $user[ 'region_id' ],
                $city_id,
                $code
            );
      }else{
            $user =  $this->db->proc("SELECT * FROM users WHERE id=?", $_SESSION['id']);
            $this->view->assign("user",$user[0]);
      }
    }

    /**
    * Edycja pozostalych danych usera, wszystkie opcjonalne
    **/
    public function editOtherAction(){
      global $_POST, $_FILES;
        if (!empty($_POST)){
            //tutaj zreczy avatarowe
            //$avatar =

            if (!empty($_FILES)){
                if ($_FILES['avatar']['size'] > 150*1024) {
                    $msg = 'Rozmiar musi być mniejszy niż 150Kb';
	                @unlink($_FILES['avatar']['tmp_name']);
                }
                if($_FILES['avatar']['type'] != "image/jpeg") {
                   $msg = 'Jedynie format JPG jest obsługiwany.';
	               @unlink($_FILES['avatar']['tmp_name']);
                }
                if (!$msg){
                    $filename = $dest = "./uploads/avatars/".md5($_FILES['avatar']['name'] . time()).'.jpg';
                    $avatar = md5($_FILES['avatar']['name'] . time()).'.jpg';
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)){
                        $i = strrpos($dest,".");
                        $l = strlen($dest) - $i;
                        $ext = substr($dest,$i+1,$l);

                         if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))
                            $src_img=imagecreatefromjpeg($dest);

                        // if(!strcmp("png",$ext))
                          //  $src_img=imagecreatefrompng($dest);
                            $new_w = $new_h = 100;
                            $old_x=imageSX($src_img);
                            $old_y=imageSY($src_img);

                            $ratio1=$old_x/$new_w;
                            $ratio2=$old_y/$new_h;

                            if($ratio1>$ratio2) {
                                $thumb_w=$new_w;
                                $thumb_h=$old_y/$ratio1;
                            }
                            else {
                                $thumb_h=$new_h;
                                $thumb_w=$old_x/$ratio2;
                            }
                            $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
                            imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


                            if(!strcmp("png",$ext)) imagepng($dst_img,$filename);
                            else imagejpeg($dst_img,$filename);

                            imagedestroy($dst_img);
                            imagedestroy($src_img);
	                   }
                }else echo $msg;
            }

           $this->db->proc("
            UPDATE users SET
            height=?, weight=?, sex=?,
            eyecolor=?,haircolor=?,age=?,
            education=?,job=?,about=?,tasks=?,
            avatar=?,gg=?,skype=?,phone=?
            WHERE id=?",
                $_POST['height'],
                $_POST['weight'],
                $_POST['sex'],
                $_POST['eyecolor'],
                $_POST['haircolor'],
                $_POST['age'],
                $_POST['education'],
                $_POST['job'],
                $_POST['about'],
                $_POST['tasks'],
                $avatar,
                $_POST['gg'],
                $_POST['skype'],
                $_POST['phone'],
                $_SESSION['id']);
            $this->view->assign("msg","Dane zaktualizowane.");
        }else{
            include "./config/data.php";
            $this->view->assign("eyecolors", $data_eyecolors);
            $this->view->assign("haircolors", $data_haircolors);
            $this->view->assign("education", $data_education);
            $user =  $this->db->proc("SELECT * FROM users WHERE id=?", $_SESSION['id']);
            $this->view->assign("user",$user[0]);
        }
    }

    public function manageAction(){
      //panel zarzadzania uzytkownikiem
      //edycja danych, odawanie ogloszen, miejsc, wyszukiwanie, wysylanie i odbieranie wiadomosci, pokazuje znajomych, oraz tych ktorzy ostatnio odwiedzili profil, powiadomienia od admina, ostatnio dodane zdjecia znajomych jak w nk, opcja black listy, usuniecie konta, moduł powiadom

        /**
        * Pobierz zdjecia ostatnio dodane
        **/
        $photos =
            $this->db->proc("
            SELECT
            photos.add_date,
            photos.id,
            photos.source,
            photos.title
            FROM
            users_photos
            Inner Join users_friends ON users_photos.user_id = users_friends.friend_id
            Inner Join users ON users_friends.user_id = users.id
            Inner Join photos ON photos.id = users_photos.photo_id
            WHERE users_friends.user_id =? OR users_friends.friend_id = ?
            ORDER BY photos.add_date DESC
            LIMIT 5
           ", intval($_SESSION['id']), intval($_SESSION['id']));
        $this->view->assign('photos', $photos);

        $visits =
            $this->db->proc("
              SELECT
              users.login,
              users.id,
              users.first_name,
              users.second_name,
              users_friends.last_visit_time
              FROM
              users_friends
              Inner Join users ON users_friends.friend_id = users.id
              WHERE
              (users_friends.user_id =  ? or users_friends.friend_id=?)
              AND (users.id != ?) AND users_friends.active=1
              ORDER BY
              users_friends.last_visit_time DESC
              LIMIT 3", intval($_SESSION['id']),intval($_SESSION['id']),intval($_SESSION['id'])
        );

        if ($visits) $vc = count($visits);
        for($i = 0; $i < $vc; ++$i){
            $visits[$i]['last_visit_time'] = $this->czas_relatywny($visits[0]['last_visit_time']);
        }

        $this->view->assign('visitors', $visits);

        $friendsonline = $this->db->proc("
            SELECT
            users.first_name,
            users.second_name,
            users.login
            FROM
            users
            Inner Join users_friends ON users.id = users_friends.friend_id
            WHERE
            (users_friends.user_id =  ? OR
            users_friends.friend_id = ?)
            AND (users.id != ?)
            AND users.last_login_times >= NOW() - INTERVAL 15 MINUTE
            ORDER BY
            users.last_login_times DESC
            LIMIT 10", intval($_SESSION['id']),intval($_SESSION['id']),intval($_SESSION['id']));
        $this->view->assign('online', $friendsonline);
        $plans = $this->db->proc("
            SELECT
            plans.content,
            plans.add_date,
            plans.user_id,
            users.login
            FROM
            users_friends
            Inner Join plans ON plans.user_id = users_friends.friend_id
            Inner Join users ON users_friends.friend_id = users.id
            WHERE
            (users_friends.user_id =  ?
            OR users_friends.friend_id = ?)
            AND users.id != ?
            AND users_friends.active=1
            ORDER BY
            plans.add_date ASC
            LIMIT 6", intval($_SESSION['id']), intval($_SESSION['id']), intval($_SESSION['id']));

        if($plans) $pc = count($plans);
        for($i = 0; $i < $pc; ++$i){
            $plans[$i]['add_date'] = $this->czas_relatywny($plans[0]['add_date']);
        }
        $this->view->assign('plans', $plans);

        $invitations = $this->db->proc("
          SELECT users.id, users.first_name, users.second_name, users.login, uf.active
          FROM users_friends uf
          INNER JOIN users ON users.id = uf.user_id
          OR users.id = uf.friend_id
          WHERE ((uf.user_id =?) OR (uf.friend_id =?))
          AND uf.active =0
          AND users.id !=?
        ", $_SESSION['id'],$_SESSION['id'], $_SESSION['id']);
        $this->view->assign('invitations', $invitations);
    }
    public function blockAction(){
      if ($this->_is_logged_in()){
          $db->proc("INSERT INTO users_friends_blacklist(user_id, banned_id) VALUES(?, ?);", $_SESSION['id'], $_GET['id']);
          $this->view->assign("msg", "Użytkownik zablokowany");
        }else{
          $this->view->assign("msg", "Musisz być zalogowany.");
        }
    }
    public function unblockAction(){
        if ($this->_is_logged_in()){
          $db->proc("DELETE FROM users_friends_blacklist WHERE user_id=? and banned_id=?", $_SESSION['id'], $_GET['id']);
          $this->view->assign("msg", "Użytkownik odblokowany");
        }else{
          $this->view->assign("msg", "Musisz być zalogowany.");
        }
    }
    public function addFriendAction(){
      //TODO: a co sie dzieje tutaj, jak user 1 i user 2 zaprosza siebie nawzajem ? trzeba zrobic sprawdzanie.
       if ($this->_is_logged_in()){
          $db->proc("INSERT INTO users_friends(user_id, friend_id,active) VALUES(?, ?,0);", $_SESSION['id'], $_GET['id']);
          $this->view->assign("msg", "Użytkownik zaproszony do znajomych");
        }else{
          $this->view->assign("msg", "Musisz być zalogowany.");
        }
    }
    public function acceptFriendAction(){
       if ($this->_is_logged_in()){
          $db->proc("UPDATE users_friends SET active=1 WHERE (user_id =? and friend_id = ?) OR (user_id =? and friend_id = ?);", $_SESSION['id'], $_GET['id'],$_GET['id'],$_SESSION['id']);
          $this->view->assign("msg", "Użytkownik zaakceptowany.");
        }else{
          $this->view->assign("msg", "Musisz być zalogowany.");
        }
    }
    public function denyFriendAction(){
       if ($this->_is_logged_in()){
          $db->proc("DELETE FROM users_friends WHERE (user_id =? and friend_id = ?) OR (user_id =? and friend_id = ?) LIMIT 1;", $_SESSION['id'], $_GET['id'],$_GET['id'],$_SESSION['id']);
          $this->view->assign("msg", "Użytkownik usunięty ze znajomych");
        }else{
          $this->view->assign("msg", "Musisz być zalogowany.");
        }
    }
    public function deleteFriendAction(){
        if ($this->_is_logged_in()){
          $db->proc("DELETE FROM users_friends WHERE user_id=? and friend_id=?", $_SESSION['id'], $_GET['id']);
          $this->view->assign("msg", "Użytkownik usunięty ze znajomych");
        }else{
          $this->view->assign("msg", "Musisz być zalogowany.");
        }
    }
    public function blackListAction(){
      if ($this->_is_logged_in()){
          $users = $this->db->proc("SELECT
          users.login,
          users.second_name,
          users.first_name,
          users.id
          FROM
          users_friends_blacklist
          Inner Join users ON users_friends_blacklist.banned_id = users.id
          WHERE
          users_friends_blacklist.user_id =  ?", $_SESSION['id']);
          $this->view->assign('users', $users);
      }else
        $this->view->assign("msg", 'Musisz być zalogowany.');

    }
    public function deleteAccountAction(){
        if (!empty($_POST) && $this->_is_logged_in()){
            $db->proc("DELETE FROM users WHERE password=? AND id=? LIMIT 1;", md5($_POST['password']), $_SESSION['id']);
            session_destroy();
            $this->view->assign("msg", "Usunięto konto");
        }
    }


    public function czas_relatywny( $data_wejsciowa ) {

    	$roznica_czasu = time() - strtotime( $data_wejsciowa );
    	if( $roznica_czasu < 0 ) { die(); }
    	$okres = array('sekund', 'minut', 'godzin', null, 'dni');
    	$dlugosc = array(60,60,24,3,31);

    	for($j = 0; $roznica_czasu >= $dlugosc[$j]; $j++) { if( !isset($dlugosc[$j]) ) { break; } if( $j<3) { $roznica_czasu /= $dlugosc[$j]; } }
    	$roznica_czasu = round(abs($roznica_czasu));

    	switch( $j ) {
    		case 0: case 1: case 2:
    			switch( $roznica_czasu ) {
    				case 1: $okres[$j] .= 'a'; break;
    				case 2:case 3:case 4:case 22:case 23:case 24:case 32:case 33:case 34:case 42:case 43:case 44:case 52:case 53:case 54: $okres[$j] .= 'y'; break;
    			} break;
    		case 3:
    			switch( $roznica_czasu ) {
    				case 1: $okres[$j] = 'wczoraj'; break;
    				case 2: $okres[$j] = 'przedwczoraj'; break;
    				case 3: $j=4; break;
    			} break;
    	}

    	if( $j==0 or $j==1 or $j==2 or $j == 4 ) { return $roznica_czasu.' '.$okres[$j].' temu, '.date("H:i",strtotime( $data_wejsciowa )); }
    	elseif( $j == 3 ) { return $okres[$j].', '.date("H:i",strtotime( $data_wejsciowa )); }
    	elseif( $j == 5 ) { return date("d-m-Y H:i",strtotime( $data_wejsciowa )); }
    }


};
