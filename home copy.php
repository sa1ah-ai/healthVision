<style>
    .carousel-item>img {
        object-fit: fill !important;
    }

    #carouselExampleControls .carousel-inner {
        height: 280px !important;
    }

    #search-field .form-control.rounded-pill {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right: none !important;
    }

    #search-field .form-control:focus {
        box-shadow: none !important;
    }

    #search-field .form-control:focus+.input-group-append .input-group-text {
        border-color: #86b7fe !important;
    }

    #search-field .input-group-text.rounded-pill {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-right: left !important;
    }

    .post-item {
        transition: all .2s ease-in-out;
        border: 1px solid #e0e0e0;
        /* Border around the card */
        border-radius: 5px;
        /* Rounded corners */
        padding: 15px;
        /* Padding inside the card */
        margin-bottom: 20px;
        /* Space between cards */
        background-color: #fff;
        /* Background color for cards */
    }

    .post-item:hover {
        transform: scale(1.02);
    }

    .divider {
        height: 2px;
        background-color: #e0e0e0;
        /* Color of the divider line */
        margin: 20px 0;
        /* Space around the divider */
    }
</style>

<section class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="carouselExampleControls" class="carousel slide bg-dark" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $upload_path = "uploads/banner";
                        if (is_dir(base_app . $upload_path)):
                            $file = scandir(base_app . $upload_path);
                            $_i = 0;
                            foreach ($file as $img):
                                if (in_array($img, ['.', '..'])) continue;
                                $_i++;
                        ?>
                                <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : ''; ?>">
                                    <img src="<?php echo validate_image($upload_path . '/' . $img); ?>" class="d-block w-100 h-100" alt="<?php echo $img; ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center my-4">
            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                <div class="input-group input-group-lg" id="search-field">
                    <input type="search" class="form-control form-control-lg rounded-pill" aria-label="Search Input" id="search" placeholder="Search here">
                    <div class="input-group-append">
                        <span class="input-group-text rounded-pill bg-transparent"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-1 gx-2 gy-2">

            <?php
            // $posts = $conn->query("SELECT p.*, c.name AS `category` FROM `post_list` p INNER JOIN category_list c ON p.category_id = c.id WHERE p.status = 1 AND p.`delete_flag` = 0 ORDER BY ABS(UNIX_TIMESTAMP(p.date_created)) DESC");
            // while ($row = $posts->fetch_assoc()):
            ?>
                <div class="col post-item">
                    <a href="./?p=posts/view_post&id=<?= $row['id'] ?>" class="card rounded-0 shadow text-decoration-none text-reset">
                        <div class="card-body">
                            <div class="mb-2 text-right">
                                <small class="badge badge-light border text-dark rounded-pill px-3"><i class="far fa-circle"></i> <?= $row['category'] ?></small>
                                <small class="badge badge-light border text-dark rounded-pill px-3"><i class="fa fa-circle text-green"></i> Post</small>

                            </div>
                            <h3 class="card-title w-100 font-weight-bold"><?= $row['title'] ?></h3>
                            <div class="card-text truncate-3 text-muted text-sm"><?= strip_tags($row['content']) ?></div>
                            <div class="mb-2 text-right">
                                <small class="text-muted"><i><?= date("Y-m-d h:i A", strtotime($row['date_created'])) ?></i></small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php #endwhile; ?>

            <hr>
            <hr>
            <hr>

            <?php
            // $events = $conn->query("SELECT eve.event_id, eve.title, eve.description, eve.event_date, CONCAT(user.firstname, ' ', user.lastname) AS created_by, col.college_name FROM events eve LEFT JOIN colleges col ON eve.college_id = col.college_id LEFT JOIN users user ON eve.created_by = user.id WHERE eve.delete_flag = 0 ORDER BY eve.event_date DESC");
            // while ($row = $events->fetch_assoc()):
            ?>
                <div class="col post-item">
                    <a href="./?p=events/view_event&id=<?= $row['event_id'] ?>" class="card rounded-0 shadow text-decoration-none text-reset">
                        <div class="card-body bg-dark">
                            <div class="mb-2 text-right">
                                <small class="badge badge-light border text-dark rounded-pill px-3"><i class="far fa-circle"></i> <?= htmlspecialchars($row['college_name']) ?></small>
                                <small class="badge badge-light border text-dark rounded-pill px-3"><i class="fa fa-circle text-danger"></i> Event</small>

                            </div>
                            <h3 class="card-title w-100 font-weight-bold"><?= htmlspecialchars($row['title']) ?></h3>
                            <div class="card-text truncate-3 text-cyan text-sm"><?= strip_tags($row['description']) ?></div>
                            <div class="mb-2 text-right">
                                <small class="text-danger"><i><?= date("Y-m-d h:i A", strtotime($row['event_date'])) ?></i></small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php #endwhile; ?>

            <hr>
            <hr>
            <hr>

            <?php
            // $sources = $conn->query("SELECT s.*, c.name as `category` FROM `sources` s INNER JOIN category_list c ON s.specialization_id = c.id WHERE s.delete_flag = 0 ORDER BY s.date_added DESC");
            // while ($row = $sources->fetch_assoc()):
            ?>
                <div class="col post-item">
                    <a href="./?p=sources/view_source&id=<?= $row['source_id'] ?>" class="card rounded-0 shadow text-decoration-none text-reset">
                        <div class="card-body bg-gradient-indigo">
                            <div class="mb-2 text-right">
                                <small class="badge badge-light border text-dark rounded-pill px-3"><i class="far fa-circle"></i> <?= $row['category'] ?></small>
                                <?php if ($row['is_approved'] == 1): ?>
                                    <small class="badge badge-light border text-dark rounded-pill px-3"><i class="fa fa-circle text-primary"></i> Approved</small>
                                <?php else: ?>
                                    <small class="badge badge-light border text-dark rounded-pill px-3"><i class="fa fa-circle text-secondary"></i> Unapproved</small>
                                <?php endif; ?>
                            </div>
                            <h3 class="card-title w-100 font-weight-bold"><?= $row['title'] ?></h3>
                            <div class="card-text truncate-3 text-light text-sm"><?= strip_tags($row['url']) ?></div>
                            <div class="mb-2 text-right">
                                <small class=" text-light"><i><?= date("Y-m-d h:i A", strtotime($row['date_added'])) ?></i></small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php #endwhile; ?>
        </div> -->
    </div>
</section>

<script>
    $(function() {
        $('#search').on('input', function() {
            var _search = $(this).val().toLowerCase();
            $('.post-item').each(function() {
                var _text = $(this).text().toLowerCase();
                _text = _text.trim();
                if (_text.includes(_search) === false) {
                    $(this).toggle(false);
                } else {
                    $(this).toggle(true);
                }
            });
        });
    });
</script>