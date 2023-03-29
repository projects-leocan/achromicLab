<style>
    .select2-container .select2-selection--single {
        height: 35px !important;
    }

    /* table {
    table-layout: fixed;   
    width: 100% !important;
} */


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
        margin-bottom: 8px;
    }
</style>
<div class="wrapper ScrollStyle">


    <div class="content-wrapper">
        <div class="content-header pb-0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-2">
                        <h1 class="m-0">Packet</h1>
                    </div>
                    <div class="col-sm-8 mt-2">
                        <div class="btn-center ">
                            <div class="form-group row ">
                                <div class=" d-flex">
                                    <input type="text" class="form-control " style="width:80%;" id="inputedCompanyName">
                                    <input type="text" name="daterange" class="mx-2 form-control " style="width:80%;" id="selectedCompanyDate" />
                                    <button type="submit" id="filterCompany" class="mx-2 btn btn-primary">Filter</button>
                                    <button type="submit" id="resetDate" class=" btn btn-primary">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <!-- <ol class="breadcrumb float-sm-right"> -->
                        <!-- <div class="d-flex"> -->
                        <button type="button" class="btn btn-block btn-primary" id="Add_packet">Add Packet</button>
                        <form enctype="multipart/form-data" id="import-csv">
                            <!-- <input id="upload" class="mt-1" type=file name="files[]" >  style="display:none"-->
                            <label class="btn btn-block btn-primary mt-1" style="font-weight: normal;">
                                Import Packet <input id="upload" type="file" name="files[]" style="display: none;">
                            </label>
                        </form>

                        <!-- </div> -->
                        <!-- </ol> -->
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
                            <!-- <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th> -->
                            <th></th>
                                <th style="width:10px">No.</th>
                                <th style="width:10px">Packet No.</th>
                                <th style="width:55px">Date</th>
                                <th style="width:100px">Company Name</th>
                                <th style="width:30px">Total piece</th>
                                <th style="width:30px">Total Carat</th>
                                <th style="width:30px">None Process Piece</th>
                                <th style="width:30px">None Process Carat</th>
                                <th style="width:30px">Broken Piece</th>
                                <th style="width:30px"> Broken Carat</th>
                                <!-- <th style="width:30px">Cube</th>
                                <th style="width:30px">Cube Date</th> -->
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
                                <!-- <th></th>
                                <th></th> -->
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