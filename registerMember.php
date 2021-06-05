<?php
ini_set("log_errors", "on");
ini_set("error_log", "log/error.log");
ini_set("date.timezone", "Asia/Tokyo");

const REGEX_EMAIL = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
const REGEX_HALF_CHAR_ALPHA = '[a-zA-Z]';
const REGEX_HALF_CHAR_NUM = '[0-9]';
const REGEX_FULL_CHAR = '/^[^ -~｡-ﾟ\x00-\x1f\t]+$/u';
const REGEX_FULL_CHAR_KANJI = '[一-龠]';
const REGEX_FULL_CHAR_ALPHA = '[ａ-ｚＡ-Ｚ]';
const REGEX_FULL_CHAR_NUM = '[０-９]';
const REGEX_FULL_CHAR_H_KANA = '[ぁ-ん]';
const REGEX_FULL_CHAR_K_KANA = '[ァ-ヴー]';
const REGEX_PHONE_NUMBER = '/^0\d{1,4}\d{1,4}\d{4}$/';
const REGEX_ZIPCODE = '/^\d{7}$/';
const REGEX_BLANK_CHAR = '/^[\s　]+$/';

const ERR_MSG_REQUIRED = '必須項目です';
const ERR_MSG_EMAIL_FORMAT = 'メールアドレスの形式で入力してください';
const ERR_MSG_EMAIL_NOT_EQUAL = 'メールアドレスが一致しません';
const ERR_MSG_PASSWORD_LEN = 'パスワードは8文字以上で設定してください';
const ERR_MSG_PASSWORD_NOT_EQUAL = 'パスワードが一致しません';
const ERR_MSG_HALF_CHAR_ALPHA_NUM_FORMAT = '半角英数字で入力してください';
const ERR_MSG_DATE_FORMAT = 'YYYY/MM/DDの形式で入力してください';
const ERR_MSG_INVALID_DATE_FORMAT = '存在しない日付です';
const ERR_MSG_PHONE_NUMBER_FORMAT = '電話番号の形式で入力してください';
const ERR_MSG_ZIPCODE_FORMAT = '郵便番号の形式で入力してください';
const ERR_MSG_FULL_CHAR_KANJI_ALPHA_KANA_FORMAT = '全角（漢字・英字・かな・カナ）で入力してください';
const ERR_MSG_FULL_CHAR_KANA_FORMAT = '全角カナで入力してください';
const ERR_MSG_FULL_CHAR_FORMAT = '全角で入力してください';

$errMsgArray = array();

