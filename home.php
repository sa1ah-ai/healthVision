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
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #fff;
    }

    .post-item:hover {
        transform: scale(1.02);
    }

    .divider {
        height: 2px;
        background-color: #e0e0e0;
        margin: 20px 0;
    }

    /* Organization Section */
    .organization-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .organization-box {
        flex: 1;
        min-width: 300px;
        max-width: 400px;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }

    .organization-box:hover {
        transform: translateY(-5px);
    }

    .organization-box h3 {
        font-size: 1.8rem;
        color: #1e3c72;
        margin-bottom: 15px;
    }

    .organization-box p {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #333;
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

        <!-- Health Vision Organization Section -->
        <div class="organization-container">
            <div class="organization-box">
                <h3>Who We Are</h3>
                <p>A student-led initiative at Jazan University, committed to leveraging technology to facilitate medical services and improve patient care.</p>
            </div>
            <div class="organization-box">
                <h3>Our Vision</h3>
                <p>To revolutionize healthcare through digital innovation, making medical assistance accessible and efficient for everyone.</p>
            </div>
            <div class="organization-box">
                <h3>Our Goals</h3>
                <p>Enhancing accessibility, empowering patients, supporting healthcare professionals, integrating technology, and developing a charitable initiative.</p>
            </div>
        </div>

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