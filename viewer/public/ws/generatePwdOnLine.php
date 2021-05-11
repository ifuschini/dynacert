<form>
Password: 
<input type="text" name="password">
<input type="submit">
</form>

<?php
$sendTo = array(
        'u6' => 'leo.iatta98@gmail.com,elfunari@hotmail.it',
        'u8' => 'g.ocello@virgilio.it,simonebucchia@hotmail.it',
        'u10' => 'adrianopst@gmail.com,manuelbigi007@googlemail.com',
        'u12' => 'kub@hotmail.it,pergoloni@dp7.it',
        'u14' => 'gianluca.vella@yahoo.it',
        'u16' => 'rickcioni@gmail.com,alessandro.natoli1386@gmail.com',
        'u18' => 's.dannunzio10@gmail.com,upafamasino@hotmail.it',
        'seniores' => 'silvio.tarroni@rugbyroma.it,2montelladanoele@gmail.com',
    );
    function cryptUser($user)
    {
        $hash = $user;
        for ($i = 0; $i < 100000; $i++) {
            $hash = hash('sha256', $hash);
        }
        return $hash;
    }
  
    function createPassword($password) {
            $hash=cryptUser($password);
            for ( $i = 0; $i < 100000; $i ++ ) {
                    $hash = hash('sha256', $hash);
            }
            return $hash;

    }
    if ($_GET['password']) {

    $userToSet['admin']=array(
                "password" => createPassword($_GET['password']),
                "category" => 'all',
    );
    print "<table border=1>";
    print "<tr><td>Utente</td><td>Password</td><td>Categorie</td></tr>";
    print '<tr><td>admin'.'</td><td>'. $_GET['password']."</td><td>all</td></tr>";
    print "</table>";
    print "<br/><br/><br/><br/><br/><br/><br/><br/>";

    print json_encode($userToSet);
}
?>