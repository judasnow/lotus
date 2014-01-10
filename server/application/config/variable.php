<?php
/**
 * 该文件设置系统中的一些常量
 */

//首页热门商品数量
$config['popular_product_num'] = 8;

//首页热门店铺数量
$config['popular_shop_num']    = 8;

//按关键词、按分类搜索结果每页商品数量
$config['search_result_product_page_num'] = 12;

//店铺展示每页商品数量
$config['shop_show_product_page_num'] = 12;





//Qiniuyun设置
$config['qiniuyun_image_expire_time'] = 6 * 60 * 60;//过期时间为 6 小时

$hostname = gethostname();


/*-------------------------------------------------------------------------------------*/
if (false && PHP_OS == 'Darwin' || $hostname == 'x200' ) {
    //开启的节点
    $config['qiniuyun_access_nodes'] = ['maoejie'];
    
    //Qiniuyun已经存在的帐号
    $config['qiniuyun_config'] = array(
        'maoejie' => array(
            '_accessKey' => 'ylMDiB_RbB1C1JRaEWHg8fqLmCXxf7RuBbOLKCZu',
            '_secretKey' => 'bHcsEs2YvYKZDjimcHzQDQEwOaeVKPgPmYENztAT'
        )
    );
    
    //七牛云存储节点映射关系
    $config['qiniuyun_nodes'] = ['nodea'];

    $config['qiniuyun_reflection_config'] = ['nodea' => 'maoejie'];
    
    
} else {
    //开启的节点
    $config['qiniuyun_access_nodes'] = ['maoejienodea', 'maoejienodeb'];

    //Qiniuyun已经存在的帐号
    $config['qiniuyun_config'] = array(
        'maoejienodea' => array(
            '_accessKey' => 'SP7ObxUeG0O4XyPAbDw5iNNkcMutSANU24cy-mSc',
            '_secretKey' => 'B6EQmnfHfTTCv76tbmayw-58sfnPxeOFHpQoZu7e'
        ),
        'maoejienodeb' => array(
            '_accessKey' => 'IBcu5uUfTOhRyjizn28arwPkXbgffLmrkl5OSI_7',
            '_secretKey' => 'ppk8yRVfWqHW3iPikUB57GeLROcYTnkY4EEE7oat'
        )
    );
    
    //七牛云存储节点映射关系
    $config['qiniuyun_nodes'] = ['nodea', 'nodeb'];
    
    $config['qiniuyun_reflection_config'] = ['nodea' => 'maoejienodea', 'nodeb' => 'maoejienodeb'];

}


