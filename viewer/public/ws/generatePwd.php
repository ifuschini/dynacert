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
  
    function createPassowrd($password) {
            $hash=cryptUser($password);
            for ( $i = 0; $i < 100000; $i ++ ) {
                    $hash = hash('sha256', $hash);
            }
            return $hash;

    }
    $passwordBase= "Rugbyroma.";
    $passwordAdmin="NuovaRugbyRoma_2020";
    $category=array();
    $userToSet=array();
    foreach ($sendTo as $key=>$send) {
        $sendPeople=explode(",",$send);
        foreach ($sendPeople as $to) {
            //print $to."<br/>";
            $category[$to]=$category[$to].$key.",";
        }
    }

    $userToSet['admin']=array(
                "password" => createPassowrd($passwordAdmin),
                "category" => 'all',
    );
    print "<table border=1>";
    print "<tr><td>Utente</td><td>Password</td><td>Categorie</td></tr>";
    print '<tr><td>admin'.'</td><td>'.$passwordAdmin."</td><td>all</td></tr>";
    foreach ($sendTo as $key=>$send) {
        $sendPeople=explode(",",$send);
        foreach ($sendPeople as $to) {
            $password=createPassowrd($passwordBase.$key);
            $cat= substr($category[$to],0,strlen($category[$to]) -1);
            print '<tr><td>'.$to.'</td><td>'.$passwordBase.$key."</td><td>".$cat."</td></tr>";
            $userToSet[$to]=array(
                "password" => $password,
                "category" => $cat,
            );
        }
    }
    print "</table>";
    print "<br/><br/><br/><br/><br/><br/><br/><br/>";

    print json_encode($userToSet);
?>