<?php
return [
    // 路由规则：app/controller=>['ControllerPath','请求方式',['接口参数类型=>校验作用']]
    "index/index" => ["index/controller/Index.php", 'post,get', ['id' => ['int', 1], 'name' => ['string', 1]]],
];