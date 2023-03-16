<style>
.select2-container .select2-selection--single {
    height: 35px !important;
}

.ui-autocomplete {
    cursor: pointer;
    /* height: 120px; */
    /* overflow-y: scroll; */
}
</style>
<div class="wrapper ScrollStyle">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Packet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <button type="button" class="btn btn-block btn-primary" id="Add_packet"
                                style=" width: 100%">Add Packet</button>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- <div class="form-group row">
                <label for="inputedCompanyName" class="mx-3 col-form-label">Company Name</label>
                <input class="form-control" type="text" id="inputedCompanyName"
                        placeholder="Company name" style="width:50%;">

                </select>

            </div> -->


            <div class="container">
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Company Name
                        <span style="color:red;"> * </span>
                    </label>
                    <div class="col-sm-10 d-flex">
                        <input type="text" class="form-control mr-2" style="width:50%;" id="inputedCompanyName">
                        <button type="submit" id="filterCompany" class="mx-2 btn btn-primary" style="padding:0px 30px;">Filter</button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="table-responsive">
                    <table id="packet_list" class="table table-bordered table-striped" style="text-align: center;">
                        <thead>
                            <tr>
                                <th style="width:30px">No.</th>
                                <th style="width:30px">Date</th>
                                <th style="width:100px">Company Name</th>
                                <th style="width:100px">Packet Number</th>
                                <th style="width:30px">Quantity</th>
                                <th style="width:30px">Carat</th>
                                <th style="width:100px">Pending Process</th>
                                <th style="width:30px">Broken</th>
                                <th style="width:30px">Price</th>
                                <th style="width:30px">Invoice</th>
                                <!-- <th style="width:30px">Total Amount</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

</div>
<!-- ./wrapper -->