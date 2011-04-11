<?php
ini_set('memory_limit','164M');
?><html>
<head>
<title><?php echo $page_title;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
/**
 * Invoice view styles notes
 *
 * This file NEEDS a locally located stylesheet to generate the appropriate formatting for 
 * transformation into a PDF.  If you alter this file (and you are encouraged to do so) just
 * keep in mind that all of your formatting must be located here.  You might also find that
 * there is limited or no support for a specific CSS style you want (ie: floating) and you'll
 * need to work around with old-school tables.  Sorry for that... ;)  
 */

body {
	padding: 0;
	margin:0;
	background-image:url(http://invoice.6px.eu/img/facturabg.gif);
	background-color:#fff;
	background-position: 2300px 1900px;
	background-repeat: no-repeat; 

}
div.wrap{
	padding: 0.5in;
}

h1, h2, h3, h4, h5, h6, li, blockquote, p, th, td {
	font-family: helvetica, arial, sans-serif; /*Trebuchet MS,*/
}
h1, h2, h3, h4 {
	color: #c11800;
	font-weight: bold;
}
h4, h5, h6 {
	color: #c11800;
}
h2 {
	margin: 0 auto auto auto;
	font-size: x-large;
}
h2 span {
	text-transform: uppercase;
}
li, blockquote, p, th, td {
	font-size: 80%;
}
ul {
	list-style: url(img/bullet.gif) none;
}
table.header {
	width: 100%;
	margin-top:0;
	margin-bottom:20px;
}
table.invoice_items {
	width: 85%;
	margin-top:12px;
}
td p {
	font-size: small;
	margin: 3px 3px;
}
th {
	color: #FFF;
	text-align: left;
	padding:2px 5px 2px 5px;

	font-size:12px;
font-weight:bold;
}
th.qty{
	background-color:#f8a12e;
}
th.desc{
	background-color:#f3660a;
}
th.item{
	background-color:#e82801;
}
th.tot{
	background-color:#de1100;
}

.bamboo_invoice_bam {
	color: #5E88B6;
	font-weight: bold;
	text-transform: capitalize;
}
.bamboo_invoice_inv {
	font-weight: bold;
	font-variant: small-caps;
	color: #333;
}

table.stripe {
	border-collapse: collapse;
	page-break-after: auto;
}
table.stripe td {
	
}
td.first{
	border-left: 0.5pt solid #f8a12e;
}
td.last{
	border-right: 0.5pt solid #f8a12e;
}
.companyInfo , .companyInfo a{
	width:100%;
	text-decoration:none;
font-size:7px;
color:#777;
}
.factura{
	text-align:left;

	font-size:18px;
	margin-top: 0;
}
.factura span{
font-size:20px;
font-weight:bold;
}
.fecha{
	text-align:left;
	color:#777;

	font-size:9px;
	margin-top: 0;
}
.fecha span{

font-weight:bold;
}
h3.cliente{


font-weight:bold;
}
.detallesCliente, .detallesCliente a{
	color:#333;
	text-decoration:none;
}
td.total{
	background-color:#de1100;
	padding:2px 5px;
	color: #fff;
	font-weight:bold;
	font-size:10px;
}
td.totalKey{
	font-size:10px;
	padding:2px 5px;
	color: #000;
	font-weight:bold;
}
h3.pago{

	font-weight:bold;
	color:#de1100;
	font-size:13px;
}
p.pago{
	color:#777;

	font-size:9px;
	margin-top: 0;
}
p.pago span, p.pago span a{
	color:#777;
text-decoration:none;
	font-weight:bold;
	font-size:9px;
	margin-top: 0;
}
p.pago span.gastos{
    font-size:8px;
    font-weight:normal
}
h2.pago{

	font-weight:bold;
	font-size:20px;
	color:#de1100;
	margin-top:25px;
}
td.first, td.middle, td.last{
	border-bottom: 0.5pt solid #f8a12e;
	
}
td.middle{
	padding-right:12px;
}
td.lastRow{
	padding-top:40px;	
}
</style>
</head>
<body>
<div class="wrap">
<table class="wrap" width="100%">
	<tr>
		<td width="1%">
		<img src="/home/xiilocom/public_html/bambooinvoice/img/spacer.gif" />
		</td>
		<td width="99%">
	<table class="header">
		<tr>
			<td width="58%">
				<h2>
				<img src="/home/xiilocom/public_html/bambooinvoice/img/logo-hor-big.jpg" />
				</h2>

				<p class="companyInfo">
					Jonathan Foucher - <?php echo $companyInfo->address1;?> -
                    <?php if ($companyInfo->address2 != '')
                {echo $companyInfo->address2." - ";}?> <?php echo $companyInfo->postal_code;?> -
                    <?php echo $companyInfo->city;?> - <?php echo $companyInfo->country;?> -
                    <?php echo auto_link(prep_url($companyInfo->website));?>
				</p>

			</td>
			<td>
			<div class="facturaWrap">
				<p class="factura">
						<?php echo strtoupper($this->lang->line('invoice_'.$row->type));?> <span><?php echo substr($date_invoice_issued,-2);?>
                    <?php echo ($row->invoice_number<10) ? "0".$row->invoice_number : $row->invoice_number;?></span>
				</p>
				<p class="fecha"><?php echo $this->lang->line('invoice_date_issued')?>  <span> <?php echo $date_invoice_issued;?></span>
				</p>
				<p class="fecha"><?php echo $this->lang->line($row->type.'_date_due')?> <span> <?php echo $date_invoice_due;?></span>
				</p>
				</div>
			</td>
		</tr>
	</table>

	<h3 class="cliente"><?php echo ucwords($this->lang->line('clients_to'));?>
		<?php echo $row->name;?>
	</h3>

	<p class="detallesCliente">
		<?php if ($row->address1 != '') {echo $row->address1;}?>
		<?php if ($row->address2 != '') {echo ', ' . $row->address2;}?>
		<?php if ($row->address1 != '' || $row->address2 != '') {echo '<br />';}?>
		<?php if ($row->postal_code != '') {echo ' ' . $row->postal_code;}?>
		<?php if ($row->city != '') {echo $row->city;}?>
		<?php if ($row->province != '') {if ($row->city != '') {echo ', ';} echo $row->province;}?>
		<?php if ($row->country != '') {if ($row->province != '' || ($row->province == '' && $row->city != '')){echo ', ';} echo $row->country;}?>
		
		<?php if ($row->city != '' || $row->province != '' || $row->country != '' || $row->postal_code != '') {echo '<br />';}?>
		<?php echo auto_link(prep_url($row->website));?>
		<?php if ($row->tax_code != '') {echo '<br />'.$this->lang->line('settings_tax_code').': '.$row->tax_code;}?>
	</p>

	<table class="invoice_items stripe">
		<tr>
			<th class="qty"><?php echo $this->lang->line('invoice_quantity');?></th>
			<th class="desc"><?php echo $this->lang->line('invoice_work_description');?></th>
			<th class="desc"></th>
			<th class="item"><?php echo $this->lang->line('invoice_amount_item');?></th>
			<th class="tot"><?php echo $this->lang->line('invoice_total');?></th>
		</tr>
		<?php foreach ($items->result() as $item):?>
		<tr valign="top">
			<td class="first"><p><?php echo str_replace('.00', '', $item->quantity);?><?php echo ($item->quantity==1) ? '' : ' h'?></p></td>
			<td colspan="2" class="middle"><?php echo nl2br(str_replace(array('\n', '\r'), "\n", $item->work_description));?></td>
			
			<td class="middle"><p>
                <?php echo $this->settings_model->get_setting('currency_symbol') . str_replace('.', $this->config->item('currency_decimal'), $item->amount);?>
                <?php if ($item->taxable == 0){echo '(' . $this->lang->line('invoice_not_taxable') . ')';}?></p></td>
			<td class="last"><p><?php echo $this->settings_model->get_setting('currency_symbol') . number_format($item->quantity * $item->amount, 2, $this->config->item('currency_decimal'), '');?></p></td>
		</tr>
		<?php endforeach;?>
		<tr><td colspan="4" class="first"><p><?php echo $this->lang->line('invoice_amount')?></p></td><td class="last"><p><?php echo $no_tax_amount?></p></td></tr>
		<?php 
		foreach($tax_data as $tax){ ?>
		<tr><td colspan="4" class="first"><p><?php echo $tax['data']?></p></td><td class="last"><p><?php echo $tax['value']?></p></td></tr>
		<?php
		}
		?>
		<tr><td colspan="4" class="totalKey first"><?php echo $this->lang->line('invoice_total')?></td><td class="total last"><?php echo $with_tax_amount?></td></tr>
		<?php
		if ($total_paid != '') { ?>
		<tr><td colspan="4" class="first"><p><?php echo $this->lang->line('invoice_amount_paid')?></p></td><td class="last"><p><?php echo $paid_amount?></p></td></tr>
		<tr><td colspan="4" class="totalKey first"><?php echo $this->lang->line('invoice_amount_outstanding')?></td><td class="total last"><?php echo $outstanding_amount?></td></tr>
		<?php } ?>
			<tr>
	<td colspan="2" class="lastRow">
				<h3 class="pago"><?php echo $this->lang->line('pago')?></h3>
				<p class="pago">
					<?php echo ucwords($this->lang->line('clients_to'))?> <span>Jonathan Foucher</span><br>
					<?php echo $this->lang->line('banco')?> <span>Ibercaja</span><br>
					<?php echo $this->lang->line('cuenta')?> <span><?php echo $this->lang->line('account_num')?> </span><br>
<?php if ((($this->settings_model->get_setting('google_merchant_id')) or ($this->settings_model->get_setting('paypal_email'))) and $row->type=='invoice'): ?>
		<?
			if ($this->settings_model->get_setting('google_merchant_id'))
			{
				echo "Google Checkout ";
				echo '<span><a href="'.$this->config->item('base_url').$this->config->item('index_page').'/paynow/googlecheckout/'.$id.'">
					<img src="https://checkout.google.com/buttons/buy.gif?merchant_id='.$this->settings_model->get_setting('google_merchant_id').'&amp;w=117&amp;h=48&amp;style=white&amp;variant=text&amp;loc=en_US">
				</a></span><br>';
			}
			

            //$paypal_amount=number_format ( $paypal_amount , 2);
			if ($this->settings_model->get_setting('paypal_email'))
			{
				echo "Paypal ";
				echo '<span><a href="'.$this->config->item('base_url').$this->config->item('index_page').'/paynow/paypal/'.$id.'">
					Pagar '.number_format($paypal_amount,2).' ahora
				</a></span><span class="gastos">('.$this->lang->line('incluye_gastos_paypal').')</span>';
			}
		?>
	</p>
	<br>
	<?php endif; ?>
				</p>
				<?php
				if($row->type=="estimate"){ ?>
				<h3 class="pago"><?php echo $this->lang->line('condiciones')?></h3>
				<p class="pago">
					50% <span><?php echo $this->lang->line('antes')?></span><br>
					50% <span><?php echo $this->lang->line('entrega')?></span><br>
				</p>
				<?php }else{ ?>
				<h3 class="pago"><?php echo $this->lang->line('condiciones')?></h3>
				<p class="pago">
					<?php echo $this->lang->line('intereses_mora')?> <span>EURIBOR + 20%</span><br>
					<?php echo $this->lang->line('vencimiento')?> <span>30 <?php echo $this->lang->line('days')?></span><br>
				</p>
				<?php } ?>
</td>
<td colspan="3" class="lastRow">
					<h3 class="pago"><?php echo $this->lang->line('otra_info')?></h3>
					<p class="pago">NIF <span>Y0715962-D</span></p>

					<h2 class="pago"><?php echo $this->lang->line('gracias_confianza')?></h2>
	 </td>
	</tr>
	</table>

	 </td>
	</tr>
	
</table>

</div>

</body>
</html>
