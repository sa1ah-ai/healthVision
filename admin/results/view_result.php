<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT 
                        res.result_id, 
                        mi.title,
                        mi.image_type,
                        mi.image_path,
                        res.diagnosis, 
                        res.confidence, 
                        res.created_at, 
                        mi.image_id,
                        mi.user_id,
                        mi.delete_flag,
                        res.status
                        FROM `medicalimages` mi
                        INNER JOIN `diagnosticresults` res ON mi.image_id = res.image_id
                        WHERE res.delete_flag = 0
                        AND mi.delete_flag = 0
                        AND res.result_id = '{$_GET['id']}'");

    if ($qry->num_rows > 0) {
        $result = $qry->fetch_assoc();
        foreach ($result as $k => $v) {
            if (!is_numeric($k)) {
                $$k = $v;
            }
        }
    } else {
        echo '<script>alert("Result ID is not recognized."); location.replace("./?page=results");</script>';
        exit;
    }
} else {
    echo '<script>alert("Result ID is required"); location.replace("./?page=results");</script>';
    exit;
}
?>
<style>
    .post-user,
    .comment-user {
        width: 1.8em;
        height: 1.8em;
        object-fit: cover;
        object-position: center center;
    }
</style>
<div class="card card-outline card-navy rounded-0 shadow">
    <div class="card-header">
        <h4 class="card-title">Result Details</h4>
        <div class="card-tools">
            <a href="./?page=results/manage_result&id=<?= $result_id ?>" class="btn btn-sm btn-flat bg-gradient-primary btn-primary"><i class="fa fa-edit"></i> Edit Result</a>
            <button type="button" id="delete_result" class="btn btn-sm btn-flat bg-gradient-danger btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </div>
    </div>
    <div class="card-body">
        <div class="contrain-fluid">

            <div style="line-height:1em" class="mb-3">
                <h2 class="font-weight-bold mb-0 border-bottom"><?= $title ?></h2>
                <div class="py-1">
                    <div class="mb-2 text-right">
                        <?php if ($status == 'Reviewed'): ?>
                            <small class="badge badge-success border text-dark rounded-pill px-3"><i class="fa fa-circle text-primary"></i> Reviewed</small>
                        <?php else: ?>
                            <small class="badge badge-info border text-lg-center rounded-pill px-3"><i class="fa fa-circle text-secondary"></i> Pending</small>
                        <?php endif; ?>
                        <h3 class=" text-lg-center badge-danger border text-dark rounded-pill  w-25 font-weight-bold mb-0 border-bottom "><i class="far fa-circle"></i> <?= $diagnosis ?></h3>

                    </div>
                    <small class="badge badge-light border text-dark rounded-pill px-3 me-2"><i class="far fa-circle"></i> <?= $image_type ?></small>
                    <small class="badge badge-primary border text-light rounded-pill px-3 me-2"><i class="far fa-circle"></i> <?= $confidence ?></small>
                    <br>
                    <!-- <span class="me-2"><img src="<?= validate_image($image_path) ?>" alt="" class="img-thumbnail border border-dark post-user rounded-circle p-0"></span> -->
                    <span class="me-2"><img src="<?= validate_image($image_path) ?>" alt="" class="img-thumbnail border border-dark rounded-circle p-0"></span>
                    <br>

                </div>
            </div>
            <div>
                <!-- <?= $diagnosis ?> -->
                <h3 class=" badge-warning border text-dark rounded-pill  w-25 font-weight-bold mb-0 border-bottom "><i class="far fa-circle"></i> <?= $diagnosis ?></h3>
            </div>
            <hr class="mx-n3">
            <h4 class="font-weight-bolder">Reviews:</h4>
            <div class="list-group comment-list mb-3 rounded-0">
                <?php
                // Fixed query to get reviews for this specific result
                $reviews = $conn->query("SELECT dr.*, u.username, u.avatar 
                            FROM doctorreviews dr
                            JOIN users u ON dr.doctor_id = u.user_id
                            WHERE dr.result_id = '{$result_id}'
                            AND dr.delete_flag = 0
                            ORDER BY dr.reviewed_at DESC");

                if ($reviews->num_rows > 0) {
                    while ($row = $reviews->fetch_assoc()):
                ?>
                        <div class="list-group-item list-group-item-action mb-1 border-top">
                            <div class="d-flex align-items-center w-100">
                                <div class="col-auto">
                                    <img src="<?= validate_image($row['avatar']) ?>" alt="" class="comment-user rounded-circle img-thumbnail p-0 border">
                                </div>
                                <div class="col-auto flex-shrink-1 flex-grow-1">
                                    <div style="line-height:1em">
                                        <div class="font-weight-bolder"><?= $row['username'] ?></div>
                                        <div><small class="text-muted"><i><?= date("Y-m-d h:i a", strtotime($row['reviewed_at'])) ?></i></small></div>
                                    </div>
                                </div>
                                <?php if ($_settings->userdata('user_id') == $row['doctor_id']): ?>
                                    <a href="javascript:void(0)" class="text-danger text-decoration-none delete-review" data-id='<?= $row['review_id'] ?>'><i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <div><?= htmlspecialchars($row['review_comments']) ?></div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="is_approved_<?= $row['review_id'] ?>" name="is_approved" disabled <?= $row['is_approved'] ? 'checked' : '' ?>>
                                    <label for="is_approved_<?= $row['review_id'] ?>"></label>
                                </div>
                                <label for="is_approved_<?= $row['review_id'] ?>" class="control-label">Approved</label>
                            </div>
                        </div>
                <?php endwhile;
                } else {
                    echo '<div class="list-group-item text-center text-muted">No reviews yet</div>';
                }
                ?>
            </div>
            <?php if ($_settings->userdata('role') == 'doctor'): ?>
                <div class="card rounded-0 shadow">
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="" id="comment-form">
                                <input type="hidden" name="result_id" value="<?= $result_id ?>">
                                <input type="hidden" name="doctor_id" value="<?= $_settings->userdata('user_id') ?>">
                                <textarea class="form-control form-control-sm rouned-0" name="review_comments" id="review_comments" rows="4" placeholder="Write your review here" required></textarea>
                                <div class="form-group mt-2">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="is_approved" name="is_approved" value="1">
                                        <label for="is_approved"></label>
                                    </div>
                                    <label for="is_approved" class="control-label">Approve this diagnosis</label>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer py-1 text-right">
                        <button class="btn btn-primary btn-flat btn-sm bg-gradient-primary" form="comment-form"><i class="fa fa-save"></i> Submit Review</button>
                        <button class="btn btn-light btn-flat btn-sm bg-gradient-light border" type="reset" form="comment-form">Cancel</button>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.delete-review').click(function() {
            _conf("Are your sure to delete this review?", "delete_review", [$(this).attr('data-id')])
        })
        $('#delete_result').click(function() {
            _conf("Are your sure to delete this result?", "delete_result", ['<?= isset($id) ? $id : '' ?>'])
        })
        $('#comment').summernote({
            height: "15em",
            placeholder: "Write your review here",
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['view', ['codeview']]
            ]
        })
        $('#comment-form').submit(function(e) {
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
                url: _base_url_ + "classes/Master.php?f=save_review",
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
                        location.reload()
                    } else if (!!resp.msg) {
                        el.html(resp.msg)
                        el.show('slow')
                        _this.prepend(el)
                        $('html, body').scrollTop(_this.offset().top + 15)
                    } else {
                        alert('An error occurred')
                        console.log(resp)
                    }
                    end_loader()
                }
            })
        })
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
                    location.replace('./?page=results');
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }

    function delete_review($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_review",
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