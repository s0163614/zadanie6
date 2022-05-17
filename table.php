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
</style>
<body>
  <div class="table1">
    <table border="1">
      <tr>
        <th>Name</th>
        <th>Mail</th>
        <th>Year</th>
        <th>Sex</th>
        <th>Limb</th>
        <th>Superpowers</th>
        <th>Bio</th>
      </tr>
      <?php
      foreach($users as $user){
          echo '
            <tr>
              <td>'.$user['name'].'</td>
              <td>'.$user['mail'].'</td>
              <td>'.$user['date'].'</td>
              <td>'.$user['sex'].'</td>
              <td>'.$user['limb'].'</td>
              <td>';
                $user_pwrs=array(
                    "immortal"=>FALSE,
                    "ghost"=>FALSE,
                    "levitation"=>FALSE
                );
                foreach($pwrs as $pwr){
                    if($pwr['id']==$user['id']){
                        if($pwr['power']=='бессмертие'){
                            $user_pwrs['immortal']=TRUE;
                        }
                        if($pwr['power']=='прохождение сквозь стены'){
                            $user_pwrs['ghost']=TRUE;
                        }
                        if($pwr['power']=='левитация'){
                            $user_pwrs['levitation']=TRUE;
                        }
                    }
                }
                if($user_pwrs['immortal']){echo 'Бессмертие<br>';}
                if($user_pwrs['ghost']){echo 'Прохождение сквозь стены<br>';}
                if($user_pwrs['levitation']){echo 'Левитация<br>';}
              echo '</td>
              <td>'.$user['bio'].'</td>
              <td>
                <form method="get" action="edit.php">
                  <input name=edit_id value='.$user['id'].' hidden>
                  <input type="submit" value=Edit>
                </form>
              </td>
            </tr>';
       }
      ?>
    </table>
    <?php
    printf('Кол-во пользователей с сверхспособностью "Бессмертие": %d <br>',$pwrs_count[0]);
    printf('Кол-во пользователей с сверхспособностью "Прохождение сквозь стены": %d <br>',$pwrs_count[1]);
    printf('Кол-во пользователей с сверхспособностью "Левитация": %d <br>',$pwrs_count[2]);
    ?>
  </div>
</body>
