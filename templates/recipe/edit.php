<div class="container">

    <div class="row justify-content-between">
        <div class="col-6">
            <form action="<?=$vars["baseUrl"]?>recipe/list" method="post">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                        </div>
                        <input value="<?= @$_POST['search']['name']?>"
                            type="text" name="search[name]" class="form-control" id="searchInput" >
                    </div>
                </div>
            </form>
        </div>

        <div class="col-6">
            <a href="<?= $vars['baseUrl'] ?>article/add" class="btn btn-light">
                <i class="fas fa-plus-circle"></i><span>OK</span>
            </a>
        </div>
    </div>

</div>