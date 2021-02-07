<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8 col-md-6">
            <div class="login-form w-100">
                <form action="<?=$vars["baseUrl"]?>user/login" method="post">
                    <h3 class="text-center">Connexion</h3>       
                    <div class="form-group">
                        <input type="text" name="Users[login]" class="form-control" placeholder="Username" required="required">
                    </div>
                    <div class="form-group">
                        <input type="password" name="Users[password]" class="form-control" placeholder="Password" required="required">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Log in</button>
                    </div>   
                </form>
            </div>
        </div>
    </div>
</div>