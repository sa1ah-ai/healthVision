<?php if ($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title font-weight-bold">List of Results</h3>
		<div class="card-tools">
			<!-- <a href="./?page=results/manage_result" id="" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a> -->
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="15%">
					<col width="17%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th># </th>
						<th>Image Title</th>
						<th>Image Type</th>
						<th>Diagnosis</th>
						<th>Confidence</th>
						<th>status</th>
						<th>created_at</th>
						<th>Action</th>

					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT 
                            res.result_id, 
                            mi.title,
                            mi.image_type,
                            res.diagnosis, 
                            res.confidence, 
                            res.created_at, 
                            mi.image_id,
                            mi.user_id,
                            mi.delete_flag,
                            res.image_id, 
                            res.status, 
                            res.delete_flag 
                            FROM `medicalimages` mi
                            inner join
                            `diagnosticresults` res on mi.image_id = res.image_id
                            where
                            res.delete_flag = 0
                            and
                            mi.delete_flag = 0
                            order by abs(unix_timestamp(res.created_at)) asc ");
					while ($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td>
								<p class="truncate-1 mb-0"><?php echo $row['title'] ?></p>
							</td>
							<td><?php echo $row['image_type'] ?></td>
							<td><?php echo $row['diagnosis'] ?></td>
							<td><?php echo $row['confidence'] ?></td>
							<td class="text-center">
								<?php if ($row['status'] == 'Reviewed'): ?>
									<span class="badge badge-success px-3 rounded-pill">Reviewed</span>
								<?php else: ?>
									<span class="badge badge-danger px-3 rounded-pill">Pending</span>
								<?php endif; ?>
							</td>
							<td><?php echo date("Y-m-d H:i", strtotime($row['created_at'])) ?></td>


							<td align="center">
								<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item" href="./?page=results/view_result&id=<?= $row['result_id'] ?>" data-id="<?php echo $row['result_id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
									<div class="dropdown-divider"></div>
									<!-- <a class="dropdown-item" href="./?page=results/manage_result&id=<?php echo $row['result_id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a> -->
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['result_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.delete_data').click(function() {
			_conf("Are you sure to delete this post permanently?", "delete_result", [$(this).attr('data-id')])
		})
		$('#create_new').click(function() {
			uni_modal("<i class='fa fa-plus'></i> Add New Result", "posts/manage_result.php")
		})
		$('.view_data').click(function() {
			uni_modal("<i class='fa fa-bars'></i> Result Details", "posts/view_result.php?id=" + $(this).attr('data-id'))
		})
		$('.edit_data').click(function() {
			uni_modal("<i class='fa fa-edit'></i> Update Result Details", "posts/manage_result.php?id=" + $(this).attr('data-id'))
		})
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: [4, 5]
			}],
			order: [0, 'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})

	function delete_result($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_result",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occured.", 'error');
					end_loader();
				}
			}
		})
	}
</script>