<div id="main">
<div id="applies-list">
<table>

    <thead>
        <tr>
            <th>#</th>
            <th>提交人</th>
            <th>审核人电话</th>
            <th>提交审核时间</th>
            <th>操作</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach( $applies_info as $key=>$value ) { ?>
        <tr>
            <td><?php echo $value['id']; ?></td>
            <td><?php echo $value['shopkeeper_name']; ?></td>
            <td><?php echo $value['shopkeeper_tel']; ?></td>
            <td><?php echo $value['apply_time']; ?></td>
            <td class='func'>
                <a class="no_pass" data-attr="<?php echo $value['id']; ?>" href="javascript:void(0);">未通过</a> |
                <a class="pass" href="/admin/main/pass/<?php echo $value['id']; ?>">通过</a> 
            </td>
        </tr>
        <?php } ?>
    </tbody>

</table>
</div>
</div>

<div id="input-failed-message-box" class="dialog-box">
<div id="input-failed-message" class="dialog">
    <div class="dialog-item">
        <textarea name="failed_message" placeholder="审核失败原因"></textarea>
    </div>
    <div class="dialog-item">
        <span class="hint">点击对话框外部任意位置,隐藏该对话框</span>
    </div>
    <div class="dialog-item">
        <span class="btn ok">提交</span>
    </div>
</div>
</div>

<div id="sys-notice">
</div>

<script>
var Dialog = function( id ) {
    var that = this;

    this.$el = $( '#' + id );
    this.$box = $( '#' + id + '-box' );

    this.$box.on( 'click' , function( e ) {
        var $targetEl = $( e.target );
        if( $targetEl.hasClass( 'dialog-box' ) ) {
            that.hide();
        }
    });

    this.$el.find( '.ok' ).on( 'click' , function() {
        that.ok();
    })
};

Dialog.prototype = {
    show: function() {
        this.$el.show();
        this.$box.show();
    },

    hide: function() {
        this.$el.hide();
        this.$box.hide();
    }
};

var SysNotice = function() {
    this.$el = $( '#sys-notice' );
};

SysNotice.prototype = {
    show: function() {
        var that = this;
        this.$el.show();
        setTimeout( function() {
            that.hide();
        }, 1000);
        return this;
    },
    hide: function() {
        this.$el.hide();
        return this;
    },
    setMsg: function( msg ) {
        this.$el.text( msg );
        return this;
    }
};

var sysNotice = new SysNotice();

$(function() {
    var $appliesList = $( '#applies-list' );

    $appliesList.on( 'click' , '.no_pass' , function( e ) {
        var $thisLink = $( e.currentTarget );
        var applyId = $thisLink.attr( 'data-attr' );

        //display dialog
        var inputFailedMessageDialog = new Dialog( 'input-failed-message' );
        inputFailedMessageDialog.ok = function() {
            this.$textarea = this.$textarea || this.$el.find( 'textarea' );
            var userInputMsg = this.$textarea.val();

            $.post(
                '/admin/main/failed_message/',
                {
                    id: applyId,
                    msg: userInputMsg
                },
                function( data , status ) {
                    if( status === 'success' && data === 'ok' ) {
                        sysNotice.setMsg( 'ok' ).show();
                        $thisLink.parent().parent().remove();
                        inputFailedMessageDialog.hide();
                    }
                }
            );
        }

        inputFailedMessageDialog.show();
    });

});
</script>

