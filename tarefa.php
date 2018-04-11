<?php
    class tarefa {
        private $link;
        function __construct() {
            include("bancodados.php");
            $this->link = mysqli_connect($ip, $usuario, $senha, $banco);
            @$action = $_GET['action'];
            switch($action) {
                case 'select': $this->select(); break;
                case 'insert': $this->insert(); break;
                case 'update': $this->update(); break;
                case 'updatestatus': $this->updatestatus(); break;
                case 'delete': $this->delete(); break;
            }
        }
        function __destruct() {
            mysqli_close($this->link);
        }
        private function select() {
            @$username    = mysqli_real_escape_string($this->link, $_GET['username']);
            @$idCategoria = mysqli_real_escape_string($this->link, $_GET['idcategoria']);
            @$id          = mysqli_real_escape_string($this->link, $_GET['id']);

            $sql = "SELECT t.id, t.descricao, t.dataLimite, t.dataLembrete, t.username, t.idCategoria, c.nome nomeCategoria FROM tb_tarefa t INNER JOIN tb_categoria c ON t.idCategoria = c.id WHERE username = '$username' AND status = 'P'";
            if(!empty($idCategoria)){
               $sql = $sql . " AND t.idcategoria = $idCategoria";
           } else if(!empty($id)) {
               $sql = $sql . " AND t.id = $id";
           }
           $sql = $sql . " ORDER BY t.id";

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
            @$descricao = mysqli_real_escape_string($this->link, $_POST['descricao']);
            @$datalimite = mysqli_real_escape_string($this->link, $_POST['datalimite']);
            @$datalembrete = mysqli_real_escape_string($this->link, $_POST['datalembrete']);
            @$username = mysqli_real_escape_string($this->link, $_POST['username']);
            @$idcategoria = mysqli_real_escape_string($this->link, $_POST['idcategoria']);
            if(!empty($idcategoria)) {
                $sql = "INSERT INTO tb_tarefa(descricao, dataLimite, dataLembrete, status, username, idCategoria) VALUES('$descricao', '$datalimite', '$datalembrete', 'P', '$username', $idcategoria)";
                $r = mysqli_query($this->link, $sql);
                if ($r)
                    echo "success";
            }
        }
        private function update() {
            @$id = mysqli_real_escape_string($this->link, $_POST['id']);
            @$idcategoria = mysqli_real_escape_string($this->link, $_POST['idcategoria']);
            @$descricao = mysqli_real_escape_string($this->link, $_POST['descricao']);
            @$datalimite = mysqli_real_escape_string($this->link, $_POST['datalimite']);
            @$datalembrete = mysqli_real_escape_string($this->link, $_POST['datalembrete']);

            if(!empty($id)) {
                if(!empty($idcategoria)) {
                    $sql = "UPDATE tb_tarefa SET descricao = '$descricao', dataLimite = '$datalimite', dataLembrete = '$datalembrete', idCategoria = $idcategoria WHERE id = $id";
                    $r = mysqli_query($this->link, $sql);
                    if($r)
                        echo "success";
                }
            }
        }
        private function updatestatus() {
            @$id = mysqli_real_escape_string($this->link, $_POST['id']);
            if(!empty($id)) {
                $sql = "UPDATE tb_tarefa SET status = 'R' WHERE id = $id";
                $r = mysqli_query($this->link, $sql);
                if($r)
                    echo "success";
            }
        }
        private function delete() {
            @$id = mysqli_real_escape_string($this->link, $_POST['id']);
            if(!empty($id)) {
                $sql = "DELETE * FROM tb_tarefa WHERE id = $id";
                $r = mysqli_query($this->link, $sql);
                if($r)
                    echo "success";
            }
        }
    }
    new tarefa();
?>
