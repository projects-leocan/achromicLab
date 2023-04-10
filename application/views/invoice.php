<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">

   <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript">
        $("#btnPrint").live("click", function () {
            var divContents = $("#download_content").html();
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>DIV Contents</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    </script> -->
   <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> -->

   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
   <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

   <title>Invoice</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" media='all'>
</head>


<body style="font-weight: bold;font-size:20px;">
   <div class="content-wrapper">
      <div class=" row invoice row-printable" style="padding-top: 25px;">
         <div class="col-md-10">
            <div class="panel panel-default plain" id="invoice_content">
               <div class="panel-body p30">
                  <div class="row">
                     <div class="col-lg-6" style="width: 50%;">
                        <div class="invoice-from">
                           <div class="left-div side-logo">
                              <ul class="list-unstyled text-left" style="text-transform: uppercase;">
                                 <h1 style="text-transform: uppercase;color: #d4af37;">achromic lab llp</h1>
                                 <li>32, Ground Floor,akshar dimoand market,</li>
                                 <li>mini bazar varachha road,surat, gujarat - 395004</li>
                                 <li>Call: 99744 27300, 79841 02715</li>
                                 <li>Email: achromiclabllp1974@gmail.com</li>
                                 <li>GSTIN 24ABXFA4579N1ZI</li>
                              </ul>
                           </div>
                           <!-- </ul> -->
                        </div>
                     </div>
                     <div class="col-lg-6" style="width: 50%;">
                        <ul class="list-unstyled text-right">
                           <div class="invoice-logo">
                              <img src="https://leocan.co/subFolder/achromicLab/dist/img/logo1.png" alt="Invoice logo">
                           </div>
                        </ul>

                     </div>
                     <div class="col-lg-12">
                        <div class="invoice-details mt25">
                           <div class="well"
                              style="min-height: 20px; padding: 19px; margin-bottom: 20px;   background-color: #f5f5f5;
                                    border: 1px solid #e3e3e3;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);">
                              <!-- <ul class="list-unstyled mb0">
                                 <li><strong>From:</strong> Anil Gems</li>
                                 <li><strong>Challan No:</strong> #936988</li>
                                 <li><strong>Invoice Date:</strong> 23/03/2023</li>
                                 </ul> -->
                              <table class="w-100">
                                 <tr>
                                    <td width="300px">From : <span id="invoice_cname"></span></td>
                                    <td>Mo:</td>
                                 </tr>
                                 <tr>
                                    <td>Date: <span id="invoice_date"></span></td>
                                    <td>Challan No: <span id="invoice_cno"></span></td>
                                    <td>Ref No:</td>
                                 </tr>
                              </table>
                           </div>
                        </div>
                        <div class="invoice-items">
                           <!-- <div class="table-responsive"> -->
                           <table class="table table-bordered" id="invoice_table">
                              <thead>
                                 <tr>
                                    <th class="per70 text-center">No.</th>
                                    <th class="per5 text-center">Pcs</th>
                                    <th class="per25 text-center">Carat</th>
                                    <th class="per70 text-center">None Process Piece</th>
                                    <th class="per70 text-center">None Process Carat</th>
                                    <th class="per25 text-center">Rate</th>
                                    <th class="per25 text-center">Amount</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <!-- <tr>
                                    <td class="text-center">1</td>
                                    <td></td>
                                    <td>1024MB Cloud 2.0 Server</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center">$25.00 USD</td>
                                    <td></td>
                                    <td></td>
                                 </tr>
                                 <tr>
                                    <td class="text-center"> 1</td>
                                    <td></td>
                                    <td>1024MB Cloud 2.0 Server</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center">$25.00 USD</td>
                                    <td></td>
                                    <td></td>
                                 </tr>
                                 <tr>
                                    <td class="text-center">1</td>
                                    <td></td>
                                    <td>1024MB Cloud 2.0 Server</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center">$25.00 USD</td>
                                    <td></td>
                                    <td></td>
                                 </tr> -->
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <th class="text-right">Sub Total:</th>
                                    <th class="text-center" id="sub_total_pcs"></th>
                                    <th class="text-center" id="sub_total_Weight"></th>
                                    <th class="text-center" id="none_process_piece"></th>
                                    <th class="text-center" id="none_process_caret"></th>
                                    <th class="text-center" id="rate"></th>
                                    <th class="text-center" id="amount"></th>
                                 </tr>
                                 <tr>
                                    <th class="text-right">Total:</th>
                                    <th class="text-center" id="total_pcs"></th>
                                    <th class="text-center" id="total_Weight"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                 </tr>
                                 <!-- <tr>
                                    <th class="text-right">Add/Less:</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                 </tr>
                                 <tr>
                                    <th class="text-right">Net Amount:</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                 </tr> -->
                                 <!-- <tr>
                                    <th colspan="7" class="text-left">Total Amount In Rupees:</th>
                                 </tr> -->
                                 <tr>
                                    <td colspan="2" class="text-center">Authorised Sign</td>
                                    <td colspan="2" class="text-center">Inssuer Sign</td>
                                    <td colspan="2" class="text-center">Delivery Sign</td>
                                    <td colspan="1" class="text-center">Customer Sign</td>
                                 </tr>
                                 <tr>
                                    <td colspan="2" class="text-center">&nbsp;</td>
                                    <td colspan="2" class="text-center">&nbsp;</td>
                                    <td colspan="2" class="text-center">&nbsp;</td>
                                    <td colspan="1" class="text-center">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="7" class="text-left">HTHP પ્રોસેસ દરમિયાન ઉચ્ચ દબાણ ના
                                       પરિણામે ડાયમંડ માં નુકશાન થવાની પુરેપુરી સંભાવના છે. આ સ્થિતિથીની
                                       સંભવનના ૧૦૦ એ ૩% છે.HTHP દરમિયાન માલ ના તમામ નુકસાન થવાના જોખમને
                                       ગાહક દ્વારા પ્રતિબંધિત કરવામાં આવશે. ર) માલ સાથેની બીલ ની બે
                                       નકલોમાંથી,કૃપા કરી ને તમારા દ્વારા પ્રાપ્ત થયેલી કૃતી ના પુરાવા રૂપે
                                       એક સહી કરેલ નકલ પરત કરો.એકવાર માલ ની રસીદ બિલ પર હસ્ત્રક્ષાર કરીને
                                       પુષ્ટિ થઇ ગયા પછી કોઈ દાવા.દલીલો અથવા ફરિયાદો જેમકે
                                       વજન,શુદ્ધતા,ચારણી,કેટેગરી અને હિરા ની ગુણવતા ને ધ્યાન માં લેવામાં
                                       આવશે નહિ.3)બધા દાવાઓ સુરત અધિકારક્ષેત્રને આધિન છે. </th>
                                 </tr>
                                 <tr>
                                    <td colspan="7" class="text-right">Original/Duplicate</th>
                                 </tr>
                                 <!-- <tr>
                                    <th colspan="2" class="text-right">20% VAT:</th>
                                    <th class="text-center">12</th>
                                    <th class="text-center">$47.40 USD</th>
                                    </tr>
                                    <tr>
                                    <th colspan="2" class="text-right">Credit:</th>
                                    <th class="text-center"></th>
                                    <th class="text-center">$00.00 USD</th>
                                    </tr>
                                    <tr>
                                    <th colspan="2" class="text-right">Total:</th>
                                    <th class="text-center">$284.4.40 USD</th>
                                    </tr> -->
                              </tfoot>
                           </table>
                           <!-- </div> -->
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="invoice-footer mt25">
               <p class="text-center"> <a href="#" id="download_btn" class="btn btn-default ml15"
                     onclick="printDiv('invoice_content')"><i class="fa fa-download mr5"></i> Download</a></p>
               <p class="text-center"> <a href="#" id="back_to_pkg_btn" class="btn btn-default ml15"><i
                        class="fa fa-arrow-left mr5"></i> back</a></p>
            </div>
         </div>
      </div>
   </div>
   <!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
   <!-- <script src="js/jsPDF/dist/jspdf.umd.js"></script>
      <script type="text/javascript">
      
      var doc = new jsPDF();
      var specialElementHandlers = {
          '#editor': function (element, renderer) {
              return true;
          }
      };
      
      $('#download_btn').click(function () {
          doc.fromHTML($('#download_content').html(), 15, 15, {
              'width': 170,
                  'elementHandlers': specialElementHandlers
          });
          doc.save('sample-file.pdf');
      });
      
      
      </script> -->

   <script>
      function printDiv(divName) {
         var printContents = document.getElementById(divName).innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.print();
         document.body.innerHTML = originalContents;
         window.location.reload();
      }
   </script>
</body>

</html>