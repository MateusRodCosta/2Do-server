<?php
    class usuario {
        private $link;
        function __construct() {
            include("bancodados.php");
            $this->link = mysqli_connect($ip, $usuario, $senha, $banco);
            @$action = $_GET['action'];
            switch($action) {
                case 'validate': $this->validate(); break;
                case 'select': $this->select(); break;
                case 'insert': $this->insert(); break;
                case 'update': $this->update(); break;
                case 'delete': $this->delete(); break;
            }
        }
        function __destruct() {
            mysqli_close($this->link);
        }
        private function validate() {
            @$username = mysqli_real_escape_string($this->link, $_POST['username']);
            @$password = mysqli_real_escape_string($this->link, $_POST['password']);

            $sql = "SELECT username, password FROM tb_usuario
                    WHERE username = '$username' AND password = '$password'";
            $rs = mysqli_query($this->link, $sql);
            if(mysqli_num_rows($rs) == 1)
                echo "success";
        }
        private function select() {
            @$username = mysqli_real_escape_string($this->link, $_GET['username']);

            $sql = "SELECT username, nome, sexo FROM tb_usuario WHERE username = '$username'";
            $rs = mysqli_query($this->link, $sql);
            if(mysqli_num_rows($rs) == 1) {
                $r = mysqli_fetch_object($rs);
                mysqli_free_result($rs);
                echo json_encode($r);
            }
        }
        private function insert() {
            @$username = mysqli_real_escape_string($this->link, $_POST['username']);
            @$password = mysqli_real_escape_string($this->link, $_POST['password']);
            @$nome = mysqli_real_escape_string($this->link, $_POST['nome']);
            @$sexo = mysqli_real_escape_string($this->link, $_POST['sexo']);

            $sql = "INSERT INTO tb_usuario(username, password, nome, sexo) VALUES('$username', '$password', '$nome', '$sexo')";
            $r = mysqli_query($this->link, $sql);
            if ($r)
                echo "success";
        }
        private function update() {
            @$username = mysqli_real_escape_string($this->link, $_POST['username']);
            @$password = mysqli_real_escape_string($this->link, $_POST['password']);
            @$nome = mysqli_real_escape_string($this->link, $_POST['nome']);
            @$sexo = mysqli_real_escape_string($this->link, $_POST['sexo']);
            @$newpassword = mysqli_real_escape_string($this->link, $_POST['newpassword']);

            $sql = "SELECT username, password FROM tb_usuario WHERE username = '$username' AND password = '$password'";
            $rs = mysqli_query($this->link, $sql);

            if(mysqli_num_rows($rs) == 1) {
                if(empty($newpassword))
                    $newpassword = $password;
                $sqlupdate = "UPDATE tb_usuario SET nome = '$nome', sexo = '$sexo', password = '$newpassword' WHERE username = '$username'";
                $r = mysqli_query($this->link, $sqlupdate);
                if($r)
                    echo "success";
            }
        }
        private function delete() {
            @$username = mysqli_real_escape_string($this->link, $_POST['username']);
            @$password = mysqli_real_escape_string($this->link, $_POST['password']);

            $sqlvalidar = "SELECT username, password FROM tb_usuario
                           WHERE username = '$username' AND password = '$password'";
            $rs = mysqli_query($this->link, $sqlvalidar);

            if(mysqli_num_rows($rs) == 1) {
                $tarefassql = "DELETE FROM tb_tarefa WHERE username = '$username'";
                $rtarefa = mysqli_query($this->link, $tarefassql);
                if($rtarefa) {
                    $sql = "DELETE FROM tb_usuario WHERE username = '$username'";
                    $r = mysqli_query($this->link, $sql);
                    if($r)
                        echo "success";
                }
            }
        }
    }
    new usuario();
?>
