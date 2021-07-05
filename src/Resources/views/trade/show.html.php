<?php $view->extend('base.html.php') ?>
<div class="wrapper container">
    <h1>Visualização de troca</h1>
    <a class="btn btn-light" href="/history" role="button">Voltar</a>

    <?php if ($trade) { ?>
        <div class="row">
            <div id="pokemons-left" class="col pokemon-group">
                <?php foreach ($trade->getPokemonsLeft() as $pokemon) { ?>
                    <div id="div_model_pokemon" style="border: 1px solid lightgray;">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <img id="pokemon_img" src="<?php echo $pokemon["img"] ?>" alt="Sem imagem">
                                </div>
                                <div class="col order-1" style="margin-top: 20px;">
                                    <?php echo ucwords($pokemon["name"]) ?>
                                    <br/>
                                    Exp: <?php echo $pokemon["xp"] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div id="pokemons-right" class="col pokemon-group">
                <?php foreach ($trade->getPokemonsRight() as $pokemon) { ?>
                    <div id="div_model_pokemon" style="border: 1px solid lightgray;">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <img id="pokemon_img" src="<?php echo $pokemon["img"] ?>" alt="Sem imagem">
                                </div>
                                <div class="col order-1" style="margin-top: 20px;">
                                    <?php echo ucwords($pokemon["name"]) ?>
                                    <br/>
                                    Exp: <?php echo $pokemon["xp"] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="form-group">Total Exp: <?php echo $trade->getBaseExperienceLeft() ?></label>
            </div>
            <div class="col">
                <label class="form-group">Total Exp: <?php echo $trade->getBaseExperienceRight() ?></label>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php if ($trade->getIsFair()) { ?>
                    <div class="alert alert-success" role="alert">
                        A troca é justa
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger" role="alert">
                        A troca não é justa
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        Sem visualição       
    <?php } ?>

</div>