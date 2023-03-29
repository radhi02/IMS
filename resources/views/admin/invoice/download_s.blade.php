<!DOCTYPE html>
<html lang="en">
<head>
  <title>:: Inventory Project ::</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <style type="text/css">
  	* {box-sizing: border-box; }

  	body{font-family: 'Poppins', sans-serif; font-size: 14px;margin: 0; padding: 0;}

  	h2{ font-size: 20px; line-height: 22px; margin-top: 0; margin-bottom: 5px; }
	h3{ font-size: 18px; line-height: 22px; margin: 0; color:#0070c0; }
  	h5{ font-size: 14px; line-height: 16px; margin: 0; }
  	h6{ font-size: 14px; margin: 0; font-weight: normal; }
  	p{margin: 0 0 5px; line-height: 14px;}

  	.invoice_container{ max-width: 1300px; }
  	.main_header{ display: flex; justify-content: space-between; }

  	.invoice_header{ display: flex;justify-content: space-between; align-items: center; }
  	.invoice_header *{ margin: 0; }

  	table{ width: 100%;border-collapse: collapse; position: relative; }
	table, th, td {
	  border-spacing: 0px;
	}
	td, th {
	  text-align: left; vertical-align: top;
	  padding: 8px; 
	}

	.has_border > tbody > tr > td,.has_border tfoot tr td{border: 1px solid #0070c0 !important;}
	.has_border thead tr th{border: 1px solid #0070c0; text-align: center; vertical-align: middle;}

	.invoice_table tr td{ text-align: center; }
	.invoice_table tbody tr td{border-bottom: none; border-top: none;}
	.invoice_table tbody tr td:nth-child(2){ text-align: left; }
	.invoice_table tr td:nth-last-child(-n+4){ text-align: right; }

	table .has_border > tbody > tr > td:first-child,
	table .has_border > tfoot > tr > td:first-child,
	table .has_border > thead > tr > th:first-child{ border-left:0 !important;}

	table .has_border > tbody > tr > td:last-child,
	table .has_border > tfoot > tr > td:last-child,
	table .has_border > thead > tr > th:last-child{ border-right:0 !important;}

	.invoice_table thead tr:first-child th{ border-top: 0; }
	.invoice_table tfoot tr td{border-bottom: 0;}

	tfoot tr td{ font-weight: bold; }

	.blank_box td{ height: 40px;  }

	.text-center{ text-align: center; }

	.text-right{ text-align: right !important; }
	.bl-0{ border-left: 0 !important; }
	.br-0{ border-right: 0 !important; }

	table table td{ padding: 1px 5px; }


	.border-0{ border: none !important; }
	td.p-0{ padding: 0; }

	.bg_color th,.bg_color td{ background-color:#e8f3fd;}

	.bg_color{ background-color:#e8f3fd;}
	
	@page {
	  size: A4;
	}

	@media print {

	  body {
	    margin: 0;
	    color: #000;
	    background-color: #fff; line-height: 18px;
	  }

	  td, th{ padding: 5px; }
	 

	  .invoice_container{ margin: 0 auto !important;  }
	  

	    	
	}



</style>
</head>
<body>

	<div class="invoice_container">
        <div class="main_header">
			<table>
				<tbody>
					<tr>
						<td>
							<div class="office_detail">
								<h2><?=$CompanyDetails->company_name?></h2>
								<p>
									<?=$CompanyDetails->reg_off_add?><br>
									<?=strtoupper($CompanyDetails->city)?>, <?=strtoupper($CompanyDetails->state)?> - <?=$CompanyDetails->pincode?>
								</p>
							</div>
						</td>
						<td>
							<div class="personal_detail">
								<h6><b>Name :</b> <?=strtoupper($CompanyDetails->company_name)?></h6>
								<h6><b>Phone :</b> <?=strtoupper($CompanyDetails->contact_no)?></h6>
								<h6><b>Email :</b> <?=strtoupper($CompanyDetails->email)?></h6>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
            
            
        </div>

		<table class="has_border">
			<tbody>
				<tr>
					<td colspan="2">
						<table>
							<tbody>
								<tr>
									<td style="text-align:left;"><h5><b>GSTIN :</b> <?=$CompanyDetails->gst_no?></h5></td>
									<td><h3>TAX INVOICE</h3></td>
									<td style="text-align:right;"><h5>ORIGINAL FOR RECIPIENT</h5></td>
								</tr>
							</tbody>
						</table>
						
					</td>
				</tr>
				<tr>
					<td style=" text-align: center; "><b>Customer Detail</b></td>
					<td rowspan="2">
						<table>
							<tr>
								<td><b>Invoice No. <b></td>
								<td><?=$InvoiceOrder->code?>    </td>
								<td><b>Invoice Date </td>
								<td><?php $created_at = date_create($InvoiceOrder->created_at);
                                echo date_format($created_at,"d M Y");?></td>
							</tr>
                            <tr>
								<td><b>Due Date. <b></td>
								<td><?php $due_date = date_create($InvoiceOrder->due_date);
                                echo date_format($due_date,"d M Y");?></td>
							</tr>
                            <tr>
								<td><b>Vehicle No. <b></td>
								<td>-</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td><b>M/S</b></td>
								<td><?=ucwords($InvoiceOrder->customer_name)?></td>
							</tr>
							<tr>
								<td><b>Address</b></td>
								<td>
                                    <?=$InvoiceOrder->customer_street?> ,<?=$InvoiceOrder->cityname?><br>
									<?=$InvoiceOrder->statename?> ,<?=$InvoiceOrder->countryname?> <br>
									<?=$InvoiceOrder->customer_zipcode?>
								</td>
							</tr>
							<tr>
								<td><b>PHONE</b></td>
								<td><?=$InvoiceOrder->customer_phone?></td>
							</tr>
							<tr>
								<td><b>GSTIN</b></td>
								<td><?=$InvoiceOrder->customer_GST??'-'?> </td>
							</tr>
							<tr>
								<td><b>PAN</b></td>
								<td><?=$InvoiceOrder->customer_PAN??'-'?> </td>
							</tr>
							<tr>
								<td><b>Place of Supply</b></td>
								<td><?=ucfirst($CompanyDetails->state)?> ( 24 ) </td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2" class="p-0 border-0">
						<table class="has_border invoice_table">
							<thead>
								<tr class="bg_color">
									<th rowspan="2">Sr. No. </th>
									<th rowspan="2">Name of Product / Service </th>
									<th rowspan="2">HSN / SAC </th>
									<th rowspan="2">Qty </th>
									<th rowspan="2">Rate</th>
									<th rowspan="2">Taxable Value</th>
									<?php if($InvoiceOrder['stateid'] == $CompanyDetails->state) { ?>
										<th colspan="2">IGST</th>
										<th colspan="2">CGST</th>
									<?php } else { ?>
										<th colspan="2">SGST</th>
									<?php } ?>
									<th rowspan="2">Total</th>
								</tr>
								<tr class="bg_color">
									<?php if($InvoiceOrder['stateid'] == $CompanyDetails->state) { ?>
										<th>%</th>
										<th>Amount</th>
										<th>%</th>
										<th>Amount</th>
									<?php } else { ?>
										<th>%</th>
										<th>Amount</th>
									<?php } ?>
								</tr>
							</thead>

							<tbody>
                                <?php 
                                // print_r($InvoiceOrder->code); 
                                if(!empty($InvoiceOrder['order_products'])) {
                                    $InvoiceProducts = json_decode($InvoiceOrder['order_products'],true); $i = 1;
                                    if(count($InvoiceProducts) > 0) { 
                                        foreach($InvoiceProducts as $ip1)  { ?>
                                    <tr>
										<td><?php echo $i ?></td>
                                        <td><?php echo ProductDetail($ip1['product_id'])->name ?></td>
                                        <td><?php echo ProductDetail($ip1['product_id'])->sku ?></td>
                                        <td><?php echo $ip1['quantity'] ?></td>
                                        <td><?php echo $ip1['base_price'] ?></td>
                                        <td><?php echo $ip1['base_subtotal_withoutax'] ?></td>
										<?php if($InvoiceOrder['stateid'] == $CompanyDetails->state) { ?>
											<td><?= $ip1['rawtax']/2 ?></td>
											<td><?= $ip1['rawtaxamount']/2 ?></td>
											<td><?= $ip1['rawtax']/2 ?></td>
											<td><?= $ip1['rawtaxamount']/2 ?></td>
										<?php } else { ?>
											<td><?= $ip1['rawtax'] ?></td>
											<td><?= $ip1['rawtaxamount'] ?></td>
										<?php } ?>
										<td><?php echo $ip1['base_total'] ?></td>
                                    </tr>
                                    <?php $i++; } 
                                    }
                                } ?>
								
							</tbody>
							<tfoot>
								<tr class="bg_color">
									<td colspan="3" class="text-right" style="border-bottom:0 !important;">Total</td>
									<td  style="border-bottom:0 !important;"><?= $InvoiceOrder->base_total_quantity ?></td>
									<td  style="border-bottom:0 !important;"><?= $InvoiceOrder->base_total_rate ?></td>
									<td  style="border-bottom:0 !important;"><?= $InvoiceOrder->base_subtotal ?></td>
									<?php if($InvoiceOrder['stateid'] == $CompanyDetails->state) { ?>
										<td  style="border-bottom:0 !important;">-</td>
										<td  style="border-bottom:0 !important;"><?= ($InvoiceOrder->base_tax_amount)/2 ?></td>
										<td  style="border-bottom:0 !important;">-</td>
										<td  style="border-bottom:0 !important;"><?= ($InvoiceOrder->base_tax_amount)/2 ?></td>
									<?php } else { ?>
										<td  style="border-bottom:0 !important;">-</td>
										<td  style="border-bottom:0 !important;"><?= ($InvoiceOrder->base_tax_amount)/2 ?></td>
									<?php } ?>
									<td  style="border-bottom:0 !important;"><?= $InvoiceOrder->base_grandtotal ?></td>
								</tr>
							</tfoot>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2"  ></td>
				</tr>
				<tr>
					<td colspan="2" class="p-0 border-0">
						<table class="has_border">
							<tbody>
								<tr>
									<td class="text-center" style="border-top:0 !important;" ><b>Total in words</b></td>
									<td class="bg_color" style="border-top:0 !important;border-right:0 !important;">Taxable Amount</td>
									<td class="text-right bg_color" style="border-top:0 !important;border-left:0 !important;"><?= $InvoiceOrder->base_subtotal ?></td>
								</tr>
								<?php if($InvoiceOrder['stateid'] == $CompanyDetails->state) { ?>
									<tr>
										<td rowspan="3" class="text-center"><?= strtoupper(getIndianCurrencyToWords($InvoiceOrder->base_grandtotal)) ?></td>
										<td style="border-right:0 !important;">Add: IGST</td>
										<td class="text-right" style="border-left:0 !important;"><?= ($InvoiceOrder->base_tax_amount)/2 ?></td>
									</tr>
									<tr>
										<td style="border-right:0 !important;">Add: CGST</td>
										<td class="text-right" style="border-left:0 !important;"><?= ($InvoiceOrder->base_tax_amount)/2 ?></td>
									</tr>
									<?php } else { ?>
									<tr>
										<td rowspan="2" class="text-center"><?= strtoupper(getIndianCurrencyToWords($InvoiceOrder->base_grandtotal)) ?></td>
										<td style="border-right:0 !important;">Add: SGST</td>
										<td class="text-right" style="border-left:0 !important;" ><?= $InvoiceOrder->base_tax_amount ?></td>
									</tr>
								<?php } ?>
								<tr>
									<td class="bg_color" style="border-right:0 !important;">Total Tax</td>
									<td class="text-right bg_color" style="border-left:0 !important;"><?= $InvoiceOrder->base_tax_amount ?></td>
								</tr>
								<tr>
									<td class="text-center"><b>Bank Details</b></td>
									<td class="bg_color" style="border-right:0 !important;" >Total Amount After Tax</td>
									<td class="text-right bg_color" style="border-left:0 !important;"><?= $InvoiceOrder->base_grandtotal ?></td>
								</tr>
								<tr>
									<td rowspan="3">
										<table>
											<tbody>
												<tr>
													<td width="150">Name </td>
													<td><?=$BankDetails->BName?></td>
												</tr>
												<tr>
													<td width="150">Branch </td>
													<td><?=$BankDetails->Branch?></td>
												</tr>
												<tr>
													<td width="150">Acc. Number </td>
													<td><?=$BankDetails->Baccount?></td>
												</tr>
												<tr>
													<td width="150">IFSC </td>
													<td><?=$BankDetails->BIFSC?></td>
												</tr>
											</tbody>
										</table>
									</td>
									<td style="border-right:0 !important;" ></td>
									<td class="text-right" style="border-left:0 !important;">(E & O.E.)</td>
								</tr>
								<tr>
									<td>GST Payable on Reverse Charge</td>
									<td class="text-right bg_color">N.A</td>
								</tr>
								<tr>
									<td colspan="2" class="text-center">
										<p>Certified that the particulars given above are true and correct</p>
										<h5>For <?=$CompanyDetails->company_name?></h5>
									</td>
								</tr>
								<tr>
									<td class="text-center"><b>Terms and Conditions</b></td>
									<td colspan="2"></td>
								</tr>
								<tr class="blank_box">
									<td rowspan="3" style="border-bottom:0 !important;" ><?=$InvoiceOrder->paymentterms?></td>
									<td colspan="2" rowspan="2"></td>
								</tr>
								<tr></tr>
								<tr>
									<td colspan="2" class="text-center" style="border-bottom:0 !important;" ><b>Authorised Signatory</b></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>

	</div>

	

</body>
</html>


