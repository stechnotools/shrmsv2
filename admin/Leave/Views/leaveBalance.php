<h5><?=$heading?></h5>
<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th><strong>Leave Type</strong></th>
            <th><strong>Carry Forward</strong></th>
            <th><strong>Current Leave</strong></th>
            <th><strong>Total Leave</strong></th>
            <th><strong>Taken</strong></th>
            <th><strong>Balance</strong></th>
        </tr>
        <?php foreach($leavebalance as $lb){?>
        <tr>
            <td><strong><?=$lb['leave_name']?></strong></td>
            <td><?=$lb['carried_leave']?></td>
            <td><?=$lb['leave_opening_total']?></td>
            <td><?=$lb['leave_total']?></td>
            <td><?=$lb['leave_taken_total']?></td>
            <td><?=$lb['leave_balance']?></td>
        </tr>
        <?}?>

        <tr>
            <td class="l_report"><strong>Total</strong>:</td>
            <td class="l_report"><?=$total['total_carried_leave']?></td>
            <td class="l_report"><?=$total['total_leave_opening_total']?></td>
            <td class="l_report"><?=$total['total_leave_total']?></td>
            <td class="l_report"><?=$total['total_leave_taken_total']?></td>
            <td class="l_report"><?=$total['total_balance_total']?></td>

        </tr>
    </tbody>
</table>