// 修改 preprocess_tpl 中的相应文件
define([

] , function() {
    'use strict';

//@if ENV='dev'
    var serverAddress = 'http://172.17.0.204:82/api/';
// @endif

// @if ENV='prod'
    var serverAddress = 'http://maoejie.com/api/';
// @endif 

    return {
        'serverAddress': serverAddress
    };
});

