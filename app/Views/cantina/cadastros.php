<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/alunos.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Cadastros</title>
</head>
<body>
    <div class="acess">
        <a href="<?php echo site_url('/', METHOD); ?>"><img class="chevron-left" src="/img/chevron-left.svg" alt=""></a>
        <center>Acesso aos cadastros</center>
    </div>
    <div id="back">
        <dialog id="bloquearDialog" class="MiniDialog">
            <header>
                <p>Confirmação</p>
            </header>
            <article>   
                <p>Você tem certeza se quer bloquear este usuário?</p>    
            </article>
            <footer>
                <button autofocus onclick="this.closest('dialog').close('cancel');document.querySelector('#back').style.display = 'none';">Cancelar</button>
                <button onclick="Bloquear();">Bloquear</button>
            </footer>
        </dialog>
    </div>
    <div class="info" id="info">
        
    </div>
    <div class="acess-1">
        <table>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Matrícula</th>
                <th>CPF</th>
                <th>Bolsista</th>
                <th></th>
            </tr>
            <?php foreach($usuarios as $usuario) :  ?>
                <tr id="row-<?= $usuario['id']; ?>">
                    <td><?php echo $usuario['Nome']; ?></td>
                    <td><?php echo $usuario['Email']; ?></td>
                    <td><?php echo $usuario['Matricula']; ?></td>
                    <td><?php echo $usuario['CPF']; ?></td>
                    <td>
                        <?php if($usuario['Tem_auxilio'] == 1) : ?>
                            <?php echo '100%'; ?>
                        <?php else: ?>
                            <?php echo 'Não'; ?>
                        <?php endif ?>
                    </td>
                    <td id="functions-<?= $usuario['id']; ?>">
                    <?php if($usuario['Bloqueado'] == 0) : ?>
                        <a class="button" id="block-button" onclick="MostrarBloquear(<?= $usuario['id']; ?>);">Bloquear</a>
                    <?php else : ?>
                        <a class="button" id="block-button" onclick="Desbloquear(<?= $usuario['id']; ?>);">Desbloquear</a>
                    <?php endif ?>    
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
    <script>
        const Back = document.querySelector('#back');
        const BloquearDialog = document.querySelector('#bloquearDialog');
        var target_id;

        function MostrarBloquear(id){
            Back.style.display = 'block';
            BloquearDialog.showModal();
            target_id = id;
        }

        async function Bloquear()
        {
            try {
                document.querySelector("#info").innerHTML = '<div class="info" id="info"></div>';
                const res = await axios.post('/api/usuarios/' + target_id, {
                    "Bloqueado" : 1
                });
                res.status;
                if(res.status == 200){
                    console.log("bloqueado com sucesso!");
                    document.querySelector("#info").innerHTML = '<div class="info" id="info"></div>';
                    document.querySelector("#functions-" + target_id).innerHTML = '<a class="button" id="block-button" onclick="Desbloquear(' + target_id + ');">Desbloquear</a>';
                    BloquearDialog.close();
                    Back.style.display = "none";
                }else{
                    console.log("Ocorreu um erro!");
                    document.querySelector("#info").innerHTML = '<div class="info" id="info"><div class="message erro"><p>Ocorreu um erro ao bloquear o usuário.</p></div></div>';
                }    
            } catch (error) {
                console.log(error);
                document.querySelector("#info").innerHTML = '<div class="info" id="info"><div class="message erro"><p>Ocorreu um erro ao bloquear o usuário.</p></div></div>';
            }
        }

        async function Desbloquear(id)
        {
            try {
                document.querySelector("#info").innerHTML = '<div class="info" id="info"></div>';
                const res = await axios.post('/api/usuarios/' + id, {
                    "Bloqueado" : 0
                });
                res.status;
                if(res.status == 200){
                    console.log("Desbloqueado com sucesso!");
                    document.querySelector("#info").innerHTML = '<div class="info" id="info"></div>';
                    document.querySelector("#functions-" + id).innerHTML = '<a class="button" id="block-button" onclick="MostrarBloquear(' + id + ');">Bloquear</a>';
                }else{
                    console.log("Ocorreu um erro!");
                    document.querySelector("#info").innerHTML = '<div class="info" id="info"><div class="message erro"><p>Ocorreu um erro ao desbloquear o usuário.</p></div></div>';
                }    
            } catch (error) {
                document.querySelector("#info").innerHTML = '<div class="info" id="info"><div class="message erro"><p>Ocorreu um erro ao desbloquear o usuário.</p></div></div>';
            }
                
            
        }
    </script>
</body>
</html>
