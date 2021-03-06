<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$page = (get("page", 1) != "") ? get("page", 1) : "";
$pro_id = (get("pro_id", 1) != "") ? get("pro_id", 1) : "";
$type_id = (get("type_id", 1) != "") ? get("type_id", 1) : "";

$car = new shoppingCar();
$carItem = $car-> getCar();
$u = count($carItem);
?>
<!DOCTYPE html>
<html lang="zh_tw">
<head>
<meta charset="UTF-8">
<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="description" content="<?php echo $description; ?>">
<meta name="author" content="<?php echo $author; ?>">
<meta name="copyright" content="<?php echo $copyright; ?>">
<title><?php echo $web_name; ?></title>
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet" />
<!--bill add 20141018-->
<link href="assets/stylesheets/alertify.core.css" rel="stylesheet" />
<link href="assets/stylesheets/alertify.default.css" rel="stylesheet" />
<link media="screen" rel="stylesheet" href="ui/colorbox/colorbox.css" />
<link rel="shortcut icon" href="assets/images/favicon.ico">
<!--bill add 20141018-->
<script src="assets/scripts/jquery-2.1.1.min.js"></script>

</head>
<body>
    <div class="agreeContent">
        <p>
        歡迎申請成為及加入LazyCat電子商務網站會員（以下稱本網站），本會員、網站的服務是由『富喜國際有限公司』（下稱本公司）所建置提供。當您進行下列服務時，即表示您願意以電子文件之方式行使法律所賦予同意之權利，並具有書面同意之效果。 LazyCat電子商務網站為富喜國際有限公司經營管理，為了確保消費者之個人資料、隱私及消費者權益之保護，於交易過程中將使用消費者之個人資料，謹依個人資料保護法第8條規定告知以下事項：
        </p>
        <br>
        <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一、 蒐集目的及方式 本公司之蒐集目的在於進行客戶管理、會員管理及行銷與內部的統計調查與分   析(法定特定目的項目編號為 037, 066, 022, 060)。蒐集方式將透過加入會員或訂購單填寫方式進行個人資料之蒐集。
        </p>
        <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;二、 蒐集之個人資料類別 本公司於網站內蒐集的個人資料包括
        <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.  C001辨識個人者： 如消費者之姓名、地址、電話、電子郵件等資訊。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.  C002辨識財務者： 如信用卡或轉帳帳戶資訊。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.  C003政府資料中之辨識者： 如身分證字號或護照號碼(外國人)。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.  C011個人描述： 如性別、國籍、出生年月日。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;三、利用期間、地區、對象及方式
        <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.  期間：本公司營運期間。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.  地區：消費者之個人資料將用於台灣地區。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.  利用對象及方式：消費者之個人資料蒐集除用於本公司之會員管理、客戶管理之檢索查詢等功能外，將有以下利用：
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.  物品寄送：於交寄相關商品時，將消費者個人資料利用於交付給相關物流、郵寄廠商用於物品寄送之目的。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.  金融交易及授權：消費者所提供之財務相關資訊，將於金融交易過程(如信用卡授權、轉帳)時提交給金融機構以完成金融交易。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.  行銷：本公司將利用消費者之地址及郵件、電話號碼進行本公司或合作廠商商品之宣傳行銷。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四、消費者個人資料之權利 消費者交付本公司個人資料者，依個資法得行使以下權利：
        <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.  查詢或請求閱覽。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.  請求製給複製本。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.  請求補充或更正。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.  請求停止蒐集、處理或利用。
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.  請求刪除。
        <br>
    </div>
</body>
</html>