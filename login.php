<?php require_once('header.php'); ?>
<!-- fetching row banner login -->
<?php

if (isset($_SESSION['access_token'])) {
    // Access token exists, check if it's still valid
    if (isAccessTokenValid($_SESSION['access_token'])) {
        header("location: http://localhost:8080/EcommercePHP/index.php");
    } else {
        // Access token is expired, you may need to refresh it if you have a refresh token.
        // If not, consider prompting the user to log in again.
    }
}

function isAccessTokenValid($accessToken) {

    $ch = curl_init();
    
    $tokenInfoUrl = "https://www.googleapis.com/oauth2/v3/tokeninfo?access_token=" . urlencode($accessToken);
    curl_setopt($ch, CURLOPT_URL, $tokenInfoUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        return false;
    }
    
    $tokenInfo = json_decode($response, true);
    
    if (isset($tokenInfo['exp']) && $tokenInfo['exp'] >= time()) {
        return true;
    } else {
        return false;
    }
    
    curl_close($ch);
}

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_login = $row['banner_login'];
}
?>

<!-- login form -->
<?php
if(isset($_POST['form1'])) {
        
    if(empty($_POST['cust_email']) || empty($_POST['cust_password'])) {
        $error_message = LANG_VALUE_132.'<br>';
    } else {
        
        $cust_email = strip_tags($_POST['cust_email']);
        $cust_password = strip_tags($_POST['cust_password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
        $statement->execute(array($cust_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $cust_status = $row['cust_status'];
            $row_password = $row['cust_password'];
        }

        if($total==0) {
            $error_message .= LANG_VALUE_133.'<br>';
        } else {
            //using MD5 form
            if( $row_password != md5($cust_password) ) {
                $error_message .= LANG_VALUE_139.'<br>';
            } else {
                if($cust_status == 0) {
                    $error_message .= LANG_VALUE_148.'<br>';
                } else {
                    $_SESSION['customer'] = $row;
                    $sessionLog = $_SESSION['customer'];
                    echo "<script>console.log($sessionLog);</script>";
                    // header("location: ".BASE_URL."dashboard.php");
                }
            }
            
        }
    }
}
?>

<?php
if(isset($_GET['code'])){

    $token = $gclient->fetchAccessTokenWithAuthCode($_GET['code']);
 
    if(!isset($token['error'])){
        $gclient->setAccessToken($token['access_token']);
 
        $_SESSION['access_token'] = $token['access_token'];
 
        $gservice = new Google_Service_Oauth2($gclient);

         $udata = $gservice->userinfo->get();

        foreach($udata as $k => $v){
            $_SESSION['login_'.$k] = $v;
        }
        $_SESSION['ucode'] = $_GET['code'];
        $_SESSION['authenticated'] = true;

            header("location: http://localhost:8080/EcommercePHP/index.php");
            exit;
        
    }
    else{
        echo "<script>console.log('Token errorrr');</script>";
    }
}
else if(isset($_GET['error'])){
        $error = $_GET['error'];
        $errorDescription = $_GET['error_description'];
        echo "<script>console.log('OAuth Error: $error - $errorDescription');</script>";
    }
else {
    echo "<script>console.log('No code parameter in the URL.');</script>";
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_login; ?>);">
    <div class="inner">
        <h1><?php echo LANG_VALUE_10; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">

                    
                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>                  
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <?php
                                if($error_message != '') {
                                    echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                                }
                                if($success_message != '') {
                                    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$success_message."</div>";
                                }
                                ?>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_94; ?> *</label>
                                    <input type="email" class="form-control" name="cust_email">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_96; ?> *</label>
                                    <input type="password" class="form-control" name="cust_password">
                                </div>
                                <div class="form-group">
                                    <label for=""></label>
                                    <a href="<?= $gclient->createAuthUrl() ?>" class="btn btn btn-primary btn-flat rounded-0">Login with Google</a>
                                    <input type="submit" class="btn btn-success" value="<?php echo LANG_VALUE_4; ?>" name="form1">
                                    </a>
                                </div>
                                <a href="forget-password.php" style="color:#e4144d;"><?php echo LANG_VALUE_97; ?>?</a>
                            </div>
                        </div>                        
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>