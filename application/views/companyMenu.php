<div class="wrapper ScrollStyle">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Company</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        </ol>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <label for="category_name" class="col-sm-2 col-form-label">Company Name
                        <span style="color:red;"> * </span></label>
                    <div class="col-sm-10">
                        <input type="text" value="" class="form-control " id="company_name"
                            placeholder="Enter Company Name">
                    </div>
                </div>
                <button type="submit" id="company_submit" class="common-btn-padding btn btn-success d-block m-auto "> Add </button>
            </div>

        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid" >
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