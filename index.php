<?php 
        //buscar arquivo
    require_once 'pessoa.php';
        //instanciar a classe pessoa (arquivo pessoa) e colocar os parâmetros do banco de dados
    $p = new Pessoa("agenda","localhost","root","");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <title>Cadastro Pessoas</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>

        <?php
                //verificar se a pessoa clicou em cadastrar ou atualizar
            if(isset($_POST['nome'])){
            //--------------------EDITAR-----------------------
                if (isset($_GET['up']) && !empty($_GET['up'])){
                        //não pegar as informações e guardar diretamente, falta de segurança (usar addslashes - faz proteção de código malicioso)
                    $up = addslashes($_GET['up']);
                    $nome = addslashes($_POST['nome']);
                    $telefone = addslashes($_POST['telefone']);
                    $email = addslashes($_POST['email']);
    
                    if(!empty($nome) && !empty($telefone) && !empty($email)){
                            //atualizar
                        $p->atualizar($up, $nome, $telefone, $email);
                        header("location: index.php");
    
                    }
    
                    else {
                        ?>
                        <div class="aviso">
                            <h4>Por favor, preenchaa todos os dados!</h4>
                        </div>
                        <?php
                    } 
                }
            //-------------------CADASTRAR---------------------
                else {
                    
                        //não pegar as informações e guardar diretamente, falta de segurança (usar addslashes - faz proteção de código malicioso)
                    $nome = addslashes($_POST['nome']);
                    $telefone = addslashes($_POST['telefone']);
                    $email = addslashes($_POST['email']);

                    if(!empty($nome) && !empty($telefone) && !empty($email)){
                            //cadastrar
                        if($p->cadastrar($nome, $telefone, $email)){

                        }
                        else {
                            ?>
                            <div class="aviso">
                                <h4>Atenção, email ja está cadastrado!</h4>
                            </div>
                            <?php
                        }

                    }

                    else {
                        ?>
                        <div class="aviso">
                            <h4>Por favor, preenchaa todos os dados!</h4>
                        </div>
                        <?php
                    }
                }
            }
        ?>

        <?php
                //verifica se a pessoa clicou em editar
            if (isset($_GET['up'])) {
                $update = addslashes($_GET['up']);
                $res = $p->buscar($update);
            }
        
        ?>

    <section id="esquerda">
        <form method="POST">
            <h2>Cadastro</h2>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?php if(isset($res)){echo $res['nome'];} ?>">
        
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php if(isset($res)){echo $res['telefone'];} ?>">
        
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php if(isset($res)){echo $res['email'];} ?>">
        
            <input type="submit" value="<?php if(isset($res)){echo "Atualizar";}
            else {echo "Cadastrar";} ?>">
        </form>
    </section>
    <section id="direita">
    <table>
            <tr id="titulo">
                <td>Nome</td>
                <td>Telefone</td>
                <td>Email</td>
            </tr>
        <?php
                //criar uma variável para receber as informações neste arquivo($dados)
                //chamar o metodo para mostrar os dados
            $dados = $p->buscarDados();
                //para verificar se está vazio
            if(count($dados) > 0){
                    //contar os array começando do 0 até a ultima
                    //copiar o count para finalizar e saber quantos array tem
                for ($i=0; $i < count($dados); $i++) {
                    echo "<tr>"; 
                        //$k- coluna $v dados
                        //mostrar as informações do bd
                    foreach ($dados[$i] as $k => $v) {
                        if($k != "id"){
                            echo "<td>".$v."</td>";
                        }
                    }
            ?>
                    <td>
                        <a href="index.php?up=<?php echo $dados[$i]['id']; ?>">Editar</a>
                        <a href="index.php?id=<?php echo $dados[$i]['id']; ?>">Excluir</a>
                    </td>
            <?php
                    echo "</tr>";
                }
            }

            else {//banco de dados vazio
             ?>  
        </table>
                <div id="cadastro">
                    <h4>Ainda não há pessoas cadastradas!</h4>
                </div>
                <?php
            }
?>
    </sectio>

</body>
</html>
<?php

    if(isset($_GET['id'])){
        $id_p = addslashes($_GET['id']);
        $p->excluir($id_p);
        header("location: index.php");
    }

?>
