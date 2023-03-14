<style>
    .select2-container .select2-selection--single {
        height: 35px !important;
    }

    #company_details_submit {
        /* background-color: #4CAF50; Green */
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 0 auto;
        display: block;
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
                        <h1 class="m-0">Company</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <button type="button" class="btn btn-block btn-primary" id="back_block_user" style=" width: 100%">BACK</button> -->
                        </ol>
                    </div>
                </div>
            </div>


            <!-- <form class="form-horizontal"> -->
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Company Name</label>
                    <div class="col-md-5">
                        <input class="form-control " type="text" autocomplete="off">
                    </div>

                </div>
                <button type="submit" id="company_details_submit" class="btn btn-success">Submit</button>


            </div>
            

        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid" >

                <table id="product_list" class="table table-bordered table-striped" style="text-align: center;">
                    <thead>
                        <tr>
                            <th style="width:30px">No.</th>
                            <th style="width:30px">Date</th>
                            <th style="width:30px">Company Name</th>
                            <th style="width:30px">Packet</th>
                            <th style="width:30px">Carat</th>
                            <th style="width:30px">Pending Process</th>
                            <th style="width:50px">Broken</th>
                            <th style="width:160px">Price</th>
                            <th style="width:160px">Quantity</th>
                            <th style="width:160px">Final Price</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
        </section>
        <!-- /.content -->
    </div>

</div>
<!-- ./wrapper -->