<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">ARQUIVOS</h3>
        </div>

        <div class="col-md-12">
            <form action="controller/arquivo_adiciona.php" method="POST" enctype="multipart/form-data" style="width: 100%">
                <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "arquivo_obrigatorio"); ?>">
                <input name="id_obrigatorio" hidden value="<?php echo  $obrigatorio->getId() ?>">
                <input hidden type="text" value="<?php echo $crip_url ?>" name="crip_url">


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="nome_arquivo" placeholder="Nome do arquivo">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="file" style="width: 100%;" class="btn btn-primary btn-block" name="arquivo">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">ENVIAR ARQUIVO</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-12">

            <br>
            <hr>
            <br>

            <?php
            if (!empty($arquivos)) {
                foreach ($arquivos as $arquivo) {

                    $crip = hash('sha256', $arquivo->getNome() . "crip_download_arquivo");
            ?>
                    <div class="col-md-12">
                        <form action="controller/arquivo_apaga.php" method="POST" style="width: 100%">
                            <input name="crip" hidden value="<?php echo  hash('sha256', $arquivo->getId() . "criptografia_arquivo"); ?>">
                            <input name="id_arquivo" hidden value="<?php echo $arquivo->getId() ?>">
                            <input name="id_obrigatorio" hidden value="<?php echo $id_obrigatorio ?>">
                            <input name="crip_url" hidden value="<?php echo $crip_url ?>">
                            <input name="chave" hidden value="<?php echo $_SESSION['chave'] ?>">

                            <a target='_blank' href='controller/arquivo_download.php?crip=<?php echo $crip ?>&id_arquivo=<?php echo $arquivo->getId() ?>'>

                                <img src='imagens/pdf.png' height='60px'>
                                <?php echo $arquivo->getLabel() ?>
                                &nbsp;&nbsp;&nbsp;
                            </a>
                            <button type="submit" onclick="return confirm('Tem certeza que deseja EXCLUIR este arquivo?')">Apagar</button>
                            <br>
                            <br>
                            <br>
                        </form>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>