<style>
.select2-container .select2-selection--single {
    height: 35px !important;
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
    margin-left: 5rem !important;
    display: inline-block;
    font-weight: 400;
    color: #fff;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
}
</style>
<div class="wrapper ScrollStyle">


    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="col-12 row " style="margin: 0 auto 0;!important">
                    <div class="col-sm-1">
                        <h1 class="side-h1">Packet</h1>
                    </div>

                    <div class="btn-center">
                        <div class="form-group row ">
                            <div class=" d-flex">
                                <input type="text" class="form-control " style="width:80%;" id="inputedCompanyName">
                                <input type="date" class="mx-2 form-control " style="width:80%;"
                                    id="selectedCompanyDate">
                                <button type="submit" id="filterCompany" class="mx-2 btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2 float-sm-right">
                        <ol class="float-sm-right">
                            <button type="button" class="btn btn-block btn-primary" id="Add_packet"
                                style=" width: 100%">Add Packet</button>
                        </ol>
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
                                <th style="width:30px">Broken</th>
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