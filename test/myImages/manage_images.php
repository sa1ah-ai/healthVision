<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `medicalimages` where image_id= '{$_GET['id']}' and user_id = '{$_settings->userdata('id')}'");
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
                    <form action="" id="image-form">
                        <input type="hidden" name="image_id" value="<?= isset($image_id) ? $image_id : '' ?>">
                        <div class="form-group">
                            <label for="title" class="control-label">Title</label>
                            <input type="text" class="form-control rounded-0" name="title" id="title" value="<?= isset($title) ? $title : "" ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 px-0">
                            <label for="image_type" class="control-label">Image Type</label>
                            <select class="form-control rounded-0" required name="image_type" id="image_type">
                                <option value='Mammogram' <?php echo isset($image_type) && $image_type == "Mammogram" ? 'selected' : '' ?>>Mammogram</option>
                                <option value='Chest ' <?php echo isset($image_type) && $image_type == "Chest" ? 'selected' : '' ?>>Chest</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image" class="control-label">Image</label>
                            <textarea type="text" class="form-control rounded-0" name="image" id="image"><?= isset($image) ? $image : "" ?></textarea>
                        </div>
                        <div class="form-group">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="status" name='status' value="1" <?= isset($status) && $status == 1 ? 'checked' : '' ?>>
                                <label for="status">
                                </label>
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
        $('#image_type').select2({
            placeholder: "Please Select Type of Image Here",
            width: '100%',
            containerCssClass: 'form-control rounded-0'
        })
        $('#image').summernote({
            height: "20em",
            placeholder: "upload your image here",
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['picture']],
                ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
            ]
        })
        $('#image-form').submit(function(e) {
            e.preventDefault()
            var _this = $(this)
            var el = $('<div>')
            el.addClass('alert alert-danger err_msg')
            el.hide()
            $('.err_msg').remove()
            if (_this[0].checkValidity() == false) {
                _this[0].reportValidity();
                return false;
            }
            start_loader()
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_image",
                method: 'POST',
                type: 'POST',
                data: new FormData($(this)[0]),
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                error: err => {
                    console.log(err)
                    alert('An error occurred')
                    end_loader()
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        location.replace('./?p=myImages/view_image&id=' + resp.pid)
                    } else if (!!resp.msg) {
                        el.html(resp.msg)
                        el.show('slow')
                        _this.prepend(el)
                        $('html, body').scrollTop(0)
                    } else {
                        alert('An error occurred')
                        console.log(resp)
                    }
                    end_loader()
                }
            })
        })
    })
</script>