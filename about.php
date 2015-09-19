<?php
include_once("_config.php");
include_once($inc_path.'lib/_shoppingcar.php');

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
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="scripts/jquery.validate.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="assets/scripts/demo.js"></script>
<script src="assets/scripts/about.js"></script>

</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="map"> <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3614.729892992575!2d121.50596490000002!3d25.043239!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3442a909047ede5f%3A0xb9e1ef28c2d46460!2zMTA45Y-w5YyX5biC6JCs6I-v5Y2A6KW_5a-n5Y2X6LevNzItMeiZn-ilv-mWgOaWsOWuvw!5e0!3m2!1szh-TW!2stw!4v1419937651330" width="1280" height="450" frameborder="0" style="border:0"></iframe> </div>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="product-list.html">shopping car</a> </div>
<div class="company-info">
    <div class="about">
        <div class="title"></div>
        <div class="abo-info"> O R I G I N S<br />
            <br />
            C L A S H是ㄧ個不斷成長的地方擁有著精緻與壞女孩的封號。被新一代的設計師啟發，創始人Serena Chung設立店面在台北引進大膽和前衛的風格進入亞州。每件親手精心挑選來自世界各地的獨特商品，混搭眾所周知的品牌和新銳設計帶給我們的消費者快速變化的選擇，唯獨在C L A S H。<br />
            <br />
            <br />
            G R O W T H<br />
            <br />
            March 2013 - 我們成立了一個網絡商店 - www.clash.tw - 隨時隨地讓女孩們能輕易得到C L A S H的個性配備！<br />
            <br />
            July 2013 - 在短短兩年時間，非常感謝C L A S H的支持者，我們很高興的宣布，我們已經擴大到一個新的店面 - ㄧ起期望更多更好的東西！<br />
            <br />
            <br />
            *我們提供全球運送！<br />
            <br />
            S H O P <br />
            Business Hours: 14:00 ~ 22:00<br />
            地址: 台北市大安區忠孝東路四段205巷29弄6號<br />
            ADD: No. 6, Alley 29, Lane 205, Section 4<br />
            ZhōngXiào East Rd, Daan District, Taipei City, Taiwan 106<br />
            <br />
            C O N T A C T<br />
            Email:  hello@clash.tw<br />
            Phone: +886 2 2721 9994<br />
            <br />
            <br />
            -------------------------------<br />
            <br />
            退換貨政策<br />
            <br />
            C L A S H任何商品都不提供退換貨。<br />
            但是為了確保所有顧客對購買的商品都很滿意，我們會審查特殊案例。如購買商品收到後，發現是瑕疵品或商品與你原本訂購的不符合 (商品必須是完整包裝: 全新，沒穿過，沒洗過，附上原本標籤) 才可以送審做退換貨。任何商品不符合這些標準或是折扣商品/不可退貨商品將被拒絕。須自收到商品三日之內傳信到hello@clash.tw引用您的訂單號碼，姓名和地址，產品的詳細資料及換貨原因跟提供照片,經由C L A S H專人批准後，我們會幫您做換貨，商品必須是同等價位或是高於商品金額。然而，請注意運費和手續費將不予退還。<br />
        </div>
    </div>

</div>
<?php include_once($inc_path."page/_footer.php"); ?>
</body>
</html>