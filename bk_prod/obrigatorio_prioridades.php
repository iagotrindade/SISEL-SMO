<br>
<center> <b><font size="5" color="green">Prioridades das cidades</font></b></center>

<form action="controller/obrigatorio_prioridade_gu_cadastra.php" method="post" role="form" >
    <div class="row card">
        <div class="col-md-4 form-group">
            <select id="combobox" name="id_guarnicao" class="form-control">
                <option value="nomegu">Selecione a Guarnição</option>
                <?php   
                    foreach ($todas_gu as $guarnicao) 
                    {
                        $imprimegu = true;
                        if($prioridade_guarnicao)
                        foreach ($prioridade_guarnicao as $value) 
                        {
                        if ($value['id_guarnicao'] == $guarnicao['id']) 
                        {
                            $imprimegu = false;
                            break; 
                        }
                        }
                        if($imprimegu)
                        echo "<option value='" . $guarnicao['id'] . "' >" . $guarnicao['nome'] . "</option>";
                    }
                ?>
            </select>
            <input hidden id="nomegu" name="nomegu">
            <script>
                    // Captura o evento de alteração da combobox
                    document.getElementById('combobox').addEventListener('change', function() {
                    // Obtém o valor e o texto da opção selecionada
                    var selectedOption = this.options[this.selectedIndex];
                    var optionValue = selectedOption.value;
                    var optionText = selectedOption.text;
                    // Exibe o valor selecionado na div
                    document.getElementById('nomegu').value = optionText;
                });
            </script>
        </div>
        <div class="col-md-4 form-group">
            <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
            <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."obrigatorio"); ?>">
            <div class="text-center"><button type="submit">Definir</button></div>
        </div>
    </div>
    
</form>


<br>
<br>

<div class="row card" >
    <div class="col-lg-12">
        <form action="controller/obrigatorio_prioridade_gu_apaga.php" method="post" role="form" name="nomegu">
            <div class="row">
            <div class="col-md-4 form-group" >
            <b>Prioridades: </b> 
            </div>      
            </div>  
            
            <div class="row">
            <div class="col-md-4 form-group" >
            <b>
            <?php 
                if($prioridade_guarnicao)
                {
                    foreach ($prioridade_guarnicao as $value) 
                    {
                    echo $value['prioridade'] . "ª ";
                    echo $value['guarnicao'];
                    echo "<br>";
                    $nomegu = $value['guarnicao'];
                    }
                }
                else echo "Nenhuma Prioridade cadastrada";
            ?>
            </b>  
            </div>      
            </div>  
            <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
            <div class="text-center"><button type="submit">Apagar</button></div>
        </form>
    </div>
</div>