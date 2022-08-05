<?php
    session_start();

    // login.php ログインされているか検証 & 管理者チェック
    if (isset($_SESSION['id']) && isset($_SESSION['name']) && $_SESSION['role'] == 1){
        $name = $_SESSION['name'];
    } else {
        header('Location: login.php');
        exit();
    }

    // データベース接続
    require_once('../db/dbconnect.php');

    // 初期化
    $form = [
        'name'     => '',
        'email'    => '',
        'password' => '',
        'role'     => ''
    ];

    $error = [];

     // 日本の日付
     date_default_timezone_set('Asia/Tokyo');

    // フォーム送信
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($form['name'] === '') {
                $error['name'] = 'blank';
        }
    
        $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            if ($form['email'] === '') {
                $error['email'] = 'blank';
        } 

        // 大文字 小文字 半角 数字 記号 8桁以上
        $form['password'] = filter_input(INPUT_POST, 'password');
            if ($form['password'] === '') {
                $error['password'] = 'blank';
            } else if (preg_match('/\A(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[#%$&@\-,])[a-zA-Z\d#%$&@\-,]{8,}\z/', $form['password']) === 0) {
                $error['password'] = 'passwordNotPregMatch';
                $form['password'] = '';
            }

        // パスワード暗号化
        $passwordHash = password_hash($form['password'], PASSWORD_DEFAULT);

        // 三項演算子 条件分岐
        // $form['role'] = isset($_POST['role']) ? false : $_POST['role'][0];
        // if (!$form['role']) {
        //     $error['role'] = 'blank';
        // } else if ($form['form'] === '管理者') {
        //     $form['role'] = 1;
        // } else {
        //     $form['role'] = 2;
        // }

        // 三項演算子
        // $ifForm = (isset($form['role']) === false ? $error['role'] = 'blank' : ($_POST['role'][0] === '管理者' ? $form['role'] = 1 : $form['role'] = 2));

        // 条件分岐
        $form['role'] = isset($_POST['role']);
            if ($form['role'] === false ) {
                $error['role'] = 'blank';
            } else {
                if ($_POST['role'][0] === '管理者') {
                    $form['role'] = 1;
                } else {
                    $form['role'] = 2;
                }
            }

        // 日付
        $create_at = date('Y-m-d H:i:s');

        // データが空の場合エラー文
        $count = 0;
        foreach ( $form as $key => $val) {
            if ( $val === '') {
                $count ++;
            } else if ( $val === false ) {
                $count ++;
            }
        }    

        // 空が１つもなければ保存
        if ($count == 0) {
            // データ追加
            $results = $mysqli->prepare('insert into users (name, email, password, role, create_at) values (?,?,?,?,?)');
            if (!$results) {
                die($mysqli->error);
            }
            $results->bind_param('sssis', $form['name'], $form['email'], $passwordHash, $form['role'], $create_at);
    
            // SQL実行
            $success = $results->execute();
            if (!$success){
                die($mysqli->error);
            }
            
            // 登録後ページ遷移
            if (empty($error)) {
                header('Location: list.php');
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ユーザー新規作成</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- ヘッダーナビ -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg-info">
    <!-- ヘッダーナビ　左 -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-white"></i></a>
      </li>
    </ul>
    <!-- ヘッダーナビ　右 -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas text-white">管理者：<?php echo $name; ?></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.ヘッダーナビ -->

  <!-- メイン サイドバー -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- ロゴ -->
    <a href="index3.html" class="brand-link bg-info">
      <span class="brand-text ml-5">FAQ管理システム</span>
    </a>

    <!-- サイドバー -->
    <div class="sidebar p-0">
      <div class="bg-black">
        <div class="user-panel d-flex pl-2">
        <div class="info">
        <a href="#" class="d-block">MAIN NAVIGATION</a>
        </div>
        </div>
      </div>
        <!-- サイドバー ナビ -->
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="list.php" class="nav-link w-100">
                <ion-icon name="people-outline"></ion-icon>
                <p class="ml-2">
                    ユーザー一覧
                </p>
            </a>
          </li>
        </ul>
        <!-- /.サイドバー ナビ  -->
    </div>
    <!-- /.サイドバー -->
  </aside>
  <!-- /.メイン サイドバー -->

  <!-- コンテンツ -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h1>ユーザー<small class="lead text-muted ml-1">user</small> </h1>
        <div class="card mt-2">
          <div class="card-header border-0">
            <h3 class="card-title">ユーザー登録</h3>
          </div>
          <div class="card-body p-0">
            <!-- フォーム -->
            <form action="" class="form-horizontal" method="POST" >
                <div class="card-body p-0 mt-3">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label text-right">名前</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control w-75 p-3" name="name" id="inputEmail3" placeholder="名前" value="<?php echo $form['name']; ?>">
<?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
                              <p class="text-danger">名前を入力してください</p>
<?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label text-right">email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control w-75 p-3" name="email" id="inputEmail3" placeholder="sample@gmail.com" value="<?php echo $form['email']; ?>">
<?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
                              <p class="text-danger">メールアドレスを入力してください</p>
<?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label text-right">password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control w-75 p-3" name="password" id="inputEmail3" placeholder="password">
                            <p class="">８文字以上&nbsp;大文字小文字の半角英数字<br>いずれかの記号&nbsp;#%$&@-,&nbsp;を含むパスワードにしてください</p>
<?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
                              <p class="text-danger">パスワードを入力してください</p>
<?php endif; ?>
<?php if (isset($error['password']) && $error['password'] === 'passwordNotPregMatch'): ?>
                              <p class="text-danger">８文字以上で半角大文字小文字の英数字<br>いずれかの記号&nbsp;#%$@&-,&nbsp;を含むパスワードにしてください</p>
<?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label for="inputEmail3" class="col-sm-2 col-form-label text-right">権限</label>
                        <div class="col-sm-10 mt-2">
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10 m-0">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role[]" value="管理者"<?php if ($form['role'] == 1){ echo "checked";} ?>>
                                        <label class="form-check-label" for="inlineRadio1">管理者</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role[]" value="ユーザー"<?php if ($form['role'] == 2){ echo "checked";} ?>>
                                        <label class="form-check-label" for="inlineRadio2">ユーザー</label>
                                    </div>
<?php if (isset($error['role']) && $error['role'] === 'blank'): ?>
                                        <p class="text-danger">権限を選択してください</p>
<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-white p-0 mt-3 mb-3 pt-3 border-top">
                  <button type="submit" class="btn btn-primary bg-success border-0">登録</button>
                </div>             
            </form>
          </div>
        </div>
        </div>
    </div>
  </div>
  <!-- /.コンテンツ -->

  <!-- Main Footer -->
  <footer class="main-footer bg-white">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

</div>
<!-- ./wrapper -->

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
