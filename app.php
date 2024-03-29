<?php
//パスの結合ヘルパ
function join_paths(array $paths)
{
  return implode(DIRECTORY_SEPARATOR, $paths);
}

define('APP_ROOT', dirname(__FILE__));
define('LIB_ROOT', join_paths([APP_ROOT, 'lib']));
define('MODELS_ROOT' join_paths([APP_ROOT, 'models']));

require_once join_paths([APP_ROOT, 'config', 'env.php']);
require_once join_paths([LIB_ROOT, 'functions.php']);

// DB
function db()
{
  static $con;

  if(!isset($con)){
    $db       = DB_DBNAME;
    $host     = DB_HOSTNAME;
    $username = DB_USERNAME;
    $password = DB_PASSWORD;

    try{
      $con = new PDO("mysql:dbname=$db;host=$host", $username,$password);
      $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'データベースに接続できません!アプリの設定を確認してください。';
      exit;
    }
  }

  return $con;
}

// Session
require_once join_paths([LIB_ROOT, 'Session.php']);

function session($namespace = 'app')
{
    static $sessions;

    if (!isset($sessions[$namespace])) {
        $sessions[$namespace] = new Session($namespace);
    }

    //$sessions[app]を返す セッションのインスタンスを生成し、セッション開始
    return $sessions[$namespace];
}


function csrf_field(Session $session){
  //$name=__csrf_token
  $name = $session->getRequestCsrfTokenKey();
  $token = $session->getCsrfToken();
  //tokenの中身をエスケープして入れ込む
  echo '<input type="hidden" name="'.$name.'" value="'.h($token).'">';
}
