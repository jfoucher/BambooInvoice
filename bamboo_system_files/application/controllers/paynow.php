<?
class PayNow extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model('invoices_model');
		$this->load->model('settings_model');
	}
	
	// --------------------------------------------------------------------

	function index()
	{
		redirect('');
	}
	
	// --------------------------------------------------------------------
	function get_currency(){
		if (settings_model::get_setting('currency_type')!=''){
			return settings_model::get_setting('currency_type');
		}else{
			$cur_symbol=settings_model::get_setting('currency_symbol');
			if ($cur_symbol==htmlentities('$')) return "USD";
			if ($cur_symbol=='&#0128;') return "EUR";
			if ($cur_symbol=='£') return "GBP";
			if ($cur_symbol=='¥') return "JPY";
		}
		//Add more currencies//
		
	}
	function googlecheckout($id)
	{
		if (!$id)
			redirect('');

		$cur=$this->get_currency();
		
		$data['row'] = $this->invoices_model->getSingleInvoice($id)->row();
		if ($data['row']->amount_paid > 0)
		{
		$outstanding_amount=number_format($data['row']->total_with_tax - $data['row']->amount_paid, 2, $this->config->item('currency_decimal'), '');
		}
		else
		{
			$outstanding_amount=substr($data['row']->total_with_tax, 0, -2);

		}
		if (!$data['row'])
			redirect('');
		
		echo '<form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/'.$this->settings_model->get_setting('google_merchant_id').'" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm">
			<input name="item_name_1" type="hidden" value="Invoice '.$data['row']->invoice_number.'"/>
			<input name="item_description_1" type="hidden" value=""/>
			<input name="item_quantity_1" type="hidden" value="1"/>
			<input name="item_price_1" type="hidden" value="'.$outstanding_amount.'"/>
			<input name="item_currency_1" type="hidden" value="'.$cur.'"/>
			<input name="_charset_" type="hidden" value="utf-8"/>
		</form>
		<script>document.forms["BB_BuyButtonForm"].submit();</script>';
	}
	
	// --------------------------------------------------------------------

	function paypal($id)
	{
		
		echo "redirecting to Paypal";
		if (!$id)
			redirect('');
			
		$cur=$this->get_currency();
		$data['row'] = $this->invoices_model->getSingleInvoice($id)->row();
		
		if (!$data['row'])
			redirect('');
		if ($data['row']->amount_paid > 0)
		{
           
		$outstanding_amount=number_format(1.029 * ($data['row']->total_with_tax - $data['row']->amount_paid) + 0.3 , 2, $this->config->item('currency_decimal'), '');
		}
		else
		{
			$outstanding_amount=substr(1.029 * $data['row']->total_with_tax + 0.3, 0, -2);

		}
		//echo $data['row']->total_with_tax."<br>";
		//echo $outstanding_amount."<br>";
		echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypalredirect">
				<input type="hidden" name="business" value="'.$this->settings_model->get_setting('paypal_email').'"> 
				<input type="hidden" name="cmd" value="_xclick"> 
				<input type="hidden" name="item_name" value="Factura '.$data['row']->invoice_number.'">
				<input type="hidden" name="amount" value="'.$outstanding_amount.'"> 
				<input type="hidden" name="currency_code" value="'.$cur.'"> 
			</form>
			<script>document.forms["paypalredirect"].submit();</script>';
	}
}
?>
