<div class="container">
    <div class="row justify-content-between">
        <div class="col-5 col-md-4 col-lg-3">
            <form action="<?=$vars["baseUrl"]?>article/orders" method="post">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                        </div>
                        <input value="<?= $_POST['search']['name'] ?? "" ?>"
                            type="text" name="search" class="form-control" id="searchInput" >
                    </div>
                </div>
            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-7">
            <table class="table listing">
                <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Utilisateur</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Date</th>
                        <th scope="col">État</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['orders'] as $order): ?>
                    <tr>
                        <td><?= $order->getId() ?></td>
                        <td><?= $order->getUser()->getFullName() ?></td>
                        <td><?= $order->getTotal() ?></td>
                        <td><?= FormatUtil::frenchTime($order->getDateCreation()) ?></td>
                        <td><?= $order->getStatus() ?></td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="col-5">
            <h2>Détail</h2>
            <div id="order-items" class="h-100">
            </div>
        </div>
    </div>
</div>