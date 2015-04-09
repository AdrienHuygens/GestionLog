<!DOCTYPE html>
<!--
Copyright 2015 Version 1.0.0
Pour le Pass, projet gestion de log.
@author Huygens Adrien
contact adrien.huygens@gmail.com
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $server = "192.168.100.34";

        $port = "389";

        $racine = "";

        // $rootdn = "cn=ldap_admin, o=commentcamarche, c=fr";

        $rootpw = "secret";
        $cpt = 0;

        echo "Connexion...<br>";

        $ds = ldap_connect($server);
        var_dump($ds . "<br>");
        if ($ds == 1) {
            $cpt = 3;
            echo "Connexion...<br>";
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
            // on s'authentifie en tant que super-utilisateur, ici, ldap_admin
            $r = ldap_bind($ds, 'uid=syslog,ou=Applications,dc=admin,dc=pass,dc=be', 'syslog');
            $r = ldap_bind($ds, 'uid=lmuller,ou=users,dc=admin,dc=pass,dc=be', 'abcde');
            echo"<br>";
            echo $r;
            /*$dn = "dc=admin,dc=pass,dc=be";
            $filtre = "uid=Ddevleeschauwer";
            $sr = ldap_search($ds, $dn, $filtre);
            echo "Le résultat de la recherche est $sr";

            echo "Le nombre d'entrées retournées est " . ldap_count_entries($ds, $sr) . ".<p>";
            $info = ldap_get_entries($ds, $sr);
            echo "Données pour " . $info["count"] . " entrées:<p>";
           
            
            for ($i = 0; $i < $info["count"]; $i++) {
                echo "dn est : " . $info[$i]["dn"] . "<br>";
                echo "premiere entree cn : " . $info[$i]["cn"][0] . "<br>";
                echo "premier email : " . $info[$i]["mail"][0] . "<p>";
            }
            
            
            $recherchegroupe = ldap_search($ds, $dn, "objectclass=group");
            $resultatgroupe = ldap_get_entries($ds,$recherchegroupe);

            echo 'nombre de groupes dans l\'AD :' . $resultatgroupe['count'] . '<br />';
            // Ici les opérations à effectuer*/
            echo "Déconnexion...<br>";

            ldap_close($ds);
        } else {

            echo "Impossible de se connecter au serveur LDAP";
            $cpt ++;
        }
        ?>
    </body>
</html>
