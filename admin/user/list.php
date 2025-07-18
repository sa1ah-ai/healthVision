<?php if ($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<style>
	.user-avatar {
		width: 3rem;
		height: 3rem;
		object-fit: scale-down;
		object-position: center center;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">List of Users</h3>
		<div class="card-tools">
			<a href="./?page=user/manage_user" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="12%">
					<col width="8%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Username</th>
						<th>Email</th>
						<th>Gender</th>
						<th>Date of birth</th>
						<th>Avatar</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query(" SELECT u.*, p.*
										FROM Users u
										JOIN Patients p ON u.user_id = p.patient_id
										where user_id != '{$_settings->userdata('user_id')}' ");
					while ($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['name'] ?></td>
							<td><?php echo $row['username'] ?></td>
							<td><?php echo $row['email'] ?></td>
							<td><?php echo $row['gender'] ?></td>
							<td><?php echo date("Y-m-d H:i", strtotime($row['date_of_birth'])) ?></td>
							<td class="text-center">
								<img src="<?= validate_image($row['avatar']) ?>" alt="" class="img-thumbnail rounded-circle user-avatar">
							</td>
							<td class="text-center">
								<?php if ($row['role'] == 'doctor'): ?>
									Doctor User
								<?php elseif ($row['role'] == 'patient'): ?>
									Patient User
								<?php else: ?>
									N/A
								<?php endif; ?>
							</td>
							<td align="center">
								<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item" href="./?page=user/manage_user&id=<?= $row['user_id'] ?>"><span class="fa fa-edit text-dark"></span> Edit</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['user_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
			_conf("Are you sure to delete this User permanently?", "delete_user", [$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: [6]
			}],
			order: [0, 'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})

	function delete_user($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Users.php?f=delete",
			method: "POST",
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					location.reload();
				} else {
					// alert_toast("An error occured.", 'error');
					// end_loader();
					location.reload();
				}
			},
			error: err => {
				// console.log(err)
				// alert_toast("An error occured.", 'error');
				// end_loader();
				location.reload();
			}

		})
	}
</script>