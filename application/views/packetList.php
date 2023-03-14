<style>
    .select2-container .select2-selection--single {
        height: 35px !important;
    }

</style>
<div class="wrapper ScrollStyle">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2" id="manage">
                    <div class="col-sm-6">
                        <h1 class="m-0">Packet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <button type="button" class="btn btn-block btn-primary" id="Add_packet" style=" width: 100%">Add Packet</button>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <label for="category_name" class="col-sm-2 col-form-label">Company Name</label>
                    <div class="col-sm-10">
                        <input type="text" value="" class="form-control " id="company_name"
                            placeholder="Enter Company Name">
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