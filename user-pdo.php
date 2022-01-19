<?php
require 'bdd.classes.php';

class User 
{
  private   $id;
  public    $login;
  public    $email;
  public    $firstname;
  public    $lastname;
  public    $db;

  public function __construct($id, $login, $email, $firstname, $lastname, $db)
  {
    $this->id=$id;
    $this->login=$login;
    $this->email=$email;
    $this->firstname=$firstname;
    $this->lastname=$lastname;
    $this->db=$db;
  }
  public function Register($login,$password,$email,$firstname,$lastname)
  {
    $login=htmlentities($_POST['login']);
    $password=htmlentities($_POST['password']); 
    $email=htmlentities($_POST['email']);
    $firstname=htmlentities($_POST['firstname']);
    $lastname=htmlentities($_POST['lastname']);
    
    $register=$this->db->prepare("SELECT login FROM utilisateurs WHERE login = :login ");
    $register->bindValue(':login',$login);
    $register->execute();

    $userExists = $register->rowcount();
    $connexionfetch=$register->fetchAll(PDO::FETCH_ASSOC);
  }
  public function connect($login,$password){

    if(isset($_POST['sign_in'])){
      $login = htmlentities($_POST['login'], ENT_QUOTES, "ISO-8859-1"); 
      $password = htmlentities(password_hash($_POST['password'],PASSWORD_DEFAULT));
      $connexion = $this->db->prepare("SELECT * FROM utilisateurs WHERE login = :login ");
      $connexion->execute(array(':login' => $login));
      $userExists = $connexion->rowcount();
      $cofetch = $connexion->fetch(PDO::FETCH_ASSOC);
    
      if(password_verify($_POST['password'],$cofetch['password'])) {
        if($userExists==1 ) {
        $_SESSION['user'] = $cofetch;
        header("Location: profil.php");
        }   
      }
      else{
          $message='Votre Login/Mot de Passe est incorrecte';  
      }
    }      
  }

  public function disconnect(){
    session_destroy();
    session_unset($_SESSION['user']);
  }

  public function delete($login){
    $connexion=$this->db->prepare("DELETE * FROM `utilisateurs` WHERE `login`= :login ");
    $connexion->bindValue(':login',$login);
    $connexion->execute();
  }

  public function updapte($login,$password,$email,$firstname,$lastname){

    $connexion=$this->db->prepare("UPDATE `utilisateurs` SET `login`=:login1, `password`=:password ,`email`=:email,`firstname`=:firstname,`lastname`=:lastname  WHERE `login`= :login");
    $connexion->bindValue(':login',$oldlogin ,PDO::PARAM_STR);
    $connexion->bindValue(':login1',$login ,PDO::PARAM_STR);
    $connexion->bindValue(':login',$password ,PDO::PARAM_STR);
    $connexion->bindValue(':login1',$email,PDO::PARAM_STR);
    $connexion->bindValue(':login',$firstname ,PDO::PARAM_STR);
    $connexion->bindValue(':login1',$lastname ,PDO::PARAM_STR);
    $connexion->execute();

    $connexion=$this->db->prepare("SELECT * FROM `utilisateurs` WHERE `login`= :login ");
    $connexion->bindValue(':login',$login);
    $connexion->execute();
    $connexionfetch2=$connexion->fetchall(PDO::FETCH_ASSOC);

    $_SESSION['user']['login']=$login;

    if(strlen($_POST['password1'])>=6){
      if($password1==$password2){
        $password1=password_hash($password1,PASSWORD_DEFAULT);
        $sqlinsert="INSERT INTO utilisateurs(password) VALUES(:password)";
        $connexioninsert=$this->db->prepare($sqlinsert);
        $connexioninsert->execute(array(':password'=>$password1));
      }
    }
  }
    
  public function isConnected() {
    if($_SESSION['user']['login']!=NULL && $_SESSION['user']['password']!=NULL){
        $_SESSION==TRUE;
    }
  }

  public function getAllinfos(){
    $getinfos=$this->db->prepare("SELECT * FROM utilisateurs WHERE login = :login ");
    $getinfos->bindValue(':login',$login);
    $getinfos->execute();
  }
    
  public function getLogin(){
    $getinfos=$this->db->prepare("SELECT login FROM utilisateurs WHERE login = :login ");
    $getinfos->bindValue(':login',$login);
    $getinfos->execute();
  }

  public function getEmail(){
    $getinfos=$this->db->prepare("SELECT email FROM utilisateurs WHERE login = :login ");
    $getinfos->bindValue(':login',$login);
    $getinfos->execute();
  }

  public function getFirstname(){
    $getinfos=$this->db->prepare("SELECT firstname FROM utilisateurs WHERE login = :login ");
    $getinfos->bindValue(':login',$login);
    $getinfos->execute();
  }

  public function getLastname(){
    $getinfos=$this->db->prepare("SELECT lastname FROM utilisateurs WHERE login = :login ");
    $getinfos->bindValue(':login',$login);
    $getinfos->execute();
  }

}