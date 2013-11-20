<div id="main">
<div id="applies-list">
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>提交人</th>
            <th>联系方式</th>
            <th>提交审核时间</th>
            <th>审核时间</th>
            <th>审核结果</th>
            <th>注册码</th>
            <th>未通过原因</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach( $applies_info as $key=>$value ) {?>
        <tr>
            <td><?php echo $value['id']; ?></td>
            <td><?php echo $value['shopkeeper_name']; ?></td>
            <td><?php echo $value['shopkeeper_tel']; ?></td>
            <td><?php echo $value['apply_time']; ?></td>
            <td><?php echo $value['verified_time']; ?></td>
            <td><?php echo $value['decision']; ?></td>
            <td><?php echo $value['register_code']; ?></td>
            <td><?php echo $value['failed_message']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</div>
</div>

