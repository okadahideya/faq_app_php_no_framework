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
    $results = $mysqli->query('select * from users');

    // htmlspecialchars省略
    function h($value) {
        return htmlspecialchars($value, ENT_QUOTES);
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ユーザー一覧</title>
  
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
            <h3 class="card-title">ユーザー一覧</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-block bg-gradient-success">新規作成</button>
            </div>
          </div>
            <div class="card-body">
              <table class="table table-bordered">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>名前</th>
                          <th>email</th>
                          <th>権限</th>
                          <th >設定</th>
                        </tr>
                    </thead>
                    <tbody>
              <!-- アカウント一覧 取得 -->
              <form action="edit.php" method="POST">
<?php while ($result = $results->fetch_assoc()): ?>
                <tr>
                  <td class="text-right"><?php echo $result['id']; ?></td>
                  <td><?php echo h($result['name']); ?></td>
                  <td><?php echo h($result['email']); ?></td>
<?php     if($result['role'] == 1): ?>
                        <td>管理者</td>
<?php     else: ?>
                        <td>ユーザー</td>
<?php     endif; ?>
                  <td class="d-flex justify-content-around">
                  <a class="text-primary" href="edit.php?id=<?php echo $result['id'] ?>">編集</a>
                    <a class="text-danger" onclick="return confirm('<?php echo $result['name']; ?>を本当に削除しますか?')" href="delete.php?id=<?php echo $result['id']; ?>">削除</a>
                  </td>
                </tr>
<?php endwhile; ?>
                    </form>
                    </tbody>
                </table>
            </div>
          </div>
      </div>
    </div>
  </div>
  <!-- /.コンテンツ -->

  <!-- Main Footer -->
  <footer class="main-footer bg-white">
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

</div><!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
  <script>
    $('button').click(function() {
      location.href = 'create.php';
    })    
  </script>

<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script  type = "module"  src = "https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script> 
<script  nomodule  src = "https:// unpkg .com / ionicons @ 5.5.2 / dist / ionicons / ionicons.js"></script> 
</body>
</html>
