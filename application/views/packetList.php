<style>

html {
  scroll-behavior: auto;
}

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

div.dataTables_scrollFoot>.dataTables_scrollFootInner>table {
    margin-top: 0 !important;
    border-top: none;
    height: 10px;
}

table.dataTable thead tr>.dtfc-fixed-left,
table.dataTable thead tr>.dtfc-fixed-right,
table.dataTable tfoot tr>.dtfc-fixed-left,
table.dataTable tfoot tr>.dtfc-fixed-right {
    top: 0;
    bottom: 0;
    z-index: 3;
    background-color: #F4F6F9 !important;
}

/* Change the background color of the striped rows */
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #E8E9EC;
}

.table-striped tbody tr:nth-of-type(even) {
    background-color: #F4F6F9;
}

/* set the background color for odd rows in the fixed column */
table.dataTable tbody tr:nth-child(odd)>.dtfc-fixed-left,
table.dataTable tbody tr:nth-child(odd)>.dtfc-fixed-right {
    background-color: #E8E9EC;
}

/* set the background color for even rows in the fixed column */
table.dataTable tbody tr:nth-child(even)>.dtfc-fixed-left,
table.dataTable tbody tr:nth-child(even)>.dtfc-fixed-right {
    background-color: #F4F6F9;
}

div.dataTables_wrapper div.dataTables_info {
    padding-top: 0.85em;
    display: inline-block;
}

.btn-container {
    padding-top: 1.85em;
    float: right;
    padding-right: 10px;
    margin-top: 10px;
    position: relative;
    bottom: 1.4rem;
}

.btn-container button {
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    color: #333;
    padding: 6px 12px;
    font-size: 14px;
    cursor: pointer;
    margin-bottom: 20px;
}

.btn-container button:hover {
    background-color: #e9e9e9;
}

.select-container {
    position: relative;
    display: inline-block;
}

.select-container select {
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    color: #333;
    padding: 6px 30px 6px 12px;
    font-size: 14px;
    cursor: pointer;
}

.select-icon {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    font-size: 12px;
    color: #666;
    pointer-events: none;
}

.dataTables_info {
    padding-top: 0.85em;
    padding-left: 0.85rem;
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
                                    <input type="text" name="daterange" class="mx-2 form-control " style="width:80%;"
                                        id="selectedCompanyDate" />
                                    <button type="submit" id="filterCompany"
                                        class="mx-2 btn btn-primary">Filter</button>
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
                <div class="table-responsive table-container" id="importPDF">

                    <table id="packet_list" class="table table-bordered table-striped"
                        style="width:100%;text-align: center;">
                        <thead>
                            <tr>

                                <span class="loader"></span>
                                <th style="width:10px"><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                                <!-- <th></th> -->
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
                                <th style="width:30px">Challan No</th>
                                <th style="width:30px">Delivery Date</th>
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
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>

        <div class="btn-container">
            <button id="prev-button" type="button" class="dataTables_info">&lt;</button>
            <button id="next-button" type="button" class="mx-1">&gt;</button>
            <div class="select-container">
                <select class="form-select form-select-lg" name="" id="rowPerPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                </select>
                <span class="select-icon">&#9660;</span>
            </div>
        </div>


        <div class="dataTables_info" id="packet_list_info" role="status" aria-live="polite">
            Showing <span id="startTO">1</span> to <span id="endTo">00</span> of <span id="total_count">00</span> entries</div>
        

    </div>

</div>
<!-- ./wrapper -->