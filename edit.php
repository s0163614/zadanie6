<?php
//echo '<pre>';
//print_r($_SERVER);
//echo '</pre>';
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
require('connect.php');
$pass_hash=array();
try{
  $get=$db->prepare("select password from administration where username=?");
  $get->execute(array('admin'));
  $pass_hash=$get->fetchAll()[0][0];
}
catch(PDOException $e){
  print('Error: '.$e->getMessage());
}
if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      $_SERVER['PHP_AUTH_USER'] != 'admin' ||
      md5($_SERVER['PHP_AUTH_PW']) != $pass_hash) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}
if(empty($_GET['edit_id'])){
  header('Location: admin.php');
}
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
    setcookie('fio_value', '', 100000);
    setcookie('mail_value', '', 100000);
    setcookie('year_value', '', 100000);
    setcookie('sex_value', '', 100000);
    setcookie('limb_value', '', 100000);
    setcookie('bio_value', '', 100000);
    setcookie('immortal_value', '', 100000);
    setcookie('ghost_value', '', 100000);
    setcookie('levitation_value', '', 100000);
    setcookie('privacy_value', '', 100000);
  }
  //Ошибки
  
  $errors_ar = array();
  $error=FALSE;
  
  $errors_ar['fio'] = !empty($_COOKIE['fio_error']);
  $errors_ar['mail'] = !empty($_COOKIE['mail_error']);
  $errors_ar['year'] = !empty($_COOKIE['year_error']);
  $errors_ar['sex'] = !empty($_COOKIE['sex_error']);
  $errors_ar['limb'] = !empty($_COOKIE['limb_error']);
  $errors_ar['powers'] = !empty($_COOKIE['powers_error']);
  $errors_ar['privacy'] = !empty($_COOKIE['privacy_error']);
  if (!empty($errors_ar['fio'])) {
    setcookie('fio_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя.</div>';
    $error=TRUE;
  }
  if ($errors_ar['mail']) {
    setcookie('mail_error', '', 100000);
    $messages[] = '<div class="error">Заполните или исправьте почту.</div>';
    $error=TRUE;
  }
  if ($errors_ar['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Выберите год рождения.</div>';
    $error=TRUE;
  }
  if ($errors_ar['sex']) {
    setcookie('sex_error', '', 100000);
    $messages[] = '<div class="error">Выберите пол.</div>';
    $error=TRUE;
  }
  if ($errors_ar['limb']) {
    setcookie('limb_error', '', 100000);
    $messages[] = '<div class="error">Выберите сколько у вас конечностей.</div>';
    $error=TRUE;
  }
  if ($errors_ar['powers']) {
    setcookie('powers_error', '', 100000);
    $messages[] = '<div class="error">Выберите хотя бы одну суперспособность.</div>';
    $error=TRUE;
  }
  $values = array();
  $values['immortal']=0;
  $values['ghost']=0;
  $values['levitation']=0;
  //print_r(empty($_SESSION['login']).' '.$_COOKIE[session_name()].' '.empty($_SESSION['uid']));
  include('connect.php');
  try{
      $id=$_GET['edit_id'];
      $get=$db->prepare("select * from application where id=?");
      $get->bindParam(1,$id);
      $get->execute();
      $inf=$get->fetchALL();
      $values['fio']=$inf[0]['name'];
      $values['mail']=$inf[0]['mail'];
      $values['year']=$inf[0]['date'];
      $values['sex']=$inf[0]['sex'];
      $values['limb']=$inf[0]['limb'];
      $values['bio']=$inf[0]['bio'];
      $get2=$db->prepare("select power from powers where id=?");
      $get2->bindParam(1,$id);
      $get2->execute();
      $inf2=$get2->fetchALL();
      for($i=0;$i<count($inf2);$i++){
        if($inf2[$i]['power']=='бессмертие'){
          $values['immortal']=1;
        }
        if($inf2[$i]['power']=='прохождение сквозь стены'){
          $values['ghost']=1;
        }
        if($inf2[$i]['power']=='левитация'){
          $values['levitation']=1;
        }
      }
  }
  catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
  }
  include('form.php');
}
else {
  if(!empty($_POST['edit'])){
    $id=$_POST['dd'];
    $fio=$_POST['fio'];
    $mail=$_POST['mail'];
    $year=$_POST['year'];
    $sex=$_POST['sex'];
    $limb=$_POST['limb'];
    $pwrs=$_POST['power'];
    $bio=$_POST['bio'];
    $errors = FALSE;
    if (empty($fio)) {
        setcookie('fio_error', '1', time() + 24*60 * 60);
        setcookie('fio_value', '', 100000);
        $errors = TRUE;
    }
    //проверка почты
    if (empty($mail) or !filter_var($mail,FILTER_VALIDATE_EMAIL)) {
        setcookie('mail_error', '1', time() + 24*60 * 60);
        setcookie('mail_value', '', 100000);
        $errors = TRUE;
    }
    //проверка года
    if ($year=='Выбрать') {
        setcookie('year_error', '1', time() + 24 * 60 * 60);
        setcookie('year_value', '', 100000);
        $errors = TRUE;
    }
    //проверка пола
    if (!isset($sex)) {
        setcookie('sex_error', '1', time() + 24 * 60 * 60);
        setcookie('sex_value', '', 100000);
        $errors = TRUE;
    }
    //проверка конечностей
    if (!isset($limb)) {
        setcookie('limb_error', '1', time() + 24 * 60 * 60);
        setcookie('limb_value', '', 100000);
        $errors = TRUE;
    }
    //проверка суперспособностей
    if (!isset($pwrs)) {
        setcookie('powers_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    if ($errors) {
        setcookie('save','',100000);
        header('Location: edit.php?edit_id='.$id);
    }
    else {
        setcookie('fio_error', '', 100000);
        setcookie('mail_error', '', 100000);
        setcookie('year_error', '', 100000);
        setcookie('sex_error', '', 100000);
        setcookie('limb_error', '', 100000);
        setcookie('powers_error', '', 100000);
        setcookie('bio_error', '', 100000);
        setcookie('privacy_error', '', 100000);
    }
    include('connect.php');
    if(!$errors){
        $upd=$db->prepare("update application set name=:name,mail=:mail,date=:date,sex=:sex,limb=:limb,bio=:bio where id=:id");
        $cols=array(
        ':name'=>$fio,
        ':mail'=>$mail,
        ':date'=>$year,
        ':sex'=>$sex,
        ':limb'=>$limb,
        ':bio'=>$bio
        );
        foreach($cols as $k=>&$v){
        $upd->bindParam($k,$v);
        }
        $upd->bindParam(':id',$id);
        $upd->execute();
        $del=$db->prepare("delete from powers where id=?");
        $del->execute(array($id));
        $upd1=$db->prepare("insert into powers set power=:power,id=:id");
        $upd1->bindParam(':id',$id);
        foreach($pwrs as $pwr){
        $upd1->bindParam(':power',$pwr);
        $upd1->execute();
        }
    }
    
    if(!$errors){
      setcookie('save', '1');
    }
    header('Location: edit.php?edit_id='.$id);
  }
  else {
    $id=$_POST['dd'];
    include('connect.php');
    try {
      $del=$db->prepare("delete from powers where id=?");
      $del->execute(array($id));
      $stmt = $db->prepare("delete from application where id=?");
      $stmt -> execute(array($id));
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
    exit();
    }
    setcookie('del','1');
    setcookie('del_user',$id);
    header('Location: admin.php');
  }

}
