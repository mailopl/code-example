<h2>Rejestracja</h2>
<?php if ($this->msg): ?>
    <?php echo $this->msg; ?>
<?php else: ?>
<ul>
    <?php if (!empty($errors)):
    foreach($errors as $error): ?>
        <li><?php echo $error ?></li>
    <?php endforeach; endif;?>
</ul
<form action="user/register" method="post">
<dl class="textinput">
							<dt>Login (wymagany)</dt>
							<dd><input name="login" type="text" value="" class="txtBox" /></dd>
						</dl>
						<dl class="textinput">
							<dt>Haslo (wymagane)</dt>
							<dd><input name="password" type="password" class="txtBox" /></dd>
						</dl>
						<dl class="textinput">
							<dt class="multiline">Powtórz haslo (musi sie zgadzac<br />z poprzednim polem)</dt>
							<dd><input name="password2" type="password" class="txtBox" /></dd>
						</dl>
						<dl class="textinput">
							<dt>Adres e-mail (wymagany)</dt>
							<dd><input name="email" type="text" value="" class="txtBox" /></dd>
						</dl>
						<dl class="textinput">
							<dt class="multiline">Powtórz adres e-mail (musi sie<br />zgadzac z poprzednim polem)</dt>
							<dd><input name="email2" type="text" value="" class="txtBox" /></dd>
						</dl>
						<dl class="textinput">
							<dt>Imie (wymagane)</dt>
							<dd><input name="first_name" type="text" value="" class="txtBox" /></dd>
						</dl>
						<dl class="textinput">
							<dt>Nazwisko</dt>
							<dd><input name="second_name" type="text" value="" class="txtBox" /></dd>
						</dl>
						<dl class="textinput">
							<dt>Plec (wymagana)</dt>
							<dd>  <select name="sex">
                            <option value="0">Kobieta</option>
                            <option value="1">Mezczyzna</option>
                            </select>
                           </dd>
						</dl>
						<!--<dl class="textinput">
							<dt class="multiline" style="width: 45%;">Data urodzenia (DD-MM-RRRR,<br />wymagana)</dt>
							<dd><input name="userbirthdayday" type="text" value="" class="txtBox2">
							-
							<select name="userbirthdaymonth" class="select">
								<option value="1">stycznia</option>
								<option value="2">lutego</option>
								<option value="3">marca</option>
								<option value="4">kwietnia</option>
								<option value="5">maja</option>
								<option value="6">czerwca</option>
								<option value="7">lipca</option>
								<option value="8">sierpnia</option>
								<option value="9">wrzesnia</option>
								<option value="10">pazdziernika</option>
								<option value="11">listopada</option>
								<option value="12">grudnia</option>
							</select>
							-
							<input name="userbirthdayyear" type="text" value="" class="txtBox2"></dd>
						</dl>-->
						<dl class="textinput">
							<dt>Miasto (wymagane)</dt>
							<dd>
                            <?php /*<select name="city_id">
                            <option value="0">Inne</option>
                            <?php foreach($this->cities as $city){echo '<option value="'.$city['id'].'">' . $city['name'].'</option>';}//echo form::dropdown('city_id', $cities); ?>
</select>*/ ?>
<input type="text" name="city" />
                            </dd>
						</dl>
						<dl class="textinput">
							<dt>Województwo (wymagane)</dt>
							<dd>
								<select name="region_id" class="select">
									<option value="16" selected="selected">dolnoslaskie</option>
									<option value="1">kujawsko-pomorskie</option>
									<option value="2">lubelskie</option>
									<option value="3">lubuskie</option>
									<option value="4">lódzkie</option>
									<option value="5">malopolskie</option>
									<option value="6">mazowieckie</option>
									<option value="7">opolskie</option>
									<option value="8">podkarpackie</option>
									<option value="9">podlaskie</option>
									<option value="10">pomorskie</option>
									<option value="11">slaskie</option>
									<option value="12">swietokrzyskie</option>
									<option value="13">warminsko-mazurskie</option>
									<option value="14">wielkopolskie</option>
									<option value="15">zachodniopomorskie</option>
								</select>
							</dd>
						</dl>

                        <p>Ile jest <?=$val1=rand(1,10);?> <img src="elements/img/secret.png" /> <?=$val2=rand(1,10);?> ? <input type="text" name="captcha"/></p>
    <input type="hidden" value="<?=md5($val1+$val2+5)?>" name="md5" />

						<div class="btn" style="clear: both;"><input type="submit" value="rejestruj" class="btn" /></div>

</form>
<?php endif; ?>