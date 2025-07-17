<style>
    #search-field .form-control.rounded-pill {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right: none !important
    }

    #search-field .form-control:focus {
        box-shadow: none !important;
    }

    #search-field .form-control:focus+.input-group-append .input-group-text {
        border-color: #86b7fe !important
    }

    #search-field .input-group-text.rounded-pill {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-right: left !important
    }

    .image-item {
        transition: all .2s ease-in-out;
    }

    .image-item:hover {
        transform: scale(1.02);
    }
</style>
<section class="py-3">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                <div class="input-group input-group-lg" id="search-field">
                    <input type="search" class="form-control form-control-lg  rounded-pill" aria-label="Search Image Input" id="search" placeholder="Search Image here">
                    <div class="input-group-append">
                        <span class="input-group-text rounded-pill bg-transparent"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-1 gx-2 gy-2">
            <?php
            $medicalImages = $conn->query("SELECT * FROM `medicalimages` where user_id = '{$_settings->userdata('user_id')}' and `delete_flag` = 0 order by abs(unix_timestamp(uploaded_at)) desc");
            while ($row = $medicalImages->fetch_assoc()):
            ?>
                <div class="col image-item">
                    <a href="./?p=myImages/view_image&id=<?= $row['image_id'] ?>" class="card rounded-0 shadow text-decoration-none text-reset">
                        <div class="card-body">
                            <div class="mb-2 text-right">
                                <small class="badge badge-light border text-dark rounded-pill px-3"><i class="far fa-circle"></i> <?= $row['image_type'] ?></small>
                                <?php if ($row['status'] == 1): ?>
                                    <small class="badge badge-light border text-dark rounded-pill px-3"><i class="fa fa-circle text-primary"></i> Checked</small>
                                <?php else: ?>
                                    <small class="badge badge-light border text-dark rounded-pill px-3"><i class="fa fa-circle text-secondary"></i> Unchecked</small>
                                <?php endif; ?>
                            </div>
                            <h3 class="card-title w-100 font-weight-bold"><?= $row['title'] ?></h3>


                            <!-- Display the uploaded image -->
                            <?php if (isset($row['image_path']) && !empty($row['image_path'])): ?>
                                <div class="img-box">
                                    <!-- <img src="<?php echo $row['image_path']; ?>" alt="image" style="max-width: 100%; height: auto;"> -->
                                    <img src="<?= validate_image3($row['image_path']) ?>" alt="image" style="max-width: 100%; height: auto;">
                                </div>
                            <?php else: ?>
                                <div class="mb-2 text-right">
                                    <small class="text-danger">Image not available</small>
                                </div>
                            <?php endif; ?>

                            <!-- <div class="card-text truncate-3 text-muted text-sm"><?= strip_tags($row['image_path']) ?></div> -->
                            <div class="mb-2 text-right">
                                <small class="text-muted"><i><?= date("Y-m-d h:i A", strtotime($row['uploaded_at'])) ?></i></small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
        <?php if ($medicalImages->num_rows <= 0): ?>
            <h4 class="text-muted text-center"><i>You don't have uploaded images yet</i></h4>
        <?php endif; ?>
    </div>
</section>
<script>
    $(function() {
        $('#search').on('input', function() {
            var _search = $(this).val().toLowerCase()
            $('.image-item').each(function() {
                var _text = $(this).text().toLowerCase()
                _text = _text.trim()
                if (_text.includes(_search) === false) {
                    $(this).toggle(false)
                } else {
                    $(this).toggle(true)
                }
            })
        })
    })
</script>