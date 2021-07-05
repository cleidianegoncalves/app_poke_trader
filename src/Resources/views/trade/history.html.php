<?php $view->extend('base.html.php') ?>
<div class="wrapper">
    <h1>Histórico</h1>
    <a class="btn btn-light" href="/trade" role="button">Voltar</a>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Data</th>
                <th scope="col">Visualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($trades) {
                foreach ($trades as $trade) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $trade->getId() ?></th>
                        <td><?php echo $trade->getDateInsert()->format("d/m/Y H:i:s") ?></td>
                        <td><a class="btn btn-info" href="/show/<?php echo $trade->getId(); ?>" role="button">Visualizar</a></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <th colspan=3 scope="row"> Sem histórico para ser visualizado </th>
                </tr>
            <?php } ?>

        </tbody>
    </table>

    <?php if ($trades && count($pages) > 1) { ?>
        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="/history">Primera</a></li>

                <?php foreach ($pages as $page) { ?>
                    <li class="page-item <?php echo $page == $pageSelected ? "active" : "" ?>"><a class="page-link" href="/history?PAGE=<?php echo $page ?>"><?php echo $page ?></a></li>
                <?php } ?>

                <li class="page-item"><a class="page-link" href="/history?PAGE=<?php echo count($pages) ?>">Última</a></li>
            </ul>
        </nav>
    <?php } ?>

</div>