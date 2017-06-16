<header class="header">
    <a href="<?php echo SITEURL; ?>" class="logo">
        Control Panel
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li><a target="_blank" href="<?php echo MAINSITEURL; ?>"><span class="fa fa-external-link"></span> Go to Website</a></li>
                <li><a href="<?php echo SITEURL; ?>?content=change_password"><span class="fa fa-key"></span> Change Password</a></li>
                <li><a href="<?php echo SITEURL; ?>?content=logout"><span class="fa fa-power-off"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
</header>

<div class="wrapper row-offcanvas row-offcanvas-left">
    <aside class="left-side sidebar-offcanvas">
        <section class="sidebar">
            <?php include "menu.php"; ?>
        </section>
    </aside>
    <aside class="right-side">
        <section class="content-header">
            <h1>
                <?php echo $web->heading_title; ?>
            </h1>
            <?php echo $web->breadcumbs->render(); ?>
        </section>

        <section class="content">
