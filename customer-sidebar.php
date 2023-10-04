<div class="user-sidebar">
    <ul>
    <a href="dashboard.php"><button class="btn btn-danger"><?php echo LANG_VALUE_89; ?></button></a>
    <a href="customer-profile-update.php"><button class="btn btn-danger"><?php echo LANG_VALUE_117; ?></button></a>
        <a href="customer-billing-shipping-update.php"><button class="btn btn-danger"><?php echo LANG_VALUE_88; ?></button></a>
        <a href="customer-password-update.php"><button class="btn btn-danger"><?php echo LANG_VALUE_99; ?></button></a>
        <a href="customer-order.php"><button class="btn btn-danger"><?php echo LANG_VALUE_24; ?></button></a>
        <?php
            if(isset($_SESSION['customer']) && !isset($_SESSION['access_token'])){
                ?>
                <a href="logout.php"><button class="btn btn-danger"><?php echo LANG_VALUE_14; ?></button></a>
                <?php
            }
            else if(!isset($_SESSION['customer']) && isset($_SESSION['access_token'])){
                ?>
                <a href="googleLogout.php"><button class="btn btn-danger">Logout from Google</button></a>
                <?php
            }
            else if(isset($_SESSION['customer']) && isset($_SESSION['access_token'])){
                ?>
                <a href="googleLogout.php"><button class="btn btn-danger">Logout from Google</button></a>
                <?php
            }
        ?>
        
    </ul>
</div>