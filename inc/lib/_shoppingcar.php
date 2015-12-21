<?php
/**
 * cully create 20120926
 * 購物車模組
 */

class shoppingCar
{
	/**
	 *購物模組用的SESSION KEY值
	 *@Property keyname
	 *@type String	 
	 */
	var $keyname="shoppingCarSession";
	/**
	 *購物車的總金額
	 */	
	var $total=0;
	/**
	 *購物車的總數量 
	 */	
	var $num=0;
	/**
	 *運費金額
	 */	
	var $freightprice=0;	
	/**
	 *運費
	 */	
	var $freight=0;
	/**
	 *運費門檻
	 */	
	var $freightlimit=1000;
	/**
	 *折扣金額
	 */	
	var $discount=0;
	/**
	 *折扣名稱
	 */	
	var $discountcomment="";
	/**
	 *贈送紅利
	 */	
	var $bonus=0;
	/**
	 *折扣陣列
	 */	
	var $aryEvent=array();
	/**
	 *扣抵紅利
	 */	
	var $disbonus=0;	
	/**
	 *購物車array
	 */		 
	var $car;
	/**
	 *是否用SESSION儲存
	 */		 
	var $session;	
	/**
	 *建構式
	 *將SESSION的值指定到car變數	 
	 */			
	function shoppingCar($session=true){
		$this->session=$session;
		$this->car=$this->getCar();
	}
	
	/**
	 *取得session儲存的num變數
	 */		
	function getNum(){
		if($this->session){
			if(!isset($_SESSION[$this->keyname.'num']) || $_SESSION[$this->keyname.'num']==null){
				$_SESSION[$this->keyname.'num']=0;
				return 0;
			}else{
				return $_SESSION[$this->keyname.'num'];
			}
		}
		else
		{
			return $this->num;
		}
	}
	/**
	 *將shoppingItem的array儲存至SESSION
	 *param shoppingItem的array
	 */			
	function setNum($n){
		if($this->session){
			$_SESSION[$this->keyname.'num']=$n;
		}
		$this->num=$n;
	}	
	
	/**
	 *取得將car從DB中重新讀取一次
	 *return shoppingItem的array
	 */		
	function getCarFromDB(){
		$carItem = $this -> getCar();
		$len = count($carItem);
		for($i=0; $i<count($carItem); $i++){
			$carItem[$i] -> fromDB();
		}
		$this -> setCar($carItem);
		return $carItem;
	}
	
	/**
	 *取得session的資料放到變數car中
	 *return shoppingItem的array
	 */		
	function getCar(){
		if($this->session){
			if(!isset($_SESSION[$this->keyname]) || $_SESSION[$this->keyname]==null){
				$_SESSION[$this->keyname]=array();
				return array();
			}else{
				return unserialize($_SESSION[$this->keyname]);
			}
		}
		else{
			return $this->car;
		}
	}
	
	function getBackupCar(){
			if($this -> session){
			if(!isset($_SESSION[$this->keyname.'_back']) || $_SESSION[$this->keyname.'_back']==null){
				$_SESSION[$this->keyname.'_back'] = array();
				return array();
			}else{
				return unserialize($_SESSION[$this->keyname.'_back']);
			}
		}	
	}
	
	/**
	 *backup car
	 */			
	function backupCar($car){
			$_SESSION[$this->keyname.'_back']=serialize($car);
	}	
	/**
	 *將shoppingItem的array儲存至SESSION
	 *param shoppingItem的array
	 */			
	function setCar($car){
		if($this -> session){
			$_SESSION[$this -> keyname] = serialize($car);
		}
		$this -> car = $car;
	}
	/**
	 *清空購物車的SESSION
	 */		
	function clear(){
		if($this->session){
			unset($_SESSION[$this->keyname]);
			unset($_SESSION[$this->keyname.'num']);
		}
	}
	/**
	 *將shoppingItem項目新增到購物車中,如果不存在就新增,存在就更新數量
	 *param shoppingItem
	 */		
	function add($si){
		$car = $this -> car;
		$item = $this -> getItemByID($si -> product_id.$si->product_sno.$si->product_color);
		if($item == null){
			$this -> insertItem($si);
		}
		else{
			$item -> update('num', $item -> /*amount + $si ->*/ amount);
			$item -> update('subtotal', $item -> amount * $item -> sell_price);
			$this -> updateItem($item);
		}
		
	}
	/**
	 *將指定ID的數量更新成$num,如果數量為0則刪除
	 *param shoppingItem
	 */		
	function modify($sid, $num){
		$car = $this -> car;
		$item = $this -> getItemByID($sid);
		if($item != null){
			if($num > 0){
				$subtotal = $item -> sell_price*$num;
				$item->update('num',$num);
				$item->update('subtotal',$subtotal);
				$this->updateItem($item);
			}
			else{
				$this -> removeItemByID($sid);
			}
		}
	}	
	/**
	 *搜尋購物車物件中,ID一樣的shoppingItem
	 *param shoppingItem的id
	 *return id相同的shoppingItem
	 */	
	function getItemByID($id){
		$car = $this -> car;
		for($i=0; $i<count($car); $i++){
			if($car[$i]->product_id.$car[$i]->product_sno.$car[$i]->product_color == $id){
				return $car[$i];
			}
		}
		return null;
	}
	/**
	 *刪除購物車物件中,ID一樣的shoppingItem
	 *param shoppingItem的id
	 */		
	function removeItemByID($id){
		$car=$this->car;
		for($i=0;$i<count($car);$i++){
			if($car[$i]->product_id.$car[$i]->product_sno.$car[$i]->product_color == $id){
				unset($car[$i]);
			}
		}
		sort($car);
		$this->setCar($car);
	}
	
