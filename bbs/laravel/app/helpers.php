<?php

    /**
     * 将请求的路由名称转换为 CSS 类名称
     * 为了后续针对 ./resources/views/layouts/app.balde.php 中的页面做页面样式定制
     */
    function route_class() {
        return str_replace('.', '-', Route::currentRouteName());
    }
