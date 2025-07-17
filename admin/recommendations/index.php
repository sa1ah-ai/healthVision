<?php if ($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">List of Recommendations</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="3%">
					<col width="7%">
					<col width="17%">
					<col width="17%">
					<col width="15%">
					<col width="8%">
					<col width="5%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th># </th>
						<th>confidence</th>
						<th>recommendation</th>
						<th>diagnosis</th>
						<th>Image</th>
						<th>image_type</th>
						<th>title</th>
						<th>created_at</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT rec.*, mi.*, res.*, u.* FROM `recommendations` rec
                                                LEFT JOIN 
                                                diagnosticresults res ON res.result_id = rec.result_id
                                                LEFT JOIN 
                                                medicalimages mi ON mi.image_id = res.image_id
                                                LEFT JOIN 
                                                users u ON rec.doctor_id = u.user_id
												order by abs(unix_timestamp(rec.created_at)) asc;");
					while ($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['confidence'] ?></td>
							<!-- <td><?php #echo date("Y-m-d H:i",strtotime($row['date_created'])) 
										?></td> -->
							<td><?php echo $row['recommendation'] ?></td>
							<td>
								<p class="mb-0 truncate-1"><?php echo $row['diagnosis'] ?></p>
							</td>
							<td class="text-center">
								<img src="<?= validate_image($row['image_path']) ?>" alt="" class="img-thumbnail rounded-circle user-avatar">
							</td>



							<td>
								<p class="mb-0 truncate-1"><?php echo $row['image_type'] ?></p>
							</td>
							<td>
								<p class="mb-0 truncate-1"><?php echo $row['title'] ?></p>
							</td>
							<td>
								<p class="mb-0 truncate-1"><?php echo $row['created_at'] ?></p>
							</td>

							<!-- <td class="text-center">
                                <?php #if($row['status'] == 1): 
								?>
                                    <span class="badge badge-success px-3 rounded-pill">Active</span>
                                <?php #else: 
								?>
                                    <span class="badge badge-danger px-3 rounded-pill">Inactive</span>
                                <?php #endif; 
								?>
                            </td> -->
							<td align="center">
								<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<!-- <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['recommendation_id'] ?>"><span class="fa fa-eye text-dark"></span> View & Edit</a> -->
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="./?page=recommendations/view_recommendation&id=<?= $row['result_id'] ?>" data-id="<?php echo $row['recommendation_id'] ?>"><span class="fa fa-eye text-dark"></span> View & Edit</a>
									<div class="dropdown-divider"></div>
									<!-- <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['recommendation_id'] ?>"><span class="fa fa-edit text-primary"></span> </a> -->
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['recommendation_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
			_conf("Are you sure to delete this Recommendation permanently?", "delete_recommendation", [$(this).attr('data-id')])
		})
		$('#create_new').click(function() {
			uni_modal("<i class='fa fa-plus'></i> Add New Recommendation", "recommendations/manage_recommendation.php")
		})
		$('.view_data').click(function() {
			uni_modal("<i class='fa fa-bars'></i> Recommendation Details", "recommendations/view_recommendation.php?id=" + $(this).attr('data-id'))
		})
		$('.edit_data').click(function() {
			uni_modal("<i class='fa fa-edit'></i> Update Recommendation Details", "recommendations/manage_recommendation.php?id=" + $(this).attr('data-id'))
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

	function delete_recommendation($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_recommendation",
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