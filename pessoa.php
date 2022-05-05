<?php 

    class Pessoa{
            //criar um variável para para instanciar o PDO
        private $pdo;
            //contrutor, ponto de partida do código, primeiro código será executado (conexção com bd)
        public function __construct($dbname, $host, $user, $senha){
            try {
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
            } 
            catch (PDOException $e) {
                echo "Erro com banco de dados: ".$e->getMessage();
                exit();
            }
            catch (Exception $e){
                echo "Erro genérico: ".$e->getMessage();
                exit();
            }
        }
            //metodo para buscar no banco de dado e colocar na tela
        public function buscarDados(){
            $array = array();
                //$receber vai receber s informações do $pdo, que está a construção do código
            $receber = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
            $array = $receber->fetchAll(PDO::FETCH_ASSOC);
            return $array;
        }

            //funão de cadastrar pessoas no banco de dados
        public function cadastrar($nome, $telefone, $email){
            //antes de cadastrar verificar se ja tem um cadastro
            $receber = $this->pdo->prepare("SELECT id from pessoa WHERE email = :e");
            $receber->bindValue(":e",$email);
            $receber->execute();
                //verificar se veio o id
            if($receber->rowCount() > 0)//o email ja existe
            {
                return false;
            }
            else{ // não for encontrado 
                $receber = $this->pdo->prepare("INSERT INTO pessoa (nome, telefone, email) VALUES (:n, :t, :e)");
                $receber->bindValue(":n", $nome);
                $receber->bindValue(":t", $telefone);
                $receber->bindValue(":e", $email);
                $receber->execute();
                return true;
            }
        }

            //metodo para clicar no botão e excluir
        public function excluir($id){
            $receber = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
            $receber->bindValue(":id",$id);
            $receber->execute();
        }

            //buscar dados de uma pessoa específica
        public function buscar($id){
            $array = array();
            $receber = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
            $receber->bindValue(":id",$id);
            $receber->execute();
            $array = $receber->fetch(PDO::FETCH_ASSOC);
            return $array;
        }

            //atualizar os dados da pessoa específica
        public function atualizar( $id, $nome, $telefone, $email){
            $receber = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :i");
            $receber->bindValue(":n",$nome);
            $receber->bindValue(":t",$telefone);
            $receber->bindValue(":e",$email);
            $receber->bindValue(":i",$id);
            $receber->execute();
        }
    }

?>
