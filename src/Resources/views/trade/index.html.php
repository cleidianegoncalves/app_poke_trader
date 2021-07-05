<?php $view->extend('base.html.php') ?>

<script type="text/javascript">
    function addPokemon(position, selectObject) {
        var pokemon_name = (selectObject.value);

        if ($('.pokemon_' + position).length > 5) {
            alert("O máximo permitido na troca é 6");
            return false;
        }

        var isSelected = isSelectedPokemon(position, pokemon_name);
        if (isSelected) {
            alert("Pokémon já selecionado: " + pokemon_name);
            return false;
        }

        $.ajax({
            contentType: "application/json",
            url: "/addPokemon?name=" + pokemon_name,
            beforeSend: function (xhr) {
            },
            success: function (response) {
                if (response) {
                    var clone = $('#div_model_pokemon').clone();

                    // labels
                    $(clone).find('#pokemon_name').html(response.name);
                    $(clone).find('#pokemon_img').attr('src', response.img);
                    $(clone).find('#pokemon_xp').html('Exp: ' + response.xp);

                    // inputs
                    $(clone).find('#pokemon_input_id').val(response.id);
                    $(clone).find('#pokemon_input_id').attr('name', 'pokemon[' + position + '][' + response.id + '][id]');
                    $(clone).find('#pokemon_input_name').val(response.name);
                    $(clone).find('#pokemon_input_name').attr('name', 'pokemon[' + position + '][' + response.id + '][name]');
                    $(clone).find('#pokemon_input_name').attr('class', 'pokemon_' + position + '');
                    $(clone).find('#pokemon_input_xp').val(response.xp);
                    $(clone).find('#pokemon_input_xp').attr('name', 'pokemon[' + position + '][' + response.id + '][xp]');
                    $(clone).find('#pokemon_input_img').val(response.img);
                    $(clone).find('#pokemon_input_img').attr('name', 'pokemon[' + position + '][' + response.id + '][img]');

                    $('#pokemons-' + position).append(clone);
                    $(clone).show();

                    calculateTotal(position, response.xp);
                } else {
                    alert("Pokémon selecionado não existe.");
                }
            },
            error: function (xhr) {
                console.log(xhr);
                alert("Erro: " + xhr);
            },
            complete: function (xhr) {
            }
        });
    }

    function isSelectedPokemon(position, pokemon_name) {

        var returno = false;
        $('.pokemon_' + position).each(function () {
            if ($(this).val() == pokemon_name) {
                returno = true;
            }
        });
        return returno;
    }

    function removePokemon(that) {
        if (confirm("Deseja realmente remover este pokémon?")) {
            $("#left").val(0);
            $("#right").val(0);

            var position = $(that).parents('.pokemon-group').attr("id");
            var exp = $(that).parent().siblings('#pokemon_input_xp').val();

            var position_adjust = position.replace("pokemons-", "");
            calculateTotal(position_adjust, -exp);

            $(that).parents('#div_model_pokemon').remove();
        }
    }

    function calculateTotal(position, exp) {
        var total = parseInt($('#total_' + position).html());
        var calculo = total + parseInt(exp);
        $('#total_' + position).html(calculo);

        var total_left = parseInt($("#total_left").html());
        var total_right = parseInt($("#total_right").html());
        $("#total").html(total_left - total_right);
    }

    function hasSelected(event) {
        event.preventDefault();

        if ($('.pokemon_left').length <= 0) {
            alert("Selecione ao menos um pokémon a esquerda");
            return false;
        }
        if ($('.pokemon_right').length <= 0) {
            alert("Selecione ao menos um pokémon a direita");
            return false;
        }

        return true;
    }

    function submitForm(event, save) {
        event.preventDefault();
        if (hasSelected(event))
        {
            var form = $('form[name="pokemon_form"]');

            $.ajax({
                url: form.attr('action') + "?save=" + save,
                type: "POST",
                data: form.serialize(),
                beforeSend: function (xhr) {
                },
                success: function (response) {
                    if (save) {
                        if (response.msg == "OK") {
                            $('#verifyModel').find('.modal-body').html("Salvo!");
                            document.location.reload(true);
                        } else {
                            $('#verifyModel').find('.modal-body').html(response.msg);
                        }
                    } else {
                        $('#verifyModel').find('.modal-body').html(response.msg);
                    }
                    $('#verifyModel').modal('show');
                },
                error: function (xhr) {
                    console.log(xhr);
                    alert("Erro: " + xhr);
                },
                complete: function (xhr) {
                }
            });
        }
    }
</script> 

<div class="wrapper container">
    <h1>Poke Trader</h1>

    <form action="/verify" method="POST" name="pokemon_form" onsubmit="submitForm(event, false)">
        <div class="row">
            <div class="col">
                <select class="form-select" id="left" onchange="addPokemon('left', this)">
                    <option value="0" disabled selected="">-Selecione-</option>
                    <?php foreach ($pokemons as $key => $pokemon) { ?>
                        <option value="<?php echo $pokemon ?>"><?php echo $pokemon ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col">
                <select class="form-select" id="right" onchange="addPokemon('right', this)">
                    <option value="0" disabled selected="">-Selecione-</option>
                    <?php foreach ($pokemons as $key => $pokemon) { ?>
                        <option value="<?php echo $pokemon ?>"><?php echo $pokemon ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div id="pokemons-left" class="col pokemon-group">
            </div>

            <div id="pokemons-right" class="col pokemon-group">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="form-group">Total Exp: <span id="total_left">0</span></label>
            </div>
            <div class="col">
                <label class="form-group">Total Exp: <span id="total_right">0</span></label>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary">Verificar</button>
                <a class="btn btn-info" href="/history" role="button">Histórico</a>
            </div>
            <div class="col" style="display:none;">
                Total Exp: <span id="total">0</span>
            </div>
        </div>
    </form>



    <div id="div_model_pokemon" style="display:none; border: 1px solid lightgray;">
        <div class="container">
            <div class="row">
                <button type="button" class="btn-close pull-right" aria-label="Close" onclick="removePokemon(this);"></button>
                <div class="col">
                    <img id="pokemon_img" src="" alt="Sem imagem">
                </div>
                <div class="col order-1" style="margin-top: 20px;">
                    <span id="pokemon_name"></span>
                    <br/>
                    <span id="pokemon_xp"></span>
                </div>
            </div>
            <input type="hidden" id="pokemon_input_id"/>
            <input type="hidden" id="pokemon_input_name"/>
            <input type="hidden" id="pokemon_input_xp"/>
            <input type="hidden" id="pokemon_input_img"/>
        </div>
    </div>


    <div class="modal fade" id="verifyModel" tabindex="-1" aria-labelledby="verifyModelLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyModelLabel">Verificação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm(event, true)">Salvar</button>
                </div>
            </div>
        </div>
    </div>

</div>