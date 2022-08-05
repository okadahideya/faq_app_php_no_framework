<?php
    
    session_start();
    
    // データベース接続
    require_once('../db/dbconnect.php');

    // 初期化
    $form = [
        'email' => ''
    ];

    $error = [];

    // フォーム送信
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $form['password']  = filter_input(INPUT_POST, 'password');
        if ($form['email']  === '') {
            $error['email'] = 'blank';
            $error['password'] = 'blank';
        } else if ($form['password']  === '') {
            $error['password'] = 'blank';
        } else {
            // SQL作成
            $db = $mysqli->prepare('select id, name, password, role from users where email=? limit 1');
            if (!$db) {
                die($mysqli->error);
            }
            
            // SQLに変数を格納
            $db->bind_param('s', $form['email']);
            
            // SQL実行
            $success = $db->execute();
            if (!$success) {
                die($mysqli->error);
            }
            
            // SQL実行結果の変数定義
            $db->bind_result($id, $name, $passwordHash, $role);
            
            // bind_result 変数へ格納
            $db->fetch();

            if ($passwordHash === NULL) {
                //ログイン失敗
                $error['login'] = 'loginFalse';
            } else if (password_verify($form['password'], $passwordHash)) {
                // 入力したパスワード&暗号化したパスワードの検証
                // ログイン成功
                // セッションID盗難対策
                session_regenerate_id();

                // セッション情報を格納
                $_SESSION['id'] = $id;
                $_SESSION['name'] = $name;
                $_SESSION['role'] = $role;
                header('Location: list.php');
                exit();
            } else {
                //ログイン失敗
                $error['login'] = 'loginFalse';
            }
            
        }
        
    }

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ログイン</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <style>
      #main-div {
          margin-top: 200px;
      }
  </style>
</head>
<body class="bg-light">  
<div class="d-flex justify-content-center" id="main-div">
    <div class="card card-info w-50">
        <div class="card-header">
            <h3 class="card-title">FAQ管理システム</h3>
        </div>
        <form action="" class="form-horizontal" method="POST">
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputEmail3" name="email" placeholder="Email" value="<?php echo  $form['email']; ?>">
<?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
                        <p class="text-danger">メールアドレスを入力してください</p>
<?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="Password">
<?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
                        <p class="text-danger">パスワードを入力してください</p>
<?php endif; ?>
                    </div>
                </div>
<?php if (isset($error['login']) && $error['login'] === 'loginFalse'): ?>
                        <p class="text-danger">ログインに失敗しました<br>メールアドレスもしくは、パスワードが違います</p>
<?php endif; ?>                
                <div class="card-footer d-flex justify-content-center bg-white">
                    <button type="submit" class="btn btn-info">Sign in</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script  type = "module"  src = "https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script> 
<script  nomodule  src = "https:// unpkg .com / ionicons @ 5.5.2 / dist / ionicons / ionicons.js"></script> 
</body>
</html>