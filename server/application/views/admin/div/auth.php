<div id="auth_box">
<div id="auth">
    <div class="auth-item"><span>管理员登录</span></div>

    <form action="do_login" method="post">
    <div class="auth-item">
        <input type="text" name="username" placeholder="用户名">
    </div>

    <div class="auth-item">
        <input type="password" name="password" placeholder="密码">
    </div class="auth-item">

    <div class="auth-item error">
        <?php if( !empty( $auth_error_info) ) { echo '登录失败 ' . $auth_error_info; } ?>
    </div>

    <div class="auth-item">
        <input type="submit" class="btn" value="登录">
    </div>
    </div>

</div>
</div>