	/**
	 *將新的shoppingItem(ID不重覆的),新增至購物陣列的尾端,並更新SESSION
	 *param shoppingItem
	 */		
	function insertItem($item){
		$car = $this -> car;
		$car[] = $item;
		$this -> setCar($car);		
	}
	/**
	 *將購物車中ID一樣的shoppingItem,更新為參數傳進來的shoppingItem,並更新SESSION
	 *param shppingItem
	 */		
	function updateItem($item){
		$car=$this->car;
		for($i=0;$i<count($car);$i++){
			if($car[$i] -> product_id.$car[$i] -> product_sno.$car[$i] -> product_color == $item -> product_id.$item -> product_sno.$item -> product_color){
				$car[$i] = $item;
			}
		}
		$this->setCar($car);
	}
	
	/**
	 *計算優惠折扣
	 *將折扣內容陣列指定至$this->aryEvent;
	 *將折扣金額指定至$this->discount;
	 *將獲得紅利指定至$this->bonus;
	 *將折扣備註指定至$this->discountcomment;
	 */	
	/*function checkEvent(){
		$car=$this->car;
		$total=0;
		
		
		for($i=0;$i<count($car);$i++){
			$total+=$car[$i]->amount*$car[$i]->sellingprice;
		}	
		$aryDis=coderDiscount::getMultiDiscountTotal($car);
		
		$discount=0;
		foreach($aryDis as $dis){
			if($dis->type!=2){//紅利不能算
				$discount+=$dis->discount;
			}
		}
		//全館優惠必須以折扣後的金額計算
		$aryDisAll=coderDiscount::getDiscountTotalprice($total-$discount);
		 
		$aryDis=array_merge($aryDis,$aryDisAll);
		$discount=0;
		$bonus=0;
		$distitle="";
		foreach($aryDis as $dis){
			$distitle.=$dis->title.'-';
			if($dis->type != 2){//折扣內容為金額
				$discount+=$dis->discount;
				$distitle.='折扣金額:'.$dis->discount;
			}
			else if($dis->type==2){ //紅利
				$bonus+=$dis->discount;
				$distitle.='獲得紅利:'.$dis->discount;
			}
		}
		$this->discountcomment=$distitle;
		$this->discount= ($discount>$total) ? $total : $discount;
		$this->bonus=$bonus;
		$this->aryEvent=$aryDis;
	}*/
	
	/**
	 *重新計算購物中的總數和金額
	 *儲存到變數num和total中
	 */		
	function calculate($transportType = 0, $checkFreight = false, $promo_money = 0, $promo_discount = 1)
	{
		$car=$this->car;
		$this->num=0;
		$this->total=0;
		for($i=0;$i<count($car);$i++){
			$this->num++;
			$this->total+=$car[$i]->amount*$car[$i]->sell_price;
		}
		$this -> setNum($this -> num);

		$total = $this->total - $this->discount;

		if($checkFreight)
		{
			if($transportType < 3 && $total > $this->freightlimit)
			{
				$this->freightprice = 0;
			}
			else if($transportType == 1)
				$this->freightprice = 60;
			else if($transportType == 2)
				$this->freightprice = 100;
			else if($transportType == 3 && !$this->chkHaveBag())
				$this->freightprice = 200;
			else if($transportType == 3 && $this->chkHaveBag())
				$this->freightprice = 580;
			else if($transportType == 4 && !$this->chkHaveBag())
				$this->freightprice = 450;
			else if($transportType == 4 && $this->chkHaveBag())
				$this->freightprice = 610;
		}
		//$this->freightprice = 500;
		$this->total = ceil(($total + $this->freightprice - $promo_money) * $promo_discount);

		return $this->freightprice;
		//取得運費
		/*$rowfreight=getTableFreightCache();
		$this->freightprice=getFreight($rowfreight,'freightprice');
		$this->freightlimit=getFreight($rowfreight,'freightlimit');

		if($total< $this->freightlimit)
		{
			if($total <= 0)
			{
				$this->freight = 0;
			}
			else
			{
				$this->freight = $this->freightprice;
			}
		}
		else
		{
			$this->freight=0;
		}*/
		
		//兌換紅利
		/*$rowsbonuslimit=getBonusRewardLimit();
		
		$bonuslimit = isset($rowsbonuslimit[0]["limit"]) ? $rowsbonuslimit[0]["limit"] : 0;
		$memberbonus=getMemberBonus($memberid);
		
		if($total<=0){
			$this->disbonus=0;
		}else{
			$tempbonus=round(($total*($bonuslimit/100)));
			$b=$memberbonus-$tempbonus;
			if($b>=0){
				$this->disbonus=$tempbonus;
			}else{
				$this->disbonus=$memberbonus;
			}
		}*/
	}	
	/**
	 *將訂單明細的$rows儲存到car中
	 *@param 訂單明細的$row
	 */		
	function import($rows){
		if(count($rows)>0){
			$car=array();
			foreach($rows as $row){
				$car[]=new shoppingItem($row['pid'],$row['pname'],$row['price'],$row['num']);
			}
			$this->car=$car;
		}
	}	
	function chkHaveBag(){
		$car=$this->car;
		for($i=0;$i<count($car);$i++){
			if($car[$i]->product_type_id == 1)
				return true;
		}
		return false;
	}
}


