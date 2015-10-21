<?php 
class CoderOrder{
	
	public function __construct(){
	}
	
	public function getOrderNO(){
		global $db, $table_ordersno;
		$sno = "";
		$today = date("Y-m-d", time());
		$orderno = explode("-", $today);
		$year = $orderno[0];
		$month = $orderno[1];
		$no = str_pad("1", 5, "0", STR_PAD_LEFT);
		
		$db -> begin();
		try{
			$sql = "SELECT * 
					FROM  $table_ordersno 
					WHERE ordersno_year = $year AND ordersno_month = $month 
					ORDER BY ordersno_sno DESC 
					LIMIT 1 FOR UPDATE";
			$row = $db -> query_first($sql);
			if($row){
				$id = $row['ordersno_id'];
				$sd = $row['ordersno_sno']+1;
				$no = str_pad($sd, 5, "0", STR_PAD_LEFT);
			
				$data["ordersno_year"] = $year;
				$data["ordersno_month"] = $month;
				$data["ordersno_sno"] = $no;
				$data["ordersno_create_time"] = request_cd();
	
				$db -> query_update($table_ordersno, $data, "ordersno_id = '$id'");
				
				$sno = $year.$month.$no;
			}else{
				$data["ordersno_year"]=$year;
				$data["ordersno_month"]=$month;
				$data["ordersno_sno"]=$no;
				$data["ordersno_create_time"] = request_cd();
	
				$db -> query_insert($table_ordersno, $data);
			 
				$sno = $year.$month.$no;
			}
			$db -> commit();
			return $sno;
		}catch(Exception $e){
			$db -> rollback();
			throw new Exception("訂單新增錯誤,請再操作一次.");
		}
	}
	
	public function orderDetailInsert($order_sno, $detail_ary){
		global $db, $table_orderdetail, $table_product, $table_storelog;
		$db -> query("DELETE FROM $table_orderdetail WHERE orderdetail_order_sno = '$order_sno'");
		
		for($i = 0; $i < count($detail_ary); $i ++){
			/*get data from $detailary put into $dataDetail*/
			$data_detail = array();
			$data_detail["orderdetail_order_sno"] = $order_sno;
			$data_detail["orderdetail_product_id"] = $detail_ary[$i] -> product_id;
			$data_detail["orderdetail_product_sno"] = $detail_ary[$i] -> product_sno;
			$data_detail["orderdetail_sell_price"] = $detail_ary[$i] -> sell_price;
			$data_detail["orderdetail_amount"] = $detail_ary[$i] -> amount;
			$data_detail["orderdetail_total_price"] = $detail_ary[$i] -> subtotal;
			$data_detail["orderdetail_create_time"] = $detail_ary[$i] -> create_time;
			
			$db -> query_insert($table_orderdetail, $data_detail);
			
			$product_id = $detail_ary[$i] -> product_id;
			$amount = $detail_ary[$i] -> amount;
			$row = $db -> query_first("SELECT * FROM $table_product WHERE product_id = '$product_id'");
			$stock = $row["product_stock"];
			
			$data_pro["product_stock"] = ($stock - $amount);
			
			$db -> query_update($table_product, $data_pro, "product_id = $product_id");
			
			//寫入log
			$data_store["storelog_product_id"] = $product_id;
			$data_store["storelog_acc"] = "網站";
			$data_store["storelog_comment"] = "(訂單寫入)庫存由".$stock."修改為".($stock - $amount);
			$data_store["storelog_create_time"] = request_cd();
			
			$db -> query_insert($table_storelog, $data_store);
			
		}
	}
	
	public function chkStock($detail_ary){
		global $db, $table_product;
		$result = array(true, "");
		for($i = 0; $i < count($detail_ary); $i ++){
			$product_id = $detail_ary[$i] -> product_id;
			$order_amount = $detail_ary[$i] -> amount;
			
			$row = $db -> query_first("SELECT * FROM $table_product WHERE product_id = '$product_id' AND product_stock < '$order_amount'");
			
			if($row){
				$result[0] = false;
				$result[1] = $row["product_name_tw"]."庫存不足";
				return $result;
			}
		}
		return $result;
	}
	
	public function orderInsert($myorder){
		global $db, $table_order;
		$sno = $this -> getOrderNO();
		
		$db -> begin();
			try{
				$myorder -> order_sno = $sno;
				$myorder -> ind = $this -> getMaxInd($table_order, "order_ind", "");
				
				/*get data from $myorder put into $dataOrder*/
				$data_order = array();
				$data_order["order_ind"] = $myorder -> ind;
				$data_order["order_sno"] = $myorder -> order_sno;
				$data_order["order_member_id"] = $myorder -> member_id;
				$data_order["order_payment_type"] = $myorder -> payment_type;
				$data_order["order_payment_state"] = $myorder -> payment_state;
				$data_order["order_state"] = $myorder -> order_state;
				$data_order["order_comment"] = $myorder -> order_comment;
				$data_order["order_total_price"] = $myorder -> total_price;
				$data_order["order_freight"] = $myorder -> freight;
				$data_order["order_recipient_name"] = $myorder -> recipient_name;
				$data_order["order_recipient_email"] = $myorder -> recipient_email;
				$data_order["order_recipient_mobile"] = $myorder -> recipient_mobile;
				$data_order["order_recipient_address"] = $myorder -> recipient_address;
				$data_order["order_recipient_wayOption"] = $myorder -> recipient_wayOption;
				$data_order["order_transport_memo"] = $myorder -> transport_memo;
				$data_order["order_manager"] = "";
				$data_order["order_create_time"] = $myorder -> create_time;
				$data_order["order_update_time"] = $myorder -> update_time;
				$data_order["order_payment_return"] = $myorder -> payment_return;
				
				$db -> query_insert($table_order, $data_order);
		
				$order_sno = $sno;
				$this -> orderDetailInsert($order_sno, $myorder -> detail_ary);
			
				$db -> commit();
			}catch(Exception $e){
				$db -> rollback();
				script($e -> getMessage());
			}
		//$db->close();
		
	}
	
