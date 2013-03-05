<?php if ($this->msg): ?>
    <?php echo $this->msg; ?>
<?php else: ?>
<form action="user/editother" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Wyglad</legend>
<dl class="textinput">
							<dt>Wzrost</dt>
							<dd>
                            <select name="height"> <?php for ($i = 74; $i < 272; ++$i): ?>
                            <option <?php echo $this->user['height'] == $i ? 'selected="selected"' : false ?>value="<?php echo $i; ?>"><?php echo $i; ?> cm</option>
                            <?php endfor; ?>
                            </select>
                          </dd>
						</dl>

                        <dl class="textinput">
							<dt>Waga</dt>
							<dd><select name="weight"> <?php for ($i = 20; $i < 560; ++$i): ?>
                            <option <?php echo $this->user['weight'] == $i ? 'selected="selected"' : false ?> value="<?php echo $i; ?>"><?php echo $i; ?> kg</option>
                            <?php endfor; ?>
                            </select></dd>
						</dl>
                        <dl class="textinput">
							<dt>Plec</dt>
							<dd><select name="sex">
                            <option value="0">Kobieta</option>
                            <option <?php echo $this->user['sex'] == 1 ? 'selected="selected"' : false ?>value="1">Mężczyzna</option></select></dd>
						</dl>
                        <dl class="textinput">
							<dt>Kolor oczu</dt>
							<dd>
                            <select name="eyecolor">
                            <?php foreach($this->eyecolors as $key=>$val): ?>
                            <option <?php echo $this->user['eyecolor'] == $key ? 'selected="selected"' : false ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                             <?php endforeach;?>
</select>
</dd>
						</dl>
                        <dl class="textinput">
							<dt>Kolor wlosów</dt>
                            <dd>
                            <select name="haircolor">
                        <?php foreach($this->haircolors as $key=>$val): ?>
                            <option <?php echo $this->user['haircolor'] == $key ? 'selected="selected"' : false ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                             <?php endforeach;?>
</select></dd>

						</dl>
                        <dl class="textinput">
							<dt>Wiek</dt>
							<dd><select name="age"> <?php for ($i = 15; $i < 120; ++$i): ?>
                            <option <?php echo $this->user['age'] == $i ? 'selected="selected"' : false ?> value="<?php echo $i; ?>"><?php echo $i; ?> lat</option>
                            <?php endfor; ?>
                            </select></dd>
						</dl>
                        </fieldset>
                        <fieldset><legend>Inne</legend>

                        <dl class="textinput">
							<dt>Wykształcenie</dt>
							<dd>
                            <select name="education">
                                <?php foreach($this->education as $key=>$val): ?>
                            <option <?php echo $this->user['education'] == $key ? 'selected="selected"' : false ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                             <?php endforeach;?>
                            </select></dd>
						</dl>
                        <dl class="textinput">
							<dt>Praca</dt>
							<dd><input name="job" type="text" value="<?php echo $this->user['job']?>" class="txtBox" /></dd>
						</dl>
                        <dl class="textinput">
							<dt>Status</dt>
							<dd></dd>
						</dl>
                        <dl class="textinput">
							<dt>O mnie</dt>
							<dd><textarea name="about"><?php echo $this->user['about']?></textarea></dd>
						</dl>
                        <dl class="textinput">
							<dt>Zajmuje się</dt>
							<dd><textarea name="tasks"><?php echo $this->user['tasks']?></textarea></dd>
						</dl>
                        <dl class="textinput">
							<dt>Avatar</dt>
							<dd><input type="file" name="avatar" /></dd>
                            <dd>Aktualny avatar: <br />
                            <img src="./uploads/avatars/<?php echo $this->user['avatar']?>" /></dd>
						</dl>
                        </fieldset>
                        <fieldset><legend>Kontakt</legend>
                        <dl class="textinput">
							<dt>GG</dt>
							<dd><input name="gg" type="text" value="<?php echo $this->user['gg']?>" class="txtBox" /></dd>
						</dl>
                        <dl class="textinput">
							<dt>Skype</dt>
							<dd><input name="skype" type="text" value="<?php echo $this->user['skype']?>" class="txtBox" /></dd>
						</dl>
                        <dl class="textinput">
							<dt>Telefon</dt>
							<dd><input name="phone" type="text" value="<?php echo $this->user['phone']?>" class="txtBox" /></dd>
						</dl>
                        </fieldset>
                        <div class="btn" style="clear: both;"><input type="submit" value="rejestruj" class="btn" /></div>
                        </form>

                        <?php endif; ?>