if (!empty($_POST)) {
  $email = $_POST['email'];
  $email_confirm = $_POST['email_confirm'];
  $password = $_POST['password'];
  $password_confirm = $_POST['password_confirm'];
  $last_name = $_POST['last_name'];
  $first_name = $_POST['first_name'];
  $last_name_kana = $_POST['last_name_kana'];
  $first_name_kana = $_POST['first_name_kana'];
  $birthday = $_POST['birthday'];
  $phone_number = $_POST['phone_number'];
  $zipcode = $_POST['zipcode'];
  $address1 = $_POST['address1'];
  $address2 = $_POST['address2'];
  $address3 = $_POST['address3'];
  $address4 = $_POST['address4'];

  // メール
  if (isBlank($email)) {
    global $errMsgArray;
    $errMsgArray['email'] = ERR_MSG_REQUIRED;
  } else if (!validEmail($email)) {
    global $errMsgArray;
    $errMsgArray['email'] = ERR_MSG_REQUIRED;
  }

  // メール（再入力）
  if (isBlank($email_confirm)) {
    global $errMsgArray;
    $errMsgArray['email_confirm'] = ERR_MSG_REQUIRED;
  } else if ($email !== $email_confirm) {
    global $errMsgArray;
    $errMsgArray['email_confirm'] = ERR_MSG_EMAIL_NOT_EQUAL;
  }

  // パスワード
  if (isBlank($password)) {
    global $errMsgArray;
    $errMsgArray['password'] = ERR_MSG_REQUIRED;
  } else if (!validHalfCharAlphaNum($password)) {
    global $errMsgArray;
    $errMsgArray['password'] = ERR_MSG_HALF_CHAR_ALPHA_NUM_FORMAT;
  } else if (strlen($password) < 8) {
    global $errMsgArray;
    $errMsgArray['password'] = ERR_MSG_PASSWORD_LEN;
  }

  // パスワード（再入力）
  if (isBlank($password_confirm)) {
    global $errMsgArray;
    $errMsgArray['password_confirm'] = ERR_MSG_REQUIRED;
  } elseif ($password !== $password_confirm) {
    global $errMsgArray;
    $errMsgArray['password_confirm'] = ERR_MSG_PASSWORD_NOT_EQUAL;
  }

  // 姓(漢字)
  if (isBlank($last_name)) {
    global $errMsgArray;
    $errMsgArray['last_name'] = ERR_MSG_REQUIRED;
  } else if (!validFullCharNameKanji($last_name)) {
    global $errMsgArray;
    $errMsgArray['last_name'] = ERR_MSG_FULL_CHAR_KANJI_ALPHA_KANA_FORMAT;
    error_log($errMsgArray['last_name']);
  }

  // 名(漢字)
  if (isBlank($first_name)) {
    global $errMsgArray;
    $errMsgArray['first_name'] = ERR_MSG_REQUIRED;
  } else if (!validFullCharNameKanji($first_name)) {
    global $errMsgArray;
    $errMsgArray['first_name'] = ERR_MSG_FULL_CHAR_KANJI_ALPHA_KANA_FORMAT;
    error_log($errMsgArray['first_name']);
  }

  // 姓(カナ)
  if (isBlank($last_name_kana)) {
    global $errMsgArray;
    $errMsgArray['last_name_kana'] = ERR_MSG_REQUIRED;
  } else if (!validFullCharNameKana($last_name_kana)) {
    global $errMsgArray;
    $errMsgArray['last_name_kana'] = ERR_MSG_FULL_CHAR_KANA_FORMAT;
  }

  // 名(カナ)
  if (isBlank($first_name_kana)) {
    global $errMsgArray;
    $errMsgArray['first_name_kana'] = ERR_MSG_REQUIRED;
  } else if (!validFullCharNameKana($first_name_kana)) {
    global $errMsgArray;
    $errMsgArray['first_name_kana'] = ERR_MSG_FULL_CHAR_KANA_FORMAT;
  }

  // 生年月日
  if (isBlank($birthday)) {
    global $errMsgArray;
    $errMsgArray['birthday'] = ERR_MSG_REQUIRED;
  } else if (!validHalfNum(str_replace('/', '', $birthday)) || mb_strlen($birthday) < 8) {
    global $errMsgArray;
    $errMsgArray['birthday'] = ERR_MSG_DATE_FORMAT;
  } else {
    list($Y, $m, $d) = explode('/', $birthday);
    if (!checkdate($m, $d, $Y)) {
      global $errMsgArray;
      $errMsgArray['birthday'] = ERR_MSG_INVALID_DATE_FORMAT;
    }
  }

  // 電話番号
  if (isBlank($phone_number)) {
    global $errMsgArray;
    $errMsgArray['phone_number'] = ERR_MSG_REQUIRED;
  } else if (!validPhoneNumber(str_replace('-', '', $phone_number))) {
    global $errMsgArray;
    $errMsgArray['phone_number'] = ERR_MSG_PHONE_NUMBER_FORMAT;
  }

  // 郵便番号
  if (isBlank($zipcode)) {
    global $errMsgArray;
    $errMsgArray['zipcode'] = ERR_MSG_REQUIRED;
  } else if (!validZipcode(str_replace('-', '', $zipcode))) {
    global $errMsgArray;
    $errMsgArray['zipcode'] = ERR_MSG_ZIPCODE_FORMAT;
  }

  // 都道府県
  if (isBlank($address1)) {
    global $errMsgArray;
    $errMsgArray['address1'] = ERR_MSG_REQUIRED;
  } else if (!validFullChar($address1)) {
    global $errMsgArray;
    $errMsgArray['address1'] = ERR_MSG_FULL_CHAR_FORMAT;
  }

  // 市区町村
  if (isBlank($address2)) {
    global $errMsgArray;
    $errMsgArray['address2'] = ERR_MSG_REQUIRED;
  } else if (!validFullChar($address2)) {
    global $errMsgArray;
    $errMsgArray['address2'] = ERR_MSG_FULL_CHAR_FORMAT;
  }

  // 町名番地
  if (isBlank($address3)) {
    global $errMsgArray;
    $errMsgArray['address3'] = ERR_MSG_REQUIRED;
  } else if (!validFullChar($address3)) {
    global $errMsgArray;
    $errMsgArray['address3'] = ERR_MSG_FULL_CHAR_FORMAT;
  }

  if (empty($errMsgArray)) {
      // 生年月日からスラッシュを除去
      $birthday = str_replace('/', '', $birthday);
      // 郵便番号からハイフンを除去
      $zipcode = str_replace('-', '', $zipcode);
    try {
      $db = getDb();
      $query = $db->prepare('INSERT INTO member(login_id, password, last_name, first_name, last_name_kana, first_name_kana, birthday, phone_number, zipcode, address1, address2, address3, address4, last_login_time, created, modified)
                             VALUES(:login_id, :password, :last_name, :first_name, :last_name_kana, :first_name_kana, :birthday, :phone_number, :zipcode, :address1, :address2, :address3, :address4, :last_login_time, :created, :modified)');
      $query->bindValue(':login_id', $email);
      $query->bindValue(':password', $password);
      $query->bindValue(':last_name', $last_name);
      $query->bindValue(':first_name', $first_name);
      $query->bindValue(':last_name_kana', $last_name_kana);
      $query->bindValue(':first_name_kana', $first_name_kana);
      $query->bindValue(':birthday', $birthday);
      $query->bindValue(':phone_number', $phone_number);
      $query->bindValue(':zipcode', $zipcode);
      $query->bindValue(':address1', $address1);
      $query->bindValue(':address2', $address2);
      $query->bindValue(':address3', $address3);
      $query->bindValue(':address4', $address4);
      $query->bindValue(':last_login_time', date('Y-m-d H:i:s'));
      $query->bindValue(':created', date('Y-m-d H:i:s'));
      $query->bindValue(':modified', date('Y-m-d H:i:s'));
      $query->execute();
      header('Location:menu.html');
    } catch (PDOException $pdoEx) {
      error_log($pdoEx->getMessage());
    }
  }
}

// 未入力チェック
function isBlank($param)
{
  if (is_null($param)) {
    return true;
  }
  // 文字列の場合は空文字・スペース・タブ・改行文字で構成されている場合も未入力とみなす
  if (is_string($param)) {
    return $param === '' || preg_match(REGEX_BLANK_CHAR, $param);
  }
}

// Emailの形式チェック
function validEmail($email)
{
  return preg_match(REGEX_EMAIL, $email);
}

// 半角数字チェック
function validHalfNum($strParam)
{
  return preg_match('/^('  . REGEX_HALF_CHAR_NUM . ')+$/', $strParam);
}

// 半角英数字チェック
function validHalfCharAlphaNum($strParam)
{
  return preg_match('/^('  . REGEX_HALF_CHAR_ALPHA . '|' . REGEX_HALF_CHAR_NUM . ')+$/', $strParam);
}

// 全角チェック
function validFullchar($strParam)
{
  return preg_match(REGEX_FULL_CHAR, $strParam);
}

// 全角漢字氏名チェック（全角の漢字・英字・かな・カナから成る文字列であればTrueを返す）
function validFullCharNameKanji($strParam)
{
  return preg_match('/^(' . REGEX_FULL_CHAR_KANJI . '|' . REGEX_FULL_CHAR_ALPHA . '|' . REGEX_FULL_CHAR_H_KANA . '|' . REGEX_FULL_CHAR_K_KANA . ')+$/u', $strParam);
}

// 全角カナ氏名チェック（全角カナから成る文字列であればTrueを返す）
function validFullCharNameKana($strParam)
{
  return preg_match('/^(' . REGEX_FULL_CHAR_K_KANA . ')+$/u', $strParam);
}

// 電話番号チェック
function validPhoneNumber($strParam)
{
  return preg_match(REGEX_PHONE_NUMBER, $strParam);
}

// 郵便番号番号チェック
function validZipcode($strParam)
{
  return preg_match(REGEX_ZIPCODE, $strParam);
}

// DBオブジェクト取得
function getDb() {
  $dsn = 'mysql:dbname=reserve_stadium; host=localhost; charset=utf8';
  $usr = 'root';
  $passwd = 'root';
  $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
  );
  return new PDO($dsn, $usr, $passwd, $options);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>BB PLATFORM</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
</head>

<body>
  <header>新規会員登録</header>
  <form class="regist-form" method="POST" action="">

      <div>ログイン情報</div>
      <table class="form-table" border="1">
        <tr>
          <th><label class="required"></label>メールアドレス</th>
          <td class="col1-multiform">

            <div class="multiform-input-area">
              <input type="email" name="email" placeholder="taro.tanaka@example.com" class="<?php if (!empty($errMsgArray['email'])) echo 'err'; ?>">
              <?php if (!empty($errMsgArray['email'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['email'] ?></p>
              <?php endif; ?>
            </div>

            <div class="multiform-input-area">
              <input type="email" name="email_confirm" autocomplete="off" class="<?php if (!empty($errMsgArray['email_confirm'])) echo 'err'; ?>">（再入力）
              <?php if (!empty($errMsgArray['email_confirm'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['email_confirm'] ?></p>
              <?php endif; ?>
            </div>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>パスワード</th>
          <td class="col1-multiform">

            <div class="multiform-input-area">
              <input type="password" name="password" placeholder="8文字以上" class="<?php if (!empty($errMsgArray['password'])) echo 'err'; ?>">
              <?php if (!empty($errMsgArray['password'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['password'] ?></p>
              <?php endif; ?>
            </div>

            <div class="multiform-input-area">
              <input type="password" name="password_confirm" class="<?php if (!empty($errMsgArray['password_confirm'])) echo 'err'; ?>">（再入力）
              <?php if (!empty($errMsgArray['password_confirm'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['password_confirm'] ?></p>
              <?php endif; ?>
            </div>

          </td>
        </tr>

      </table>

      <div>会員様情報</div>
      <table class="form-table" border="1">
        <tr>
          <th><label class="required"></label>氏名</th>
          <td class="col2">
            <div class="col-part">
              <input type="text" name="last_name" placeholder="田中" class="<?php if (!empty($errMsgArray['last_name'])) echo 'err'; ?>">
              <?php if (!empty($errMsgArray['last_name'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['last_name'] ?></p>
              <?php endif; ?>
            </div>
            <div class="col-part">
              <input type="text" name="first_name" placeholder="太郎" class="<?php if (!empty($errMsgArray['first_name'])) echo 'err'; ?>">
              <?php if (!empty($errMsgArray['first_name'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['first_name'] ?></p>
              <?php endif; ?>
            </div>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>氏名（カナ）</th>
          <td class="col2">
            <div class="col-part">
              <input type="text" name="last_name_kana" placeholder="タナカ" class="<?php if (!empty($errMsgArray['last_name_kana'])) echo 'err'; ?>">
              <?php if (!empty($errMsgArray['last_name_kana'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['last_name_kana'] ?></p>
              <?php endif; ?>
            </div>
            <div class="col-part">
              <input type="text" name="first_name_kana" placeholder="タロウ" class="<?php if (!empty($errMsgArray['first_name_kana'])) echo 'err'; ?>">
              <?php if (!empty($errMsgArray['first_name_kana'])) : ?>
                <p class="err-msg"><?php echo $errMsgArray['first_name_kana'] ?></p>
              <?php endif; ?>
            </div>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>生年月日</th>
          <td class="col1">
            <input type="text" name="birthday" placeholder="2000/01/01" id="birthday" class="<?php if (!empty($errMsgArray['birthday'])) echo 'err'; ?>">
            <?php if (!empty($errMsgArray['birthday'])) : ?>
              <p class="err-msg"><?php echo $errMsgArray['birthday'] ?></p>
            <?php endif; ?>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>お電話番号</th>
          <td class="col1">
            <input type="tel" name="phone_number" placeholder="090XXXXXXXX" class="<?php if (!empty($errMsgArray['phone_number'])) echo 'err'; ?>">
            <?php if (!empty($errMsgArray['phone_number'])) : ?>
              <p class="err-msg"><?php echo $errMsgArray['phone_number'] ?></p>
            <?php endif; ?>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>郵便番号</th>
          <td class="col2">
            <input type="text" name="zipcode" placeholder="1234567" id="zipcode" class="<?php if (!empty($errMsgArray['zipcode'])) echo 'err'; ?>">
            <button id="search" type="button">住所検索</button>
            <?php if (!empty($errMsgArray['zipcode'])) : ?>
              <p class="err-msg"><?php echo $errMsgArray['zipcode'] ?></p>
            <?php endif; ?>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>都道府県</th>
          <td class="col1">
            <input type="text" name="address1" placeholder="東京都" id="address1" class="<?php if (!empty($errMsgArray['address1'])) echo 'err'; ?>">
            <?php if (!empty($errMsgArray['address1'])) : ?>
              <p class="err-msg"><?php echo $errMsgArray['address1'] ?></p>
            <?php endif; ?>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>市区町村</th>
          <td class="col1">
            <input type="text" name="address2" placeholder="港区" id="address2" class="<?php if (!empty($errMsgArray['address2'])) echo 'err'; ?>">
            <?php if (!empty($errMsgArray['address2'])) : ?>
              <p class="err-msg"><?php echo $errMsgArray['address2'] ?></p>
            <?php endif; ?>
          </td>
        </tr>

        <tr>
          <th><label class="required"></label>町名番地</th>
          <td class="col1">
            <input type="text" name="address3" placeholder="六本木１丁目２３−４５" id="address3" class="<?php if (!empty($errMsgArray['address3'])) echo 'err'; ?>">
            <?php if (!empty($errMsgArray['address3'])) : ?>
              <p class="err-msg"><?php echo $errMsgArray['address3'] ?></p>
            <?php endif; ?>
          </td>
        </tr>

        <tr>
          <th>ビル名・建物名</th>
          <td class="col1">
            <input type="text" name="address4" placeholder="○○タワー20階" id="address4" class="<?php if (!empty($errMsgArray['address4'])) echo 'err'; ?>">
            <?php if (!empty($errMsgArray['address4'])) : ?>
              <p class="err-msg"><?php echo $errMsgArray['address4'] ?></p>
            <?php endif; ?>
          </td>
        </tr>

      </table>

    <button type="button" id="clear">クリア</button>
    <button type="submit">登録</button>

  </form>

  <script src="js/jquery-3.6.0.min.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  <script src="js/datepicker-ja.js"></script>
  <script src="js/fetch-jsonp.min.js"></script>
  <script>
    $(function() {
      $('#birthday').datepicker({
        yearRange: '-100:+0',
        changeMonth: true,
        changeYear: true
      });
      $("#birthday").datepicker('setDate', (new Date().getFullYear() - 10).toString().concat('/01/01'));
      $('#clear').on('click', function() {
        $(':input, form').val('');
        $("#birthday").datepicker('setDate', (new Date().getFullYear() - 10).toString().concat('/01/01'));
      });
    });
    let search = document.getElementById('search');
    search.addEventListener('click', () => {

      let api = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=';
      let error = document.getElementById('error');
      let input = document.getElementById('zipcode');
      let address1 = document.getElementById('address1');
      let address2 = document.getElementById('address2');
      let address3 = document.getElementById('address3');
      let param = input.value.replace("-", ""); //入力された郵便番号から「-」を削除
      let url = api + param;

      fetchJsonp(url, {
          timeout: 10000, //タイムアウト時間
        })
        .then((response) => {
          // error.textContent = ''; //HTML側のエラーメッセージ初期化
          return response.json();
        })
        .then((data) => {
          if (data.status === 400) { //エラー時
            // error.textContent = data.message;
          } else if (data.results === null) {
            error.textContent = '郵便番号から住所が見つかりませんでした。';
          } else {
            address1.value = data.results[0].address1;
            address2.value = data.results[0].address2;
            address3.value = data.results[0].address3;
          }
        })
        .catch((ex) => { //例外処理
          console.log(ex);
        });
    }, false);
  </script>
</body>

</html>
