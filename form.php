<style>
  .form1{
    max-width: 960px;
    text-align: center;
    margin: 0 auto;
  }
  .error {
    border: 2px solid red;
  }
  .hidden{
    display: none;
  }
  .button {
  font: bold 11px Arial;
  text-decoration: none;
  background-color: #EEEEEE;
  color: #333333;
  padding: 2px 6px 2px 6px;
  border-top: 1px solid #CCCCCC;
  border-right: 1px solid #333333;
  border-bottom: 1px solid #333333;
  border-left: 1px solid #CCCCCC;
}
</style>
<body>
<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}
?>
  <div class="form1">
  <form action="edit.php" method="POST">
    <label> ФИО </label> <br>
    <input name="fio" <?php //if ($errors_ar['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" /> <br>
    <label> Почта </label> <br>
    <input name="mail" type="email" <?php //if ($errors_ar['mail']) {print 'class="error"';} ?> value="<?php print $values['mail']; ?>"/> <br>
    <label> Год рождения </label> <br>
    <select name="year" <?php //if ($errors_ar['year']) {print 'class="error"';} ?>>
      <option value="Выбрать">Выбрать</option>
    <?php
        for($i=1890;$i<=2022;$i++){
          if($values['year']==$i){
            printf("<option value=%d selected>%d год</option>",$i,$i);
          }
          else{
            printf("<option value=%d>%d год</option>",$i,$i);
          }
        }
    ?>
    </select> <br>
    <!--<input name="year" type="date" /> <br>-->
    <label> Ваш пол </label> <br>
    <div <?php //if ($errors_ar['sex']) {print 'class="error"';} ?>>
      <input name="sex" type="radio" value="M" <?php if($values['sex']=="M") {print 'checked';} ?>/> Мужчина
      <input name="sex" type="radio" value="W" <?php if($values['sex']=="W") {print 'checked';} ?>/> Женщина
    </div>
    <label> Сколько у вас конечностей </label> <br>
    <div <?php //if ($errors_ar['limb']) {print 'class="error"';} ?>>
      <input name="limb" type="radio" value="1" <?php if($values['limb']=="1") {print 'checked';} ?>/> 1 
      <input name="limb" type="radio" value="2" <?php if($values['limb']=="2") {print 'checked';} ?>/> 2 
      <input name="limb" type="radio" value="3" <?php if($values['limb']=="3") {print 'checked';} ?>/> 3 
      <input name="limb" type="radio" value="4" <?php if($values['limb']=="4") {print 'checked';} ?>/> 4 
    </div>
    <label> Выберите суперспособности </label> <br>
    <select name="power[]" size="3" multiple <?php //if ($errors_ar['powers']) {print 'class="error"';} ?>>
      <option value="бессмертие" <?php if($values['immortal']==1){print 'selected';} ?>>Бессмертие</option>
      <option value="прохождение сквозь стены" <?php if($values['ghost']==1){print 'selected';} ?>>Прохождение сквозь стены</option>
      <option value="левитация" <?php if($values['levitation']==1){print 'selected';} ?>>Левитация</option>
    </select> <br>
    <label> Краткая биография </label> <br>
    <textarea name="bio" rows="10" cols="15"><?php print $values['bio']; ?></textarea> <br>
    <input name='dd' hidden value=<?php print($_GET['edit_id']);?>>
    <input type="submit" name='edit' value="Edit"/>
    <input type="submit" name='del' value="Delete"/>
  </form>
    <p>
    
    <a href='admin.php' class="button">Назад</a>

    </p>
  </div>
</body>