class shoppingItem{
	var $product_id = 0,
		$product_sno = "",
		$special_price = 0,
		$sell_price = 0,
		$amount = 0,
		$subtotal = 0,
		$product_name_en = "",
		$product_name_tw = "",
		$pic = "",
		$product_type_id = 0,
		$product_color = 0;
		//$serial_id = 0,
		//$type_id = 0;
		
	function shoppingItem($product_id, $product_sno, $special_price, $sell_price, $amount, $subtotal, $product_name_en, $product_name_tw, $pic, $product_color){
		
		$this -> product_id = $product_id;
		$this -> product_sno = $product_sno;
		$this -> special_price = $special_price;
		$this -> sell_price = $sell_price;
		$this -> amount = $amount;
		$this -> subtotal = $subtotal;
		$this -> product_name_en = $product_name_en;
		$this -> product_name_tw = $product_name_tw;
		$this -> pic = $pic;
		$this -> product_color = $product_color;
		//$this -> serial_id = $serial_id;
		//$this -> type_id = $type_id;
	}
	
	function update($key,$val){
		if($key=="num"){
			$this->amount=$val;
		}
		if($key=="subtotal"){
			$this->subtotal=$val;
		}
	}
	/**
	 *將caritem資料從DB中重新讀取一次
	 */			
	function fromDB(){
		global $db;
		$sql="select * from lc_product where  product_id='".$this->product_id."' AND product_sno='".$this->product_sno."' order by product_ind DESC";
		$row = $db->query_first($sql);
		if($row){
			$this->product_id=$row["product_id"];
			$this->product_sno=$row['product_sno'];
			$this->sell_price=$row['product_sell_price'];
			$this->special_price=$row['product_special_price'];
			$this->subtotal=$this -> amount*$this->sell_price;
			$this->product_name_en=$row['product_name_en'];
			$this->product_name_tw=$row['product_name_tw'];
			$this->pic=$row['product_pic1'];
			$this->product_type_id=$row['product_type_id'];
			$this->product_color = $this->product_color;
		}
	}
}


//其餘shopping會用到的function.......
/*function getTableSizeCache(){
	global $db,$table_size;
	$sqlsize="select * from $table_size ";
	return getWebCache($sqlsize,"cache_product_size");
}

function getTableFreightCache(){
	global $db, $table_freight;
	$sqlfreight="select * from $table_freight";
	return getWebCache($sqlfreight,"cache_table_freight");
}
function getSize($rowssize,$sizesno){
	foreach($rowssize as $row){
		if($row["snumber"]==$sizesno){
			return $row["size"];
		}
	}
}
function getFreight($rowfreight,$keyword){
	foreach($rowfreight as $row){
		if($keyword==$row['keyword']){
			return $row['value'];
		}
	}
}
function getBonusRewardLimit(){
	global $db,$table_bonuslimit;
	$sqllimit="select * from $table_bonuslimit where isshow=1 limit 1";
	return getWebCache($sqllimit,"cache_bonus_limit");
}
function getMemberBonus($memberid){
	global $db,$table_bonus,$table_bonusreward;
	if($memberid==0){
		return 0;
	}else{
		$sqlbonus="select SUM(bonus) AS allbonus from $table_bonus where memberid='$memberid'";
		$rowbonus=$db->query_first($sqlbonus);
		
		$sqlrebonus="select SUM(rewardbonus) AS allrebonus from $table_bonusreward where memberid='$memberid'";
		$rowrebonus=$db->query_first($sqlrebonus);
		
		$a=$rowbonus["allbonus"]-$rowrebonus["allrebonus"];
		if($a<=0){
			return 0;
		}else{
			return $a;
		}
	}
	
}*/
//...........................

?>