	public function orderUpdate($myorder, $id){
		global $db, $table_order;
		
		/*get data from $myorder put into $dataOrder*/
		$data_order = array();
		$row_ordernumber = $db -> query_first("SELECT order_ordernumber FROM $table_order WHERE order_id='$id'");
		if(!$row_ordernumber){
			throw new Exception("查無此訂單資料!");
			return;
		}
		$ordernumber = $row_ordernumber["ordernumber"];
		$data_order["order_memberid"] = $myorder -> memberid;
		$data_order["order_payway"] = $myorder -> payway;
		$data_order["order_bank"] = $myorder -> bankname;
		$data_order["order_installment"] = $myorder -> installment;
		$data_order["order_paytype"] = $myorder -> paytype;
		$data_order["order_status"] = $myorder -> status;
		$data_order["order_comment"] = $myorder -> comment;
		$data_order["order_bankno"] = $myorder -> bankno;
		$data_order["order_totalprice"] = $myorder -> totalprice;
		$data_order["order_freight"] = $myorder -> freight;
		$data_order["order_manager"] = $myorder -> manager;
		$data_order["order_isshow"] = $myorder -> isshow;
		//$data_order["order_createtime"] = $myorder -> createtime;
		
		$db -> query_update($table_order, $data_order, "order_id = $id");
		
		$this -> orderDetailInsert($ordernumber, $myorder -> detail_ary);
		
		//$db -> close();
	}
	
	function getMaxInd($table, $field, $where){
		global $db;
		$row = $db -> query_first("SELECT MAX($field) AS max FROM $table $where", "max");
		$maxind = intval($row["max"]);
		
		if($maxind == 0){
			$maxind = 1;
		}else{
			$maxind += 5;
		}
		return $maxind;
	}
	
	public function orderMail($myorder){
		global $db, $table_member, $ary_payment_type, $sys_email, $sys_name;
		
		$member_id = $myorder -> member_id;
		$sql = "SELECT * FROM $table_member WHERE member_id = '$member_id' ";
		$row = $db -> query_first($sql);
		
		$member_name = $row["member_name"];
		$member_email = $row["member_email"];
		$db -> close();	
		
		
		$order_sno = $myorder ->order_sno;
		$create_time = $myorder ->create_time;
		$total_price = $myorder ->total_price;
		//$freight = $myorder -> freight;
		$payment_type = $myorder -> payment_type;
		//$alltotal = $myorder -> totalprice;
		$detailary = $myorder->detail_ary;
		//$allDiscount = ($disbonus+$discount);
		
		$fr_em = $sys_email;
		$fr_na = $sys_name;
		$to_em = $member_email;
		//$to_em = "bill@coder.com.tw";
		$to_na = $member_name;
		$subject = "【訂單成立】- 感謝您的購買!";
		$msg = "";
		
		$msg .= '親愛的顧客'.$member_name.'您好，感謝您的訂購，以下為您的訂單內容：<br /><br />';
		$msg .= '訂單編號：'.$order_sno.'<br />';
		$msg .= '付款方式：'.$ary_payment_type[$payment_type].'<br />';
		$msg .= '<br />';
        $msg .= '*若您是使用ATM虛擬帳號付款,超商代收或7-11ibon / 全家FamiPort / 萊爾富Life-ET / OK 超商OK-go等付款方式,請在指定天數內付款，確認付款後我們將為您盡快出貨<br /><br />';
		foreach($detailary as $item){
			$msg .= $item -> product_name_tw.'/'.$item -> product_name_en.' &nbsp;&nbsp;* '.$item -> amount.'&nbsp;&nbsp;&nbsp;&nbsp;'.$item -> subtotal.'<br /><br />';
		}
		$msg .= '總計：'.$total_price.'<br /><br />';
		$msg.='本系統已經收到您的訂購訊息，並供您再次自行核對之用，此通知函不代表交易已經完成。<br />';
		$msg.='感謝您的訂購，有任何問題請歡迎聯絡客服中心。<br /><br />';
			
			
		sendMail($fr_em, $fr_na, $to_em, $to_na, $subject, $msg);

	}	

}


class CoderOrderItem{
	public $ind, $order_sno, $member_id, $payment_type, $payment_state, $order_state, $order_comment, $total_price, $freight, $recipient_name, $recipient_email, $recipient_mobile, $recipient_address, $create_time, $update_time, $payment_return, $detail_ary = array();
	
	public function _construct(){
	}
	
	public function insertDetail(CoderOrderDetailItem $detail_item ){
		$this -> detail_ary[] = $detail_item;
	}
	
}

class CoderOrderDetailItem{
	public $id, $order_sno, $product_id, $product_sno, $product_name_tw, $product_name_en, $sell_price, $amount, $subtotal, $create_time;
	
	public function _construct(){
	}
	
}

/*End PHP*/