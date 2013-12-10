define( [
    "zepto"
] , function( $ ) {

    "use strict";

    var helper = {

        //浮点数乘法
        FloatMul: function( arg1 , arg2 ) {
        //{{{
            var m=0,s1=arg1.toString(),s2=arg2.toString(); 
            try{m+=s1.split(".")[1].length}catch(e){} 
            try{m+=s2.split(".")[1].length}catch(e){} 
            return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m) 
        },//}}}

        //浮点数加法
        FloatAdd: function( arg1 , arg2 ) {
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
        },//}}}

        //计算用户剩余可输入字数
        //先变为数组
        //@param object el 需要操作的元素
        countLength: function( el ) {
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
        },//}}}

        substr: function( str , len ) {
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
        },//}}}

        //中文取子串
        chineseSubStr: function( str , begin , num ) {
        //{{{
            var ascRegexp = /[^\x00-\xFF]/g, i = 0;
            while(i < begin) (i ++ && str.charAt(i).match(ascRegexp) && begin --);
            i = begin;
            var end = begin + num;
            while(i < end) (i ++ && str.charAt(i).match(ascRegexp) && end --);
            return str.substring(begin, end);
        },//}}}

        //自动增长的 textarea ,
        //采用的方案就是 将文本内容复制到另外一个 div 元素中
        //获取其 height 和当前的 textarea 进行一个对比
        autoGrowHeight: function( $textarea ) {
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

        }//}}}
    }

    return helper;

});
