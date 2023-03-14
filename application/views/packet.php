<style>
    .select2-container .select2-selection--single {
        height: 35px !important;
    }


    #packet_details_submit {
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
                        <h1 class="m-0">Packet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <button type="button" class="btn btn-block btn-primary" id="back_to_packet" style=" width: 100%">Back</button>
                        </ol>
                    </div>
                </div>
            </div>
               
            </div>

            <div class="card-body">

                <div class="form-group row">
                    <label for="selected_date" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="selected_date">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Company Name</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" autocomplete="off"
                            list="company_name" id="inputedCompanyName">
                            <datalist id="company_name"></datalist>
                        
                    </div>
                </div>

                <div class="form-group row">
                    <label for="number_of_packet" class="col-sm-2 col-form-label">Packet Number</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="number_of_packet" placeholder="Add Packet number">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="number_of_qty" class="col-sm-2 col-form-label">Quantity</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="number_of_qty" placeholder="Quantity">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="number_of_carat" class="col-sm-2 col-form-label">Total Carat</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="number_of_carat" placeholder="Add Carat">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pending_process" class="col-sm-2 col-form-label">Pending Process</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="number" id="pending_process_qty" class="form-control" placeholder="Add Quantity"/>
                            <span class="input-group-addon">-</span>
                            <input type="number" id="pending_process_carat" class="form-control" placeholder="Carat"/>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="broken" class="col-sm-2 col-form-label">Broken</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" id="broken_qty" class="form-control" placeholder="Add Quantity"/>
                            <span class="input-group-addon">-</span>
                            <input type="text" id="broken_carat" class="form-control" placeholder="Carat"/>
                        </div>
                    </div>
                </div>

                


                <div class="form-group row">
                    <label for="price_per_carat" class="col-sm-2 col-form-label">Price Per Carat</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="price_per_carat" placeholder="Price per Carat">
                    </div>
                </div>



                <button type="submit" id="packet_details_submit" class="btn btn-success">Submit</button>

            </div>
        </div>


    </div>

</div>