<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `medicalimages` where image_id= '{$_GET['id']}' and user_id = '{$_settings->userdata('user_id')}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            if (!is_numeric($k)) {
                $$k = $v;
            }
        }
    }
}
?>
<style>
    .form-group.note-form-group.note-group-select-from-files {
        display: none;
    }
</style>
<section class="py-4">
    <div class="container">
        <div class="card rounded-0 shadow">
            <div class="card-header">
                <h5 class="card-title"><?= !isset($image_id) ? "Add New" : "Update Details" ?></h5>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form action="" id="image-form" enctype="multipart/form-data">
                        <input type="hidden" name="image_id" value="<?= isset($image_id) ? $image_id : '' ?>">
                        <div class="form-group">
                            <label for="title" class="control-label">Title</label>
                            <input type="text" class="form-control rounded-0" name="title" id="title" value="<?= isset($title) ? $title : "" ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 px-0">
                            <label for="image_type" class="control-label">Image Type</label>
                            <select class="form-control rounded-0" required name="image_type" id="image_type">
                                <option value='Mammogram' <?= isset($image_type) && $image_type == "Mammogram" ? 'selected' : '' ?>>Mammogram</option>
                                <option value='Chest X-ray' <?= isset($image_type) && $image_type == "Chest X-ray" ? 'selected' : '' ?>>Chest X-ray</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image" class="control-label">Upload Image</label>
                            <input type="file" class="form-control-file" name="image_file" id="image_path">
                        </div>

                        <?php if (isset($image_path) && !empty($image_path)): ?>
                            <div class="form-group">
                                <label class="control-label">Current Image</label><br>
                                <img src="<?php echo isset($image_path) ? $image_path : ''; ?>" alt="image" style="max-width: 100%; height: auto;">
                            </div>
                        <?php else: ?>
                            <!-- <div class="form-group">
                                <small class="text-danger">No current image available</small>
                            </div> -->
                        <?php endif; ?>

                        <div class="form-group">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="status" name="status" value="1" <?= isset($status) && $status == 1 ? 'checked' : '' ?>>
                                <label for="status"></label>
                            </div>
                            <label for="status" class="control-label">Checked</label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-1 text-center">
                <button class="btn btn-flat btn-sm btn-primary bg-gradient-primary rounded-0" form="image-form"><i class="fa fa-save"></i> Save</button>
                <a class="btn btn-flat btn-sm btn-light bg-gradient-light border rounded-0" href="./?p=myImages"><i class="fa fa-angle-left"></i> Cancel</a>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        // $('#image_type').select2({
        //     placeholder: "Please Select Type of Image Here",
        //     width: '100%',
        //     containerCssClass: 'form-control rounded-0'
        // });


        $('#image-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            // var fileInput = document.getElementById("image_path");
            // if (fileInput.files.length > 0) {
            //     formData.append("image_path", fileInput.files[0]);
            // }
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_image",
                method: 'POST',
                data: formData,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                error: err => {
                    console.log(err);
                    alert('An error occurred');
                    end_loader();
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        location.replace('./?p=myImages/view_image&id=' + resp.pid);
                    } else if (resp.msg) {
                        alert(resp.msg);
                    } else {
                        alert('An error occurred');
                    }
                    end_loader();
                }
            });
        });
    });
</script>