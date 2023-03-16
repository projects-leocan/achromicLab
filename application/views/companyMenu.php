<div class="wrapper ScrollStyle">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <div class="content-header mt-2">
            <!-- <div class="container-fluid">
                <div class="row ">
                    <div class="col-sm-6">
                        <h1 class="m-0">Company</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        </ol>
                    </div>
                </div>
            </div> -->

            <div class="container">
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Company Name
                        <span style="color:red;"> * </span>
                    </label>
                    <div class="col-sm-10 d-flex">
                        <input type="text" value="" class="form-control mr-2" id="company_name"
                            placeholder="Enter Company Name" style="width:50%;">
                        <button type="submit" id="company_submit" class="mx-2 btn btn-success" style="padding:0px 30px;">Add</button>
                    </div>
                </div>
            </div>


        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="table-responsive">
                    <table id="category_list" class="table table-bordered table-striped" style="text-align: center;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Company Name</th>
                                <th>Actions</th>
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