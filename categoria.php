<?php
    class categoria {
        private $link;
        function __construct() {
            include("bancodados.php");
            $this->link = mysqli_connect($ip, $usuario, $senha, $banco);
            @$action = $_GET['action'];
            switch($action) {
                case 'select': $this->select(); break;
                case 'insert': $this->insert(); break;
                case 'update': $this->update(); break;
                case 'delete': $this->delete(); break;
            }
        }
        function __destruct() {
            mysqli_close($this->link);
        }
        private function select() {
            @$id = mysqli_real_escape_string($this->link, $_GET['id']);

            $sql = "SELECT id, nome FROM tb_categoria";
            if(!empty($id)) {
                $sql = $sql . " WHERE id = $id";
            }
            $sql = $sql . " ORDER BY id";

            $rs = mysqli_query($this->link, $sql);
            $dados = array();
            if(!empty($rs) AND mysqli_num_rows($rs) > 0) {
                while($r = mysqli_fetch_object($rs)){
                    $dados[] = $r;
                }
                mysqli_free_result($rs);
            }
            echo json_encode($dados);
        }
        private function insert() {
            @$nome = mysqli_real_escape_string($this->link, $_POST['nome']);
            $sql = "INSERT INTO tb_categoria(nome) VALUES ('$nome')";
            $r = mysqli_query($this->link, $sql);
            if ($r)
                echo "success";
        }
        private function update() {
            @$id = mysqli_real_escape_string($this->link, $_POST['id']);
            @$nome = mysqli_real_escape_string($this->link, $_POST['nome']);
            @$username = mysqli_real_escape_string($this->link, $_POST['username']);

            if(!empty($id)) {
                $sqltotal = "SELECT * FROM tb_tarefa WHERE idCategoria = $id";
                $rstotal = mysqli_query($this->link, $sqltotal);

                $sqltotaluser = "SELECT * FROM tb_tarefa
                                 WHERE idCategoria = $id AND username = '$username'";
                $rstotaluser = mysqli_query($this->link, $sqltotaluser);

                if(mysqli_num_rows($rstotal) == mysqli_num_rows($rstotaluser)) {
                    $sql = "UPDATE tb_categoria SET nome = '$nome' WHERE id = $id";
                    $r = mysqli_query($this->link, $sql);
                    if($r)
                        echo "success";
                }
            }
        }
        private function delete() {
            @$id = mysqli_real_escape_string($this->link, $_POST['id']);
            if(!empty($id)) {
                $sqltotal = "SELECT * FROM tb_tarefa WHERE idCategoria = $id";
                $rs = mysqli_query($this->link, $sqltotal);
                if(empty($rs) OR mysqli_num_rows($rs) == 0) {
                    $sql = "DELETE FROM tb_categoria WHERE id = $id";
                    $r = mysqli_query($this->link, $sql);
                    if($r)
                        echo "success";
                }
                mysqli_free_result($rs);
            }
        }
    }
    new categoria();
?>
