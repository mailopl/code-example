<?php if($this->msg): ?>
<?php echo $this->msg; ?> <a href="./user/login">zaloguj</a>
<?php else:?>
<form action="user/editmain" method="post">
						<dl class="textinput">
							<dt>Haslo</dt>
							<dd><input name="password" type="password" class="txtBox"/></dd>
						</dl>

						<dl class="textinput">
							<dt>Adres e-mail </dt>
							<dd><input name="email" type="text"  class="txtBox" value="<?php echo $this->user['email']?>"/></dd>
						</dl>

						<dl class="textinput">
							<dt>Imie </dt>
							<dd><input name="first_name" type="text" class="txtBox" value="<?php echo $this->user['first_name']?>"/></dd>
						</dl>
						<dl class="textinput">
							<dt>Nazwisko</dt>
							<dd><input name="second_name" type="text"  class="txtBox" value="<?php echo $this->user['second_name']?>"/></dd>
						</dl>
						<dl class="textinput">
							<dt>Plec</dt>
							<dd>  <select name="sex">
                            <option value="0">Kobieta</option>
                            <option value="1" value="<?php echo $this->user['sex'] ? 'selected="selected"' : false ?>">Mezczyzna</option>
                            </select>
                           </dd>
						</dl>

						<dl class="textinput">
							<dt>Miasto (wymagane)</dt>
							<dd>
                            <input autocomplete="off" type="text" id="city" name="city" onkeyup="suggestCity(this.value);" />
                            <div id="dropdown"></div>
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

						<div class="btn" style="clear: both;"><input type="submit" value="Zapisz" class="btn" /></div>

</form>
<?php endif; ?>