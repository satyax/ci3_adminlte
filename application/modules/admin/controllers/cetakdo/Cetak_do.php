<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_do extends Admin_Controller {
  
  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_builder');
    $this->load->library('PHPExcel/Classes/PHPExcel');
    $this->load->library('Admin/wawan_lib_admin', true);
  }
  
  public function index() {
    //$this->printInv();
    
    $form1 = $this->form_builder->create_form('admin/cetakdo/cetak_do/printInv');
    $this->mViewData['form1'] = $form1;
    $this->mViewData['message'] = '';
    if ($this->input->get('message') == 'no_data') $this->mViewData['message'] = $this->input->get('order_number').' does not exist!';
    if ($this->input->get('message') == 'db_tunnel_connection_failed') $this->mViewData['message'] = 'Connection to DB Tunnel failed!';
    $this->mTitle = "Cetak Invoice";
    $this->render('cetakdo/entry_form');
  }
  
  public function printInv() {
    
    set_time_limit(0);
    ini_set('memory_limit', '2048M');
    
    $order_number = $this->db->escape(rtrim(ltrim($this->input->post('order_number'))));
    $this->load->model('Trs_invoice_print_model','trs_invoice_print');
    
    // open connection to oshop production database
    $oshop = $this->load->database('oshop', TRUE);
    $connected = $oshop->initialize();
    if (!$connected) {
      redirect('admin/cetakdo/cetak_do?message=db_tunnel_connection_failed&order_number='.$order_number);
    } else {
      $oshop = $this->load->database('oshop', TRUE);
    }
    
    // 400000024
    $query = $oshop->query("
        SELECT DISTINCT
        so.entity_id
        , so.increment_id AS order_number
        , so.status
        , sp.method 
        , so.customer_id
        , CONCAT(so.customer_firstname, ' ', so.customer_lastname) AS customer_name
        , ce.email customer_email
        , IF(cei.value = 1, 'MALE', IF(cei.value = 2, 'FEMALE', NULL)) AS customer_gender
        , date(ced.value) AS customer_dob
        , sa.street
        , sa.city
        , dr.default_name AS province
        , sa.kecamatan
        , sa.kelurahan
        , sa.postcode
        , sa.telephone
        , sa.fax
        , DATE_FORMAT( IF(soc.created_at IS NOT NULL, DATE_ADD(soc.created_at, INTERVAL 7 HOUR), DATE_ADD(so.created_at, INTERVAL 7 HOUR)) , '%d-%m-%Y') as order_date
        , CONCAT(u.firstname, ' ', u.lastname) AS order_created_by
        , aw.delivery_date  AS delivery_date_request
        , DATE_ADD(shc.created_at, INTERVAL 7 HOUR) AS `payment_date`
        , DATE_ADD(si.created_at, INTERVAL 7 HOUR) AS `invoice_date` 
        , if(shc.entity_name = 'shipment', '',CONCAT(uc.firstname, ' ', uc.lastname)) AS invoice_created_by
        , DATE_ADD(scm.created_at, INTERVAL 7 HOUR) AS `creditmemo_date`
        , so.kurir AS kurir_name
        , DATE_ADD(ss.shipped_date, INTERVAL 7 HOUR) AS shipped_date
        , ss.recipient_name
        , so.`base_subtotal_incl_tax`
        , so.shipping_amount
        , so.shipping_tax_amount
        , so.shipping_incl_tax
        , so.tax_amount
        , so.discount_amount
        , so.coupon_code
        , so.base_grand_total
        , so.total_invoiced
        , so.total_refunded
        , so.total_canceled
        , soi.sku 
        , soi.name AS product_name
        , soi.base_price AS unit_price
        , soi.qty_ordered
        , soi.qty_invoiced 
        , soi.qty_shipped
        , soi.qty_canceled
        , soi.qty_refunded
        , soi.tax_amount AS tax_amount_item
        , soi.discount_amount AS discount_amount_item
        , soi.row_total
        , (soi.row_total - soi.discount_amount) AS sub_total
        , DATE_ADD(so.updated_at, INTERVAL 7 HOUR) AS last_update
        , soi.item_id AS soi_item_id
        , so.customer_note 
        , sp.ccod_bank
        , sp.ccod_installment
      FROM
        sales_flat_order so
        LEFT JOIN customer_entity ce ON ce.entity_id=so.customer_id
        LEFT JOIN customer_entity_int cei on cei.entity_id=ce.entity_id AND cei.attribute_id=18
        LEFT JOIN customer_entity_datetime ced on ced.entity_id=ce.entity_id AND ced.attribute_id=11
        LEFT JOIN sales_flat_order soc ON soc.increment_id=so.original_increment_id
        INNER JOIN sales_flat_order_item soi ON so.entity_id = soi.order_id
        LEFT OUTER JOIN sales_flat_order_address sa ON so.entity_id = sa.parent_id AND sa.address_type = 'shipping'
        LEFT OUTER JOIN directory_country_region dr ON sa.region_id = dr.region_id
        LEFT OUTER JOIN sales_flat_order_payment sp ON so.entity_id = sp.parent_id
        LEFT OUTER JOIN sales_flat_order_status_history shc ON so.entity_id = shc.parent_id
        LEFT OUTER JOIN admin_user u ON shc.`user_id` = u.user_id
        LEFT OUTER JOIN aw_deliverydate_delivery aw ON so.entity_id = aw.order_id        
        LEFT OUTER JOIN admin_user uc ON shc.`user_id` = uc.user_id
        LEFT OUTER JOIN `sales_flat_invoice` si ON so.entity_id = si.order_id
        LEFT OUTER JOIN sales_flat_creditmemo scm ON so.entity_id = scm.order_id
        LEFT OUTER JOIN sales_flat_shipment ss ON so.entity_id=ss.order_id
        LEFT OUTER JOIN sales_flat_shipment_item ssi ON soi.item_id=ssi.order_item_id AND ss.entity_id=ssi.parent_id
      WHERE
        so.increment_id = $order_number
      GROUP BY
        so.increment_id, soi.sku, soi.qty_ordered
      ORDER BY 
        so.increment_id
    ");
    
    $i = 1;
    $rowProduk1 = 13; $rowProduk2 = 47; $rowProduk3 = 81;
    $fileType = 'Excel5';
    $objReader = PHPExcel_IOFactory::createReader($fileType);
    $objPHPExcel = $objReader->load(FCPATH.'assets/dist/oshop/templateInvoice.xls');
    $objPHPExcel->setActiveSheetIndex(0);
    
    $styleAligntmentHorizontalLeft = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
      )
    );
    
    foreach ($query->result() as $row) {
      
      // isi yang diisi cuma sekali
      if ($i==1) {
        
        if (substr($order_number, 0, 2) == "'4") {
          $payment_method = 'Cash Leasing';
        } else {
          $payment_method = $row->method;
        }
        
        $objPHPExcel->getActiveSheet()->setCellValue('D6', $row->customer_name)
                                      ->setCellValue('D40', $row->customer_name)
                                      ->setCellValue('D74', $row->customer_name)
                                      ->setCellValue('D7', $row->street)
                                      ->setCellValue('D41', $row->street)
                                      ->setCellValue('D75', $row->street)
                                      ->setCellValue('D8', $row->city)
                                      ->setCellValue('D42', $row->city)
                                      ->setCellValue('D76', $row->city)
                                      ->setCellValue('D9', 'T: '.$row->telephone)
                                      ->setCellValue('D43', 'T: '.$row->telephone)
                                      ->setCellValue('D77', 'T: '.$row->telephone)
                                      ->setCellValue('D10', 'F: '.$row->fax)
                                      ->setCellValue('D44', 'F: '.$row->fax)
                                      ->setCellValue('D78', 'F: '.$row->fax)
                                      ->setCellValue('I9', $payment_method)
                                      ->setCellValue('I43', $payment_method)
                                      ->setCellValue('I77', $payment_method)
                                      ->setCellValue('I6', $row->order_number)
                                      ->setCellValue('I40', $row->order_number)
                                      ->setCellValue('I74', $row->order_number)
                                      ->setCellValue('I7', $row->order_date)
                                      ->setCellValue('I41', $row->order_date)
                                      ->setCellValue('I75', $row->order_date)
                                      ->setCellValue('I8', $row->order_created_by)
                                      ->setCellValue('I42', $row->order_created_by)
                                      ->setCellValue('I76', $row->order_created_by)
                                      ->setCellValue('I22', $row->base_subtotal_incl_tax)
                                      ->setCellValue('I56', $row->base_subtotal_incl_tax)
                                      ->setCellValue('I90', $row->base_subtotal_incl_tax)
                                      ->setCellValue('I23', $row->discount_amount)
                                      ->setCellValue('I57', $row->discount_amount)
                                      ->setCellValue('I91', $row->discount_amount)
                                      ->setCellValue('I24', $row->base_grand_total)
                                      ->setCellValue('I58', $row->base_grand_total)
                                      ->setCellValue('I92', $row->base_grand_total);
      }
      
      // isi detail produk nya
      if ($i>8) {
        $objPHPExcel->getActiveSheet()->insertNewRowBefore($rowProduk1,1);
        $objPHPExcel->getActiveSheet()->mergeCells('C'.$rowProduk1.':E'.$rowProduk1);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$rowProduk1.':E'.$rowProduk1)->applyFromArray($styleAligntmentHorizontalLeft);
        
        $rowProduk2 = $rowProduk2 + 1;
        $objPHPExcel->getActiveSheet()->insertNewRowBefore($rowProduk2,1);
        $objPHPExcel->getActiveSheet()->mergeCells('C'.$rowProduk2.':E'.$rowProduk2);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$rowProduk2.':E'.$rowProduk2)->applyFromArray($styleAligntmentHorizontalLeft);
        
        $rowProduk3 = $rowProduk3 + 1;
        $objPHPExcel->getActiveSheet()->insertNewRowBefore($rowProduk3,1);
        $objPHPExcel->getActiveSheet()->mergeCells('C'.$rowProduk3.':E'.$rowProduk3);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$rowProduk3.':E'.$rowProduk3)->applyFromArray($styleAligntmentHorizontalLeft);
      }
      
      $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowProduk1, $row->sku)
                                    ->setCellValue('B'.$rowProduk2, $row->sku)
                                    ->setCellValue('B'.$rowProduk3, $row->sku)
                                    ->setCellValue('F'.$rowProduk1, $row->qty_ordered)
                                    ->setCellValue('F'.$rowProduk2, $row->qty_ordered)
                                    ->setCellValue('F'.$rowProduk3, $row->qty_ordered)
                                    ->setCellValue('C'.$rowProduk1, $row->product_name)
                                    ->setCellValue('C'.$rowProduk2, $row->product_name)
                                    ->setCellValue('C'.$rowProduk3, $row->product_name)
                                    ->setCellValue('G'.$rowProduk1, $row->unit_price)
                                    ->setCellValue('G'.$rowProduk2, $row->unit_price)
                                    ->setCellValue('G'.$rowProduk3, $row->unit_price)
                                    ->setCellValue('I'.$rowProduk1, $row->row_total)
                                    ->setCellValue('I'.$rowProduk2, $row->row_total)
                                    ->setCellValue('I'.$rowProduk3, $row->row_total);
      
      $rowProduk1++; $rowProduk2++; $rowProduk3++; $i++;
    }    
    
    if ($i>1) {
      
      $data = [
        'order_number' => str_replace("'", "", $order_number),
        'username' => $this->mUser->username,
      ];
      $result = $this->trs_invoice_print->insert($data);
      
      // Redirect output to a client’s web browser (PDF)
      header('Content-Type: application/vnd.ms-excel');
      $guid = $this->wawan_lib_admin->gen_uuid();
      header('Content-Disposition: attachment; filename="Inv-'.$guid.'.xls"');
      header('Cache-Control: max-age=0');
      
      ob_clean();
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
      $objWriter->save('php://output');
    } else {
      redirect('admin/cetakdo/cetak_do?message=no_data&order_number='.$order_number);
    }
    
  }
  
}
