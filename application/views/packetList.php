<style>
.select2-container .select2-selection--single {
    height: 35px !important;
}

/* table {
    table-layout: fixed;   
    width: 100% !important;
} */

table.dataTable th:nth-child(1) {
  width: 20px;
  max-width:20px;
}
table.dataTable th:nth-child(2) {
  width: 20px;
  max-width:20px;
}
table.dataTable th:nth-child(3) {
  width: 10px;
  max-width:10px;
}

.ui-autocomplete {
    cursor: pointer;
    /* height: 120px; */
    /* overflow-y: scroll; */
}

div.dt-buttons {
    /* position: absolute; */
    position: relative;
    float: right;
}

#packet_list_length {
    position: absolute;

}

.buttons-pdf {
    color: #fff;
    background-color: #0062cc !important;
    border-color: #84b8f0 !important;
    margin-left: 1rem !important;
    display: inline-block;
    font-weight: 400;
    color: #fff;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    margin-bottom:8px;
}
</style>
<div class="wrapper ScrollStyle">


    <div class="content-wrapper">
    <div class="content-header">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-sm-6">
                        <h1 class="m-0">Packet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <button type="button" class="btn btn-block btn-primary" id="Add_packet">Add Packet</button>
                            <!-- <button type="button" class="btn btn-block btn-primary" id="back_to_packet"
                                style=" width: 100%">Back</button> -->
                        </ol>
                    </div>

                    <div class="btn-center w-100">
                        <div class="form-group row ">
                            <div class=" d-flex">
                                <input type="text" class="form-control " style="width:80%;" id="inputedCompanyName">
                                <input type="text" name="daterange" class="mx-2 form-control " style="width:80%;"
                                    id="selectedCompanyDate" />
                                <button type="submit" id="filterCompany" class="mx-2 btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main content -->
        <section class="content ">
            <div class="container-fluid">
                <div class="table-responsive" id="importPDF">

                    <table id="packet_list" class="table table-bordered table-striped" style="text-align: center;">
                        <thead>
                            <tr>
                                <th style="width:30px">No.</th>
                                <th style="width:30px">Packet No.</th>
                                <th style="width:30px">Date</th>
                                <th style="width:100px">Company Name</th>
                                <th style="width:30px">Total piece</th>
                                <th style="width:30px">Total Carat</th>
                                <th style="width:30px">None Process</th>
                                <th style="width:30px">None Process Qty</th>
                                <th style="width:30px">Broken</th>
                                <th style="width:30px">Broken Qty</th>
                                <th style="width:30px">Cube</th>
                                <th style="width:30px">Cube Date</th>
                                <th style="width:30px">Final Carat</th>
                                <!-- <th style="width:30px">Invoice</th> -->
                                <th style="width:30px">Action</th>
                                <!-- <th style="width:30px">Total Amount</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align:right"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

</div>
<!-- ./wrapper -->