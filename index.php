<?php

// Attributs (Private = peut être accessibles uniquement dans la class) 
//           (Public = peut être accessibles depuis la classe ou depuis l'extérieur de celle-ci)

class User 
{
    private $_id;
    public  $_login;
    public  $_email;
    public  $_firstname;
    public  $_lastname;

    public $_conn;

    function __construct()
    {
        $bdd = mysqli_connect('localhost:8889', 'root', 'root', 'classes');
        $this->_conn = $bdd;

        if (!$bdd) {
            echo 'Erreur de connexion (' . mysqli_connect_errno() . ') ' . mysqli_connect_error();
        }
    }

    public function register($login, $password, $email, $firstname, $lastname)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // Create connection
        $bdd = mysqli_connect('localhost:8889', 'root', 'root', 'classes'); 
               // Check connection
        if (!$bdd) 
        {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES ('".$login."','". $hash."','". $email."','". $firstname."','".$lastname."')";

        if (mysqli_query($bdd, $sql))
        {
            echo "Nouveau Compte Crée";
        } else 
        {
            echo "Error: " . $sql . "<br>" . mysqli_error($bdd);
        }
        
        mysqli_close($bdd);
    }

    public function connect($login, $password)
    {
        $sql = mysqli_query($this->_conn, "SELECT * FROM utilisateurs WHERE login = '$login'");
        $erreur = false;

        if ($sql != false) 
        {
            $res = mysqli_fetch_array($sql);

            if (password_verify($password, $res['password'])) 
            {
                $this->_id = $res['id'];
                $this->_login = $res['login'];
                $this->_email = $res['email'];
                $this->_firstname = $res['firstname'];
                $this->_lastname = $res['lastname'];

                $_SESSION['id'] = $res['id'];
                $_SESSION['login'] = $res['login'];
                $_SESSION['email'] = $res['email'];
                $_SESSION['firstname'] = $res['firstname'];
                $_SESSION['lastname'] = $res['lastname'];
            } 
            else 
            {
                $erreur = true;   
            }
        }
        else 
        {
            $erreur = true;
        }
        if ($erreur)
        {
            Echo "Votre Login/Mot de Passe est invalide";
        }
    }

    public function disconnect()
    {
        session_unset($_SESSION);
        session_destroy();
    }

    public function delete()
    {

        $id = $_SESSION['id'];
        $login = $_SESSION['login'];
        $delete = mysqli_query($this->_conn, "DELETE * FROM utilisateurs WHERE login = '$login' && id = '$id'");
        
    }

    public function update($login, $password, $email, $firstname, $lastname)
    {
        $id = $_SESSION['id'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $login1 = $_POST['login'];
        $hash1 = $_POST['hash'];
        $email1 = $_POST['email'];
        $firstname1 = $_POST['firstname'];
        $lastname1 = $_POST['lastname'];


        if (mysqli_query($this->_conn, "UPDATE `utilisateurs` SET `login`=$login1, `password`=$hash1, `email`=$email1, `firstname`=$firstname1 WHERE  `id`=$id") === true) {
            printf("Utilisateur mis à jour !");
        }
    }

    public function isConnected()
    {
        if (isset($_SESSION['id']) === true) {
            printf("Vous êtes connectés");
        }
    }

    public function getAllInfos()
    {
        $id_session = $_SESSION['id'];
        $sql = mysqli_query($this->_conn, "SELECT * FROM `utilisateurs` WHERE id =='$id_session'");

        if ($sql != false) {
            return array($sql);
        }
    }
    
    public function getLogin ()
    {
        $id_session = $_SESSION['id'];
        $sql = mysqli_query($this->_conn, "SELECT `login` FROM `utilisateurs` WHERE id =='$id_session'");

        if (isset($_SESSION['id']) != false) 
        {
            return array($sql);
        }
    }
    public function getEmail ()
    {
        $id_session = $_SESSION['id'];
        $sql = mysqli_query($this->_conn, "SELECT `email` FROM `utilisateurs` WHERE id =='$id_session'");

        if (isset($_SESSION['id']) != false) 
        {
            return array($sql);
        }
    }
    public function getFirstname ()
    {
        $id_session = $_SESSION['id'];
        $sql = mysqli_query($this->_conn, "SELECT `firstname` FROM `utilisateurs` WHERE id =='$id_session'");

        if (isset($_SESSION['id']) != false) 
        {
            return array($sql);
        }
    }
    public function getLastname ()
    {
        $id_session = $_SESSION['id'];
        $sql = mysqli_query($this->_conn, "SELECT * FROM `lastname` WHERE id =='$id_session'");

        if (isset($_SESSION['id']) != false) 
        {
            return array($sql);
        }
    }
}
$this->_conn = $bdd;
var_dump($bdd);