define( [
    "zepto"
] , function( $ ) {
    "use strict";

    //( $(), $() ) => void
    var drag = function( $handle, $target ) {
    //{{{
        //用於捕獲 scroll 信息
        var $body = $( 'body' );
        var $document = $( document );

        $handle.on( 'mousedown', function( event ) {
            var mouseStartX = event.clientX - $body.scrollLeft();
            var mouseStartY = event.clientY - $body.scrollTop();

            //獲取本元素的初始位置
            var originX = $target.get(0).offsetLeft;
            var originY = $target.get(0).offsetTop;

            //計算本元素需要進行的位置偏移 並設置之
            var delteX = mouseStartX - originX;
            var delteY = mouseStartY - originY;

            $handle.css( 'cursor', 'move' );

            $document.on({
                'mousemove': function( event ) {
                    $target.css({
                        //初始化 css 屏蔽之前的信息
                        'margin-left': '0',
                        'margin-top': '0',
                        'left': ( event.clientX + $body.scrollLeft() - delteX ) + 'px',
                        'top': ( event.clientY + $body.scrollLeft() - delteY ) + 'px',
                    });
                },
                'mouseup': function( event ) {
                    //用戶釋放鼠標 取消 handle 事件綁定
                    $document.off( 'mouseup' ).off( 'mousemove' );
                    $handle.css( 'cursor', 'auto' );
                    event.stopPropagation();
                }
            });

            event.stopPropagation();
        });
    };//}}}

    //( string, number ) => string
    var cutTextByMaxLength = function( text, maxLength ) {
    //{{{
        var cutStr = '';

        if( typeof text === 'string' && typeof maxLength === 'number' ) {
            if( text.length > maxLength ) {
                cutStr = helper.chineseSubStr( text, 0, maxLength ) + ' ...';
            } else {
                cutStr = text;
            }
        }

        return cutStr;
    };//}}}

    //浮点数乘法
    var floatMul = function( arg1 , arg2 ) {
    //{{{
        var m=0,s1=arg1.toString(),s2=arg2.toString(); 
        try{m+=s1.split(".")[1].length}catch(e){} 
        try{m+=s2.split(".")[1].length}catch(e){} 
        return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m) 
    };//}}}

    //浮点数加法
    var floatAdd = function( arg1 , arg2 ) {
    //{{{
        var r1 , r2 , m;
        try{
                r1=arg1.toString().split(".")[1].length;
        }catch(e){
                r1=0; 
        }
        try{
                r2=arg2.toString().split(".")[1].length;
        }catch(e){
                r2=0
        }
        m = Math.pow(10,Math.max(r1,r2));
        return ( parseInt( this.FloatMul( arg1 , m ) ) + parseInt( this.FloatMul( arg2 , m ) ) ) / m;
    };//}}}

    //计算用户剩余可cutTextByMaxLength输入字数
    //先变为数组
    //@param object el 需要操作的元素
    var countLength = function( el ) {
    //{{{
        //var reg = new RegExp('[\u4E00-\u9FA5]');
        var reg = new RegExp( '[^\x00-\xff]' );
        var not_cc_count = 0;

        //计算用户剩余可输入字数
        //先变为数组
        var user_input_string_array = el.val().split( '' );
        var length = 0;

        for ( var i in user_input_string_array ){
            if( reg.test( user_input_string_array[i] ) ? true : false ){
                //为汉字加1
                length++;
            }else{
                //不为汉字 计数器加1
                not_cc_count++;	 
                if( not_cc_count == 2 ){
                        length++;
                        not_cc_count = 0;
                }
            }
        }

        if( not_cc_count == 1 ) {
            length++;
        }

        return length;
    };//}}}

    var substr = function( str , len ) {
    //{{{
        if(!str || !len) { return ''; }
        var a = 0;
        var i = 0;
        var temp = '';
        for (i=0;i<str.length;i++) {
            if (str.charcodeat(i)>255) {
                a+=2;
            } else {
                a++;
            }
            //如果增加计数后长度大于限定长度，就直接返回临时字符串
            if(a > len) { return temp; }  
            temp += str.charat(i);
        }
        return str; 
    };//}}}

    //中文取子串
    var chineseSubStr = function( str , begin , num ) {
    //{{{
        var ascRegexp = /[^\x00-\xFF]/g, i = 0;
        while(i < begin) (i ++ && str.charAt(i).match(ascRegexp) && begin --);
        i = begin;
        var end = begin + num;
        while(i < end) (i ++ && str.charAt(i).match(ascRegexp) && end --);
        return str.substring(begin, end);
    };//}}}

    //自动增长的 textarea ,
    //采用的方案就是 将文本内容复制到另外一个 div 元素中
    //获取其 height 和当前的 textarea 进行一个对比
    var autoGrowHeight = function( $textarea ) {
    //{{{
        var growStep = 32;
        var oriHeight = $textarea.height();
        var oriwidth = $textarea.width();

        //生成一个 div 
        var $tmpDiv = $( "<div id='foo' style='dislpay:none;width: " + oriwidth + "px'></div>" );
        $( "body" ).append( $tmpDiv );

        $textarea.on( "keydown" , function() {
            var val = $textarea.val().replace( /\n/g , '<br/>' );

            var nowHeight = $tmpDiv.html( val ).height();

            if( nowHeight >= oriHeight ) {
                var height = nowHeight + growStep;
                $textarea.attr( "style" , "min-height:" + height + "px" );
            } else {
                //@TODO 用户删除之后的还原问题
            }
        });

    };//}}}

    var helper = {
        drag: drag,
        cutTextByMaxLength: cutTextByMaxLength,
        floatMul: floatMul,
        floatAdd: floatAdd,
        countLength: countLength,
        substr: substr,
        chineseSubStr: chineseSubStr,
        autoGrowHeight: autoGrowHeight
    };

    return helper;

});
