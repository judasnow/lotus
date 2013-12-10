<ul id="nav">
    <li class="nav-item title">Lotus admin</li>

    <li class="nav-item <?php if( $page_name === 'home' ) {?> nav-item-active <?php } ?>">
        <a href="/admin/main/">首页</a>
    </li>
    <li class="nav-item <?php if( $page_name === 'applying' ) {?> nav-item-active <?php } ?>">
        <a href="/admin/main/applying/">待审核店铺</a>
    </li>
    <li class="nav-item <?php if( $page_name === 'applied' ) {?> nav-item-active <?php } ?>">
        <a href="/admin/main/applied/">审核完成店铺</a>
    </li>
    <li class="nav-item">
        <a href="/admin/main/do_logout/">退出登录</a>
    </li>

    <li class="nav-item">|</li>
    <li class="nav-item">管理员: <?php echo $admin_info['username']; ?></li>
</ul>

