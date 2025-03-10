<?php include base_path('views/client/partials/head.php') ?>
<?php include base_path('views/client/partials/nav.php') ?>


<section class="hero" id="hero">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-background">
                    <img src="asset/images/slider6.png" alt="" style="object-fit: cover;">
                    <div class="carousel-container">
                        <div class="carousel-content-container">
                            <h2>Profile</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>

    <div class="profile-container">
        <!-- Left Panel -->
        <div class="profile-sidebar">
            <div class="profile-avatar">
                <img src="<?= $_SESSION['user']['image_path'] ?>" alt="Profile Photo" />
            </div>
            <h2 class="profile-name"><?= $_SESSION['user']['name'] ?></h2>
            <p class="profile-username"><?= $_SESSION['user']['email'] ?></p>
            <button class="btn upload-btn">Upload New Photo</button>

            <p class="member-since">Member Since: 29 September 2019</p>
            <form action="/logout" method="POST" style="text-align: start;">
                <button
                    type="submit"
                    class="btn logout-btn ">
                    logout
                </button>
            </form>
        </div>


        <div class="profile-content">
            <h2>Edit Profile</h2>

            <ul class="profile-tabs">
                <li class="active">User Info</li>
            </ul>
            <div class="profile-form-section">
                <form>
                    <div class="profile-form-row">
                        <div class="profile-form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" placeholder="James" />
                        </div>
                        <div class="profile-form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" placeholder="demo@mail.com" />
                        </div>
                    </div>
                    <div class="profile-form-row" style="width: 100%;">
                        <div class="profile-form-group">
                            <label for="oldpassword">Old Password</label>
                            <input type="password" id="oldpassword" placeholder="********" />
                        </div>
                    </div>
                    <div class="profile-form-row">
                        <div class="profile-form-group">
                            <label for="newpassword">New Password</label>
                            <input type="password" id="newpassword" placeholder="********" />
                        </div>
                        <div class="profile-form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input
                                type="password"
                                id="confirmPassword"
                                placeholder="********" />
                        </div>
                    </div>

                    <div class="profile-form-row">

                        <form action="" method="POST" style="text-align: end;">
                            <button
                                type="submit"
                                class="btn update-btn ">
                                update
                            </button>
                        </form>

                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>
</section>
<?php include base_path('views/client/partials/newsletter.php') ?>
<?php require base_path('views/client/partials/footer.php') ?>
