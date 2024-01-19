<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <td class="text-left">Card No</td>
        <td class="text-left">Punch</td>
        <td class="text-left">PDay</td>
        <td class="text-left">Manual</td>
		<td class="text-left">InOut</td>
		<td class="text-left">Delete</td>
      </tr>
    </thead>
    <tbody>
		<?php if($punches){?>
		<?php foreach($punches as $punch){?>
		<tr>
			<td><?php echo $punch['card_no'];?></td>
			<td><?php echo $punch['punch_date'].' '.$punch['punch_time'];?></td>
			<td>N</td>
			<td><?php echo ($punch['punch_type']=='M')?'Y':'N';?></td>
			<td></td>
			<td><a class="btn-sm btn btn-danger btn-remove" href="<?php echo admin_url('mpunch/hdelete/'.$punch['id']);?>" onclick="return confirm('Are you sure?') ? true : false;"><i class="fa fa-trash-o"></i></a>
			</td>
		</tr>
		<?}?>
		<?}else{?>
		<tr><td colspan="6">No Punch Data Found</td></tr>
		<?}?>
    </tbody>
  </table>
</div>

