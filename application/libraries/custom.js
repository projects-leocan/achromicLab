// live 
const base_url = 'https://leocan.co/subFolder/achromicLab/';

// local 
// const base_url = 'http://localhost/achromicLab/';
let prevPacketData = [];
let currentPage = 1;
let isFilter = false;
let isSearch = false;
// ready function 
$(() => {
    localStorage.removeItem("lastPacketId")
    localStorage.removeItem("pageLastIndex");
    localStorage.removeItem("startDate");
    localStorage.removeItem("endDate");
    localStorage.removeItem("searchText");
    // localStorage.removeItem("FilterSelecteCompanyName");
    // localStorage.removeItem("FilterSelecteCompanyID");
    // localStorage.removeItem("invoice_company");
    //fetchAllComapany();
    if (window.location.href == base_url + 'company') {
        fetchAllComapany();
    }
    if (window.location.href == base_url + 'invoice_form') {
        BindInvoiceData();
    }
    if (window.location.href == base_url + 'packet') {
        $('#inputedCompanyName').val("All Company");
        BindControls();
        fetchPacketData();
        getCount();                   
        // getPacketSumWithFilter();
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            // dateFormat: 'dd/mm/yyyy',
            autoUpdateInput: false,
            autoclose: true,
            clearBtn: true,
            autoClear: false,
            alwaysShowCalendars: false,
            // autoclose: true,
            // autoApply:true,
            locale: {
                // cancelLabel: 'Clear',
                clearBtn: true,
                value: [null, null]
            }

        }, function (start, end, label) {
            localStorage.setItem("startDate", start.format('YYYY/MM/DD'));
            localStorage.setItem("endDate", end.format('YYYY/MM/DD'));
            $("#selectedCompanyDate").val(`${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
        });

        $("#selectedCompanyDate").val("DD/MM/YYYY - DD/MM/YYYY");
    }

    if (window.location.href == base_url + 'packet_form') {
        fetchPacketData();
        if (localStorage.getItem("packet_id") != "" && localStorage.getItem("packet_id") != null) {
            bindPacketData();
           
        }
        else{
            autoIncPacketNum();
        }
        $("#selected_date").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: new Date()
            // 2023-03-21
        });
        $("#selected_date").datepicker('setDate', new Date());
        BindControls();


    }
})


function showLoader(){
    $(".loader").show();
}
function hideLoader(){
    $(".loader").hide();
}


$('#upload').change((e) => {
    
    handleFileSelect(e);
    //document.getElementById('upload').addEventListener('change', handleFileSelect, false);
})

$("#total_number_of_carat").on("input", () => {
    finalPrice()
})

$("#pending_process_carat").on("input", () => {
    finalPrice()
})

function finalPrice() {
    let totalCarat = $("#total_number_of_carat").val();
    let broken_qty_carat = $("#pending_process_carat").val();
    let fixedNum = (totalCarat - broken_qty_carat).toFixed(2);

    $("#price_per_carat").val(fixedNum);
}

let company_name_arr2 = [];
let company_name_arr3 = [];
let company_id = [];
// import csv 
var ExcelToJSON = function () {

    this.parseExcel = function (file) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
            workbook.SheetNames.forEach(function (sheetName,index) {
                if(index == 0)
                {
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                var json_object = JSON.stringify(XL_row_object);
                let json_data = JSON.parse(json_object);
                autoIncPacketNumExport(json_data);
                }
                

               
            })
        };

        reader.onerror = function (ex) {
            console.log(ex);
        };

        reader.readAsBinaryString(file);
    };
};

function handleFileSelect(evt) {
    var files = evt.target.files; 
    var xl2json = new ExcelToJSON();
    let jsonData = xl2json.parseExcel(files[0]);

}

function insertNewCompanies(insertNewCompanies) {
    let newCompanyNames = JSON.stringify(insertNewCompanies)
    let response = new FormData();
    response.append("company_names", newCompanyNames)
    $.ajax({
        url: base_url + 'Dashboard/newCompanies',
        type: "POST",
        data: response,
        processData: false,
        contentType: false,
        beforeSend: function (response) { },
        complete: function (response) {
        },
        error: function (response) {
            // alert('Something went wrong while fatching packet ')
        },
        success: function (response) {
            // console.log("data:",data);
        }
    })
}

function sendJSON(data) {
    
    var jsonString = JSON.stringify(data);
    
    let response = new FormData();
    response.append("data", jsonString);

    $.ajax({
        url: base_url + 'Dashboard/importCSV',
        type: "POST",
        data: response,
        processData: false,
        contentType: false,
        beforeSend: function (response) { },
        complete: function (response) {
        },
        error: function (response) {
            Swal.fire({
                title: '',
                text: `Please choose valid xlsx file.`,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if (result.isConfirmed) {
                   //fetchPacketData();
                }
            })
        },
        success: function (response) {
            response= JSON.parse(response);
            if (response.success) {
                fetchPacketData();
                Swal.fire({
                    title: '',
                    text: `${response.message}`,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    if (result.isConfirmed) {
                       //fetchPacketData();
                    }
                })
              
            }
            else
            {
                Swal.fire({
                    title: '',
                    text: `${response.message}`,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    if (result.isConfirmed) {
                       //fetchPacketData();
                    }
                })
            }
            // console.log("data:",data);
        }
    })
}

// popup function...
function showAlert(alertText) {
    $.alert({
        title: '',
        theme: 'modern',
        // typeAnimated: true,
        closeIcon: false,
        // backgroundDismiss: false,
        // backgroundDismissAnimation: '',
        // animation: 'news',
        // closeAnimation: 'news',
        content: alertText,
        buttons: {
            ok: {
                text: "Ok",
                btnClass: 'btn-alert',
                action: function () {
                    // getCategoryList();
                    // window.location.reload();
                    // hidedotLoader();
                }
            },
        }
    });
}


// creting function for packet menu
$('#packet_menu').click((e) => {
    window.location = 'packet';
})

// creting function for company menu
$('#company_menu').click((e) => {
    window.location = 'company';
})

$('#Add_packet').click((e) => {
    window.location = 'packet_form';
})

$('#back_to_packet').click((e) => {
    localStorage.setItem("packet_id", "");
    window.location = 'packet';
})


// +++++++++++++++++++++++++ Sign In +++++++++++++++++++++++++++++++++

$("#signIn").on('click', (e) => {
    // console.log("Clicked On sign In");
    validUserSignIn();
});


const validUserSignIn = () => {
    let email = $("#signin_user_email").val();
    let password = $('#signin_user_password').val();

    if (email == null || email == '') {
        alert("Please provide Your Email Address ");
    }
    else if (password == null || password == '') {
        alert("Please provide Your Password");
    }
    else {
        singIn(email, password);
    }
}

function singIn(email, password) {
    let formData = new FormData();

    formData.append('email', email);
    formData.append('password', password);

    $.ajax({
        type: "POST",
        url: base_url + "Home/userSignIn",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        error: function (data) {
        },

        success: function (data) {
            if (data.success == true) {
                $.ajax({
                    url: base_url + 'Home/set_session',
                    data: formData,
                    processData: false,
                    contentType: false,
                    method: 'post',
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        // Swal.fire({
                        //     title: 'Login Success',
                        //     text: 'Redirecting...',
                        //     icon: 'success',
                        //     timer: 2000,
                        // }).then(() => {
                        // });
                        window.location = 'company';
                    }
                })
            }
            else {
                Swal.fire('Invalid Email or Password');
            }
        }
    });

}

// +++++++++++++++++++++++++ Sign out +++++++++++++++++++++++++++++++++

$("#btn_Log_Out").on('click', () => {
    Swal.fire({
        title: 'Are you sure you want to logout?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#007bff',
        denyButtonText: `Cancel`,
    }).then((result) => {
        if (result.isConfirmed) {
            signOut();
        } else if (result.isDenied) {

        }
    })
})

function signOut() {
    $.ajax({
        url: base_url + 'Home/logout',
        method: 'post',
        beforeSend: function (data) {
        },
        complete: function (data) {
        },
        error: function (data) {
            console.log("Something went wrong while sign out");
        },
        success: function (data) {
            window.location = 'signIn';
            localStorage.clear();
        },
    });
}


// +++++++++++++++++++++++++ Company +++++++++++++++++++++++++++++++++


const fetchAllComapany = () => {
    $.ajax({
        url: base_url + 'Dashboard/fatchAllCompanyName',
        method: 'get',
        processData: false,
        contentType: false,
        beforeSend: function (data) { 
            showLoader();
        },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            alert('Something went wrong while fatching company ');
            hideLoader();
        },
        success: function (data) {
            hideLoader();
            data = JSON.parse(data)
            let table = $('#category_list').DataTable()
            table.clear().draw()
            if (data.success) {
                data.CompanyNames.forEach(function (company_names, index) {
                    let count = index + 1
                    let names = company_names.company_name
                    let Id = company_names.company_id
                    //company_name_arr2.push(names);
                    //company_name_arr3.push(names);
                    company_id.push(company_names.company_id);
                    $('#category_list').DataTable().row.add([
                        count,Id, names,
                        `<a  id="company_edit" com_id="${company_names.company_id}" com_name="${names}" ><i class="mx-2 fa fa-edit"></i></a>
                        <a id="company_delete" com_id="${company_names.company_id}">  <i class="fa fa-trash"></i> </a>`

                    ]).draw()
                })
            }
        }
    })
}


$(document).on("click", "#company_edit", function (event) {
    let id = $(this).attr('com_id');
    let company_name = $(this).attr('com_name');
    window.history.replaceState(null, null, '?id=' + id + '');
    $("#company_name").val(company_name);
});

$(document).on("click", "#company_delete", function (event) {
    let id = $(this).attr('com_id');
    localStorage.setItem('company_id', id);

    Swal.fire({
        title: 'Do You want to delete this company ?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#F28123',
        denyButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            localStorage.getItem('category_id');
            companyDelete(id);
        } else if (result.isDenied) {

        }
    })
});

function companyDelete(id) {
    let data = new FormData()
    data.append('company_id', id)

    $.ajax({
        url: base_url + 'Dashboard/deleteCompany',
        data: data,
        method: 'post',
        processData: false,
        contentType: false,
        beforeSend: function () {
            showLoader();
        },
        complete: function () {
            hideLoader();
        },
        error:function(){
            hideLoader();
        },
        success: function (data) {
            hideLoader();
            data = JSON.parse(data);
            if (data.success == true) {
                Swal.fire({
                    title: '',
                    text: 'Company Delete Succssfully.',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#F28123'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetchAllComapany();
                    }

                })
            }
            else {
                Swal.fire('Cannot delete this company because packet is already exist with this company.');
            }
        }
    });
}


$("#company_submit").on("click", function () {

    var url = decodeURIComponent(document.URL);
    var init_array = url.substring(url.lastIndexOf('?') + 1);
    let array = init_array.split('=');
    let id = array[1];

    let company_name = $("#company_name").val();

    if (id != '' && id != undefined) {
        updateCompany(id, company_name);
    }
    else {
        // Validation
        if (company_name == null || company_name == '') {
            alert("Company Name is required field.");
        }
        else {
            addCompany()
        }
    }
});

function updateCompany(id, company_name) {

    let data = new FormData();
    data.append('company_id', id);
    data.append('company_name', company_name);


    $.ajax({
        url: base_url + 'Dashboard/updateCompany',
        data: data,
        type: "POST",
        cache: false,
        processData: false,
        contentType: false,
        dataType: false,
        beforeSend: function (data) {
        },
        complete: function (data) {
        },
        error: function (e) {
            alert("Failed to Data Add.");
        },
        success: function (data) {
            data = JSON.parse(data);
            if (data.success) {
                Swal.fire({
                    title: '',
                    text: `${data.message}`,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = window.location.href;
                        url = url.slice(0, url.indexOf('?'));
                        history.pushState(null, '', url);
                        fetchAllComapany();
                    }
                })
                $("#company_name").val("");

            }
            else {
                Swal.fire(`${data.message}`);
            }
        },
    });
}

const addCompany = () => {
    let company_name = $('#company_name').val()
    if (company_name == '' || company_name == null) {
        alert('Please Enter Company name')
        return false
    } else {
        //company_name = toTitleCase(company_name)
         company_name = company_name.toUpperCase();

        addCompanyData(company_name)
    }
}

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}

const addCompanyData = (company_name) => {
    let data = new FormData()
    data.append('company_name', company_name)

    $.ajax({
        url: base_url + 'Dashboard/uniqueName',
        data: data,
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        dataType: false,
        beforeSend: function (data) { 
            showLoader();
        },
        complete: function (data) { 
            hideLoader();
        },
        error: function (e) {
            alert('something went wrong .');
            hideLoader();
        },
        success: function (data) {
            hideLoader();
            data = JSON.parse(data);
            if (data.success) {
                Swal.fire({
                    title: '',
                    text: `${data.message}`,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetchAllComapany();
                    }
                })
                $("#company_name").val("");
            }
            else {

                let data = new FormData()
                data.append('company_name', company_name)

                $.ajax({
                    url: base_url + 'Dashboard/addCompany',
                    data: data,
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: false,
                    beforeSend: function (data) { 
                        showLoader();
                    },
                    complete: function (data) { 
                        hideLoader();
                    },
                    error: function (e) {
                        alert('something went wrong .');
                        hideLoader();
                    },
                    success: function (data) {
                        hideLoader();
                        data = JSON.parse(data);

                        if (data.success) {
                            Swal.fire({
                                title: '',
                                text: `${data.message}`,
                                confirmButtonText: 'Ok',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetchAllComapany();
                                }
                            })
                            $("#company_name").val("");

                        }
                        else {
                            Swal.fire(`${data.message}`);
                        }
                    },
                })
            }
        },
    })
}

function BindControls() {

    $.ajax({
        url: base_url + 'Dashboard/fatchAllCompanyName',
        type: 'get',
        contentType: 'application/json',
        beforeSend: function (data) {
            // showLoader();
        },
        complete: function (data) {
            // hideLoader();
        },
        error: function (data) {
            alert("Something went wrong ")
            // hideLoader();
        },

        success: function (data) {
            // hideLoader();
            data = JSON.parse(data);
            let company_name = [];
            let company_name_for_packet = [];

            data.CompanyNames.map((currentCompanyName) => {
                company_name.push({ id: currentCompanyName.company_id, name: currentCompanyName.company_name });
                company_name_for_packet.push({ id: currentCompanyName.company_id, name: currentCompanyName.company_name });

            })
            
           company_name.sort(function(a, b) {
                return a.name.localeCompare(b.name);
              });
            company_name.unshift({ id: -1, name: "All Company" });
            
            if (data.success) {
                $('#inputedCompanyName').autocomplete({
                    source: company_name.map(company => company.name),
                    minLength: 0,
                    scroll: true,
                    select: function (event, ui) {
                        var selectedCompany = company_name.find(company => company.name === ui.item.value);
                        var selectedCompanyId = selectedCompany ? selectedCompany.id : -1;
                        localStorage.setItem("FilterSelecteCompanyID", selectedCompanyId)
                        localStorage.setItem("FilterSelecteCompanyName", selectedCompany.name)
                    }
                }).focus(function () {
                    $(this).autocomplete("search", "");
                });

                // packet form 
                $('#selectedCompanyName').autocomplete({
                    source: company_name_for_packet.map(company => company.name),
                    minLength: 0,
                    scroll: true,
                    select: function (event, ui) {
                        var selectedCompany = company_name_for_packet.find(company => company.name === ui.item.value);
                        var selectedCompanyId = selectedCompany ? selectedCompany.id : -1;
                        localStorage.setItem("selecteCompanyID", selectedCompanyId)
                    }
                }).focus(function () {
                    $(this).autocomplete("search", "");
                });
            }
        }
    })
}



$("#filterCompany").on("click", function () {
    isFilter = true;
    prevPacketData = [];
    currentPage = 1;
    localStorage.removeItem("lastPacketId")
    localStorage.removeItem("pageLastIndex");
    fatchSelectedCompnay();


})


// +++++++++++++++++++++++++ packet +++++++++++++++++++++++++++++++++
function getCount(){
    $.ajax({
        url: base_url + 'Dashboard/getCount',
        type: "POST",
        processData: false,
        contentType: false,
        beforeSend: function (response) { },
        complete: function (response) {
        },
        error: function (response) {
            // alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            data = JSON.parse(data);
            if(data.success){
                $("#total_count").html((data.packet_count))
            }
        }
    })
}

function getPacketCountForFilter(){

    let data = new FormData()
    
    let selected_value = localStorage.getItem("FilterSelecteCompanyID");
    let startDate = localStorage.getItem("startDate");
    let endDate = localStorage.getItem("endDate");

    if (startDate && endDate) {
        data.append("startDate", startDate)
        data.append("endDate", endDate)
    }
    

    data.append("company_id", selected_value)

    $.ajax({
        url: base_url + 'Dashboard/getCountForFilter',
        type: "POST",
        data:data,
        processData: false,
        contentType: false,
        beforeSend: function (response) { },
        complete: function (response) {
        },
        error: function (response) {
            // alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            data = JSON.parse(data);
            if(data.success){
                $("#total_count").html((data.packet_count))
            }
        }
    })
}

function getPacketCountForFilterWithSearch(){

    let data = new FormData()
    
    let selected_value = localStorage.getItem("FilterSelecteCompanyID");
    let startDate = localStorage.getItem("startDate");
    let endDate = localStorage.getItem("endDate");
    let searchText = localStorage.getItem("searchText");

    if (startDate && endDate) {
        data.append("startDate", startDate)
        data.append("endDate", endDate)
    }
    
    if(searchText){
        data.append("searchText",searchText)
    }


    data.append("company_id", selected_value)

    $.ajax({
        url: base_url + 'Dashboard/getCountForFilterWithSearch',
        type: "POST",
        data:data,
        processData: false,
        contentType: false,
        beforeSend: function (response) { },
        complete: function (response) {
        },
        error: function (response) {
            // alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            data = JSON.parse(data);
            if(data.success){
                $("#total_count").html((data.packet_count))
            }
        }
    })
}


function getPacketSumWithFilter(){

    let data = new FormData()
    
    let selected_value = localStorage.getItem("FilterSelecteCompanyID");
    let startDate = localStorage.getItem("startDate");
    let endDate = localStorage.getItem("endDate");
    let searchText = localStorage.getItem("searchText");

    if (startDate && endDate) {
        data.append("startDate", startDate)
        data.append("endDate", endDate)
    }
    
    if(searchText){
        data.append("searchText",searchText)
    }


    data.append("company_id", selected_value)

    $.ajax({
        url: base_url + 'Dashboard/getPacketSumWithFilter',
        type: "POST",
        data:data,
        processData: false,
        contentType: false,
        beforeSend: function (response) { },
        complete: function (response) {
        },
        error: function (response) {
            // alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            data = JSON.parse(data);
            if(data.success){
                localStorage.setItem("packetSum",JSON.stringify(data));
                footerBind();
            }
        }
    })
}


$('#resetDate').click((e) => {
    localStorage.removeItem("startDate");
    localStorage.removeItem("endDate");
    localStorage.removeItem("FilterSelecteCompanyID");
    localStorage.removeItem("FilterSelecteCompanyName");
    localStorage.removeItem("invoice_company");
    localStorage.removeItem("lastPacketId");
    localStorage.removeItem("searchText");
    
    $("#searchText").val("");
    $("#selectedCompanyDate").val("DD/MM/YYYY - DD/MM/YYYY");
    $('#inputedCompanyName').val("All Company");
    fetchPacketData();
    getCount();
    getPacketSumWithFilter();
    currentPage = 1;
    isFilter = false;
    isSearch = false;
    localStorage.setItem("lastPacketId",0)
    localStorage.setItem("pageLastIndex",0);

})

let rowPerPage;
$("#rowPerPage").change(function() {
    rowPerPage = Number($(this).val());
    prevPacketData = [];
    currentPage = 1;
    localStorage.removeItem("lastPacketId");
    localStorage.setItem("lastPacketId",0)
    localStorage.setItem("pageLastIndex",0);
    if(isFilter == true){
        fatchSelectedCompnay();
    }
    else if(isSearch == true){
        searchPacket();
    }
    else{
        fetchPacketData();
    }
});

const fetchPacketData = () => {
    const lastPacketId = localStorage.getItem("lastPacketId")
    let data = new FormData();

    if(isNaN(rowPerPage)){
        rowPerPage = 50;
    }
    data.append('rowPerPage', rowPerPage);
    data.append('lastPacketId', lastPacketId);

    $.ajax({
        url: base_url + 'Dashboard/fetchAllPackets',
        data:data,
        method: 'post',
        processData: false,
        contentType: false,
        beforeSend: function (data) { 
            showLoader();
        },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ');
            hideLoader();
        },
        success: function (data) {
            data = JSON.parse(data); 
            if(data.success){
                getPacketSumWithFilter();
                dataBind(data,"API");
            } else{
                Swal.fire("No more data available")
            }
        }
    })

}

const fatchSelectedCompnay = () => {

    let data = new FormData()
    const lastPacketId = localStorage.getItem("lastPacketId")
    let selected_value = localStorage.getItem("FilterSelecteCompanyID");
    let searchText = localStorage.getItem("searchText");
    if(searchText){
        data.append("searchText", searchText)
    }

    if(selected_value != "-1")
    {
        localStorage.setItem("invoice_company", localStorage.getItem("FilterSelecteCompanyName"))

    }
    else
    {
        localStorage.removeItem("invoice_company");
    }
    // let selected_date = $("#selectedCompanyDate").val();
    let startDate = localStorage.getItem("startDate");
    let endDate = localStorage.getItem("endDate");

    if (startDate && endDate) {
        // selected_date = selected_date.replaceAll('-', '/');
        // Split the string into year, month, and day
        // const dateParts = selected_date.split('/');
        // const year = dateParts[0];
        // const month = dateParts[1];
        // const day = dateParts[2];
        // Rearrange the parts in the desired format
        // const formatted_date = day + "/" + month + "/" + year;
        data.append("startDate", startDate)
        data.append("endDate", endDate)
    }

    data.append("company_id", selected_value)
    data.append('lastPacketId', lastPacketId);
    data.append('rowPerPage', rowPerPage);

    $.ajax({
        url: base_url + 'Dashboard/fatchSelectedCompanyData',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (data) {
            showLoader();
         },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ');
            hideLoader();
        },
        success: function (data) {
            data = JSON.parse(data);
            if(data.success){
                dataBind(data,"API");
                getPacketCountForFilter();
                getPacketSumWithFilter();
            } else{
                Swal.fire("No more data available");
            }
            hideLoader();
        }
    })
}



$(".btn-container").on("click", "#next-button", function(e) {
    currentPage = currentPage + 1
    let lastIndex = localStorage.getItem("lastPacketId")
    let pageLastIndex = localStorage.getItem("pageLastIndex");

    if(lastIndex == pageLastIndex){
        // get data from api
        if(isFilter == true && isSearch == true){
            fatchSelectedCompnay();
        }
        else if(isFilter == true ){
            fatchSelectedCompnay();
        }
        else if(isSearch == true){
            searchPacket();
        }
        else{
            fetchPacketData();
        }
    }else{
        // fetch data from previos array 
        const startIndex = (currentPage -1) * rowPerPage;
        const endIndex = parseInt(startIndex) + parseInt(rowPerPage);
        const prevDataSlice = prevPacketData.slice(startIndex, endIndex);
        let pageLastIndex = prevDataSlice[prevDataSlice.length - 1]; 
        localStorage.setItem("pageLastIndex", pageLastIndex.packet_id)
        dataBind(prevDataSlice,"prevPacketData");
    }
})

$("#prev-button").on("click", (e) => {

    currentPage = Math.max(currentPage - 1, 1);
    let startIndex,endIndex;
        startIndex = (currentPage -1) * rowPerPage;
        endIndex = parseInt(startIndex) + parseInt(rowPerPage);

    const prevDataSlice = prevPacketData.slice(startIndex, endIndex);

    let lastPacketOfSelectedPage ;
    if(prevDataSlice.length > 0){
        lastPacketOfSelectedPage = prevDataSlice[prevDataSlice.length - 1];
        localStorage.setItem("pageLastIndex", lastPacketOfSelectedPage.packet_id)
    }
    dataBind(prevDataSlice, "prevPacketData");
});


function footerBind(){
let sumObj = JSON.parse(localStorage.getItem("packetSum"));
let show_total_piece =  sumObj.total_piece;
let show_total_carat =  sumObj.total_carat;
let show_none_process_piece =  sumObj.none_process_piece;
let show_none_process_carat =  sumObj.none_process_carat;
let show_broken_piece =  sumObj.broken_piece;
let show_broken_carat =  sumObj.broken_carat;
let show_final_carat =  sumObj.final_carat;

    var table = $('#packet_list').DataTable({ 
        columnDefs: [{
            'targets': 0,
            'searchable':false,
            'checkboxes': {
                'selectRow': true
            },
            'orderable':false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta){
                return '<input type="checkbox" id="cb1" name="cb[]" value="' 
                + $('<div/>').text(data).html() + '">';
            }
        }],
        order: [[ 1, 'asc' ]],
        dom: 'lBfrtip',
        searching: false,
        info: false,
        bPaginate: false,// remove pagination
        destroy: true,
        "scrollX": true, 
        "sScrollX": "100%",
        "sScrollXInner": "110%",
        "bScrollCollapse": true,
        autoWidth: false,
        // "scrollX": true,
        fixedColumns:   {
        left: 1,
        right: 1
    },
        "columns": [
            { "width": "10px" },
            { "width": "10px" },
            { "width": "10px" },
            { "width": "55px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "10px" },
            { "width": "30px" },
            
        ],
        buttons: [
            {    
                extend: 'pdfHtml5', footer: true,
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12,13]
                },  
                orientation: 'landscape',
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                    doc.styles.tableHeader.alignment = 'center';
                
                },
                text: 'Download',
            
            },
            {
                text: 'Invoice',
                attr: {
                id: 'btn-send',
                class:'buttons-pdf',
                },
                action: function(e, dt, node, config) { 
                    getSelectedInvoiceData();
            }          
                
            },
            
        ],
        
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // $(api.column(4).footer()).html(totalCarat);
            $(api.column(5).footer()).html(show_total_piece);
            $(api.column(6).footer()).html(show_total_carat.toFixed(2));
            $(api.column(7).footer()).html(show_none_process_piece);
            $(api.column(8).footer()).html(show_none_process_carat.toFixed(2));
            $(api.column(9).footer()).html(show_broken_piece);
            $(api.column(10).footer()).html(show_broken_carat.toFixed(2));
            $(api.column(11).footer()).html(show_final_carat.toFixed(2));
            // $(api.column(9).footer()).html(broken_qty);
            // $(api.column(8).footer()).html(total);
        },
    });

    table.draw();
   
}


function dataBind(data, dataSource) {

    var table = $('#packet_list').DataTable({ 
        columnDefs: [{
            'targets': 0,
            'searchable':false,
            'checkboxes': {
                'selectRow': true
            },
            'orderable':false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta){
                return '<input type="checkbox" id="cb1" name="cb[]" value="' 
                   + $('<div/>').text(data).html() + '">';
            }
         }],
        order: [[ 1, 'asc' ]],
        dom: 'lBfrtip',
        searching: false,
        info: false,
        bPaginate: false,// remove pagination
        destroy: true,
        "scrollX": true, 
        "sScrollX": "100%",
        "sScrollXInner": "110%",
        "bScrollCollapse": true,
        autoWidth: false,
         // "scrollX": true,
        fixedColumns:   {
           left: 1,
           right: 1
       },
        "columns": [
            { "width": "10px" },
            { "width": "10px" },
            { "width": "10px" },
            { "width": "55px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "30px" },
            { "width": "10px" },
            { "width": "30px" },
            
          ],
        buttons: [
            {    
                extend: 'pdfHtml5', footer: true,
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12,13]
                },  
                orientation: 'landscape',
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                    doc.styles.tableHeader.alignment = 'center';
                    // var rowCount = doc.content[1].table.body.length;
                    // for (i = 1; i < rowCount; i++) {
                    //     doc.content[1].table.body[i][4].alignment = 'right';
                    //     doc.content[1].table.body[i][5].alignment = 'right';
                    //     doc.content[1].table.body[i][6].alignment = 'right';
                    // }
                },
                text: 'Download',
                // exportOptions: {
                //     modifier: {
                //         page: 'All'
                //     }
                // }
            },
            {
                text: 'Invoice',
                attr: {
                  id: 'btn-send',
                  class:'buttons-pdf',
                },
                action: function(e, dt, node, config) { 
                    getSelectedInvoiceData();
            }          
                
            },
            // {   attr: {
            //     id: 'search-box',
            //     class:'col-md-6',
            //   },
            //     'render': function (data, type, full, meta){
            //         return `<input type="text" class="col-md-6 form-control form-control-sm" name="" id="" placeholder="">`;
            //     }    
            // }          
                
            
        ],
        
        // footerCallback: function (row, data, start, end, display) {
        //     var api = this.api();

        //     // Remove the formatting to get integer data for summation
        //     var intVal = function (i) {
        //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        //     };

        //     // Total over all pages

        //     // total_piece = api
        //     //     .column(5)
        //     //     .data()
        //     //     .reduce(function (a, b) {
        //     //         return intVal(a) + intVal(b);
        //     //     }, 0);


        //     // total_carat = api
        //     //     .column(6)
        //     //     .data()
        //     //     .reduce(function (a, b) {
        //     //         return intVal(a) + intVal(b);
        //     //     }, 0);


        //     // noneProcessPiece = api
        //     //     .column(7)
        //     //     .data()
        //     //     .reduce(function (a, b) {
        //     //         return intVal(a) + intVal(b);
        //     //     }, 0);

        //     // none_process_carat = api
        //     //     .column(8)
        //     //     .data()
        //     //     .reduce(function (a, b) {
        //     //         return intVal(a) + intVal(b);
        //     //     }, 0);


        //     // broken_piece = api
        //     //     .column(9)
        //     //     .data()
        //     //     .reduce(function (a, b) {
        //     //         return intVal(a) + intVal(b);
        //     //     }, 0);

        //     // broken_carat = api
        //     //     .column(10)
        //     //     .data()
        //     //     .reduce(function (a, b) {
        //     //         return intVal(a) + intVal(b);
        //     //     }, 0);

        //     // finalCarat = api
        //     //     .column(11)
        //     //     .data()
        //     //     .reduce(function (a, b) {
        //     //         return intVal(a) + intVal(b);
        //     //     }, 0);


        //     // // Update footer

        //     // $(api.column(4).footer()).html(totalCarat);
        //     // $(api.column(5).footer()).html(show_total_piece);
        //     // $(api.column(6).footer()).html(show_total_carat.toFixed(2));
        //     // $(api.column(7).footer()).html(show_none_process_piece);
        //     // $(api.column(8).footer()).html(show_none_process_carat.toFixed(2));
        //     // $(api.column(9).footer()).html(show_broken_piece);
        //     // $(api.column(10).footer()).html(show_broken_carat.toFixed(2));
        //     // $(api.column(11).footer()).html(show_final_carat.toFixed(2));
        //     // $(api.column(9).footer()).html(broken_qty);
        //     // $(api.column(8).footer()).html(total);
        // },
    });

    // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
    // Check/uncheck all checkboxes in the table
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
 });
    // table.buttons().container().appendTo('#example_wrapper' );
    
    table.clear().draw()
    $(".set-corner").show();
    let sourceData;
    let lastIndex = localStorage.getItem("lastPacketId")
    let pageLastIndex = localStorage.getItem("pageLastIndex");
    
    if(lastIndex == pageLastIndex){
         sourceData = (dataSource === "API") ? data.packet : data;
    }
    else{
        sourceData = (dataSource === "API") ? data.packet : data;
    }

    if(sourceData){
        
        sourceData.map(function (currentPacket, index) {

            var isDuplicate = prevPacketData.some(function(prevPacket) {
                return JSON.stringify(prevPacket) === JSON.stringify(currentPacket);
            });
        
            if (!isDuplicate) {
                prevPacketData.push(currentPacket);
            }


            let count = ((currentPage - 1 ) * rowPerPage) + (index + 1)
            let date = currentPacket.date;
            var mydate = new Date(date);
            year = mydate.getFullYear();
            month = (mydate.getMonth() + 1).toString().padStart(2, "0");
            day = mydate.getDate().toString().padStart(2, "0");
            var packetDate = day+ '/' + month + '/' + year;

            let company_name = currentPacket.company_name;
            company_name = company_name.toUpperCase();
            let packet_no = currentPacket.packet_no;
            let qty = currentPacket.packet_dimond_qty;
            let carat = parseFloat(currentPacket.packet_dimond_caret).toFixed(2);
            let pending_process = parseFloat(currentPacket.pending_process_diamond_carat).toFixed(2);
            let pending_process_qty = currentPacket.pending_process_diamond_qty;
            let broken_carat = parseFloat(currentPacket.broken_diamond_carat).toFixed(2);
            let broken_qty = currentPacket.broken_diamond_qty;
            let challan_no = currentPacket.challan_no;
            let dDate = currentPacket.delivery_date;
            var delivery_date = '-';
            if(dDate){
                var delDate = new Date(dDate);
                year = delDate.getFullYear();
                month = (delDate.getMonth() + 1).toString().padStart(2, "0");
                day = delDate.getDate().toString().padStart(2, "0");
                delivery_date = day+ '/' + month + '/' + year;
            }
            
            if(challan_no == "" || challan_no == null ){
                challan_no = '-';
            }
            

            // let cube = currentPacket.cube_qty.toFixed(2);
            // let cube_time = currentPacket.cube_time;
            // if (cube_time == "" || cube_time == null) {
            //     cube_time = "-";
            // }
            let price = parseFloat(currentPacket.price_per_carat).toFixed(2);
            var invoice_data = packet_no+ ',' + company_name + ',' + qty+ ',' +carat+ ',' +pending_process_qty+ ',' +pending_process;

            table.row.add([
                invoice_data, count, packet_no, packetDate, company_name, qty, carat,pending_process_qty, pending_process,broken_qty, broken_carat, price,challan_no,delivery_date,
                `<a  id="packet_edit" packet_id="${currentPacket.packet_id}">
                <i class="mx-2 fa fa-edit"></i></a>
                <a id="packet_delete" packet_id="${currentPacket.packet_id}">  <i class="fa fa-trash"></i> </a>`
            ])
        });


        $("#endTo").html((currentPage - 1) * rowPerPage +  (sourceData.length))      
        $("#startTO").html(((currentPage - 1) * rowPerPage) + 1)        

        if(dataSource === "API"){
            let lastPacketID = prevPacketData[prevPacketData.length - 1]; 
            let pageLastIndex = prevPacketData[prevPacketData.length - 1]; 
    
            localStorage.setItem("lastPacketId", lastPacketID.packet_id)
            localStorage.setItem("pageLastIndex", pageLastIndex.packet_id)
        } 
       
    }
    table.draw()
}

$("#searchText").on("input", function (e) {
    prevPacketData = [];
    localStorage.setItem("lastPacketId", 0);
    let searchText = $(this).val().replace(/\s+/g, ''); // Remove all spaces
    localStorage.setItem("searchText", searchText);
    isSearch = true;

    searchPacket();
    // if(isFilter == true && isSearch == true){
    //     currentPage = 1;
    //     fatchSelectedCompnay();
    //     getPacketCountForFilter();
    // }else{
    //     searchPacket();
    // }

    if(!searchText){
        localStorage.removeItem("searchText");
    }
});

const searchPacket = () =>{

    isSearch = true;
    let data = new FormData()

    if(isNaN(rowPerPage)){
        rowPerPage = 100;
    }

    let lastPacketId = localStorage.getItem("lastPacketId");
    let searchText = localStorage.getItem("searchText");
    let selected_value = localStorage.getItem("FilterSelecteCompanyID");
    let startDate = localStorage.getItem("startDate");
    let endDate = localStorage.getItem("endDate");

    
    if(searchText){
        data.append("searchText", searchText)
    }

    if (startDate && endDate) {
        data.append("startDate", startDate)
        data.append("endDate", endDate)
    }
    
    data.append("company_id", selected_value)
    data.append('rowPerPage', rowPerPage);
    data.append('lastPacketId', lastPacketId);

    $.ajax({
        url: base_url + 'Dashboard/searchPacket',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (data) { 
            showLoader();
        },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            hideLoader();
        },
        success: function (data) {
            data = JSON.parse(data); 
            if(data.success){
                dataBind(data,"API");
                getPacketCountForFilter();
            } else{
                // Swal.fire("No data available")
            }
            hideLoader();
        }
    })

}


$(document).on("click", "#packet_delete", function (event) {
    let id = $(this).attr('packet_id');
    let data = new FormData()
    data.append("packet_id", id)


    Swal.fire({
        title: 'Do You want to delete this packet ?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#F28123',
        denyButtonText: `No`,

    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + 'Dashboard/deletePacket',
                method: 'post',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function (data) { 
                    showLoader();
                },
                complete: function (data) {
                    hideLoader();
                },
                error: function (data) {
                    alert('Something went wrong while fatching packet ');
                    hideLoader();
                },
                success: function (data) {
                    hideLoader();
                    data = JSON.parse(data)
        
                    if(data.success){
                        
                        const startIndex = (currentPage -1) * rowPerPage ;
                        const endIndex = parseInt(startIndex) + parseInt(rowPerPage);
                        let prevDataSlice = prevPacketData.slice(startIndex, endIndex);
                        prevDataSlice = prevDataSlice.filter(packet => packet.packet_id !== Number(id))

                        prevPacketData = prevPacketData.filter(packet => packet.packet_id !== Number(id))

                        let lastPacketOfSelectedPage ;
                        if(prevDataSlice.length > 0){
                            lastPacketOfSelectedPage = prevDataSlice[prevDataSlice.length - 1];
                            localStorage.setItem("pageLastIndex", lastPacketOfSelectedPage.packet_id)
                            localStorage.setItem("lastPacketId", lastPacketOfSelectedPage.packet_id)
                        }

                        dataBind(prevDataSlice, "prevPacketData");
                        if(isFilter == true){
                            getPacketCountForFilter();
                        }
                        else if(isSearch == true){
                            getPacketCountForFilter();
                        }
                        else{
                            getCount();
                        }
                        $("#endTo").html((currentPage - 1) * rowPerPage +  (prevDataSlice.length))      

                    }
                    Swal.fire({
                        title: '',
                        text: `${data.message}`,
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // localStorage.removeItem("lastPacketId");
                            // localStorage.removeItem("pageLastIndex");
                            // fetchPacketData();
                        }
                    })
                }
            })
        }
    })
});


$(document).on("click", "#packet_edit", function (event) {
    let id = $(this).attr('packet_id');
    localStorage.setItem('packet_id', id)
    window.location = `packet_form`;
});

function bindPacketData() {
    let id = localStorage.getItem("packet_id");
    $("#packet_details_submit").text("Update Packet");
    $("#packet_details_reset").hide();
    let data = new FormData()
    data.append("packet_id", id);

    $.ajax({
        url: base_url + 'Dashboard/fatchPacketById',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (data) { 
            showLoader();
        },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
            hideLoader();
        },
        success: function (data) {
            hideLoader();
            data = JSON.parse(data);
            data.packet.map((pack) => {
                localStorage.setItem("selecteCompanyID",pack.company_id);
                var mydate = new Date(pack.date);
                year = mydate.getFullYear();
                month = (mydate.getMonth() + 1).toString().padStart(2, "0");
                day = mydate.getDate().toString().padStart(2, "0");
                var packetDate = day+ '/' + month + '/' + year;

                $("#selected_date").val(packetDate);
                $("#selectedCompanyName").val(pack.company_name);
                $("#number_of_packet").val(pack.packet_no);
                $("#number_of_qty").val(pack.packet_dimond_qty);
                $("#total_number_of_carat").val((pack.packet_dimond_caret).toFixed(2));
                $("#pending_process_qty").val(pack.pending_process_diamond_qty);
                $("#pending_process_carat").val((pack.pending_process_diamond_carat).toFixed(2));
                $("#broken_qty").val(pack.broken_diamond_qty);
                $("#broken_carat").val((pack.broken_diamond_carat).toFixed(2));
                $("#price_per_carat").val((pack.packet_dimond_caret - pack.pending_process_diamond_carat).toFixed(2));
                $("#cube_qty").val(pack.cube_qty);
                $("#cube_time").val(pack.cube_time);

            })
        }
    })

}


function updatePacket(id, selectedDate,company_name, company_id, packetNum, quantity, total_carat, pending_process_qty_diamond, pending_process_qty_carat, broken_qty_diamond, broken_qty_carat, cube_qty, cube_time, price_per_carat) {

    if (pending_process_qty_diamond == undefined || pending_process_qty_diamond == "" || pending_process_qty_diamond == null) {
        pending_process_qty_diamond = 0;
    }
     if (pending_process_qty_carat == undefined || pending_process_qty_carat == "" || pending_process_qty_carat == null) {
        pending_process_qty_carat = 0;
    }
     if (broken_qty_diamond == undefined || broken_qty_diamond == "" || broken_qty_diamond == null) {
        broken_qty_diamond = 0;
    }
     if (broken_qty_carat == undefined || broken_qty_carat == "" || broken_qty_carat == null) {
        broken_qty_carat = 0;
    }
     if (quantity == undefined || quantity == "" || quantity == null) {
        quantity = 0;
    }
     if (total_carat == undefined || total_carat == "" || total_carat == null) {
        total_carat = 0;
    }
     if (cube_qty == undefined || cube_qty == "" || cube_qty == null) {
        cube_qty = 0;
    }

    let data = new FormData();
    data.append('packet_id', id);
    data.append('date', selectedDate);
    data.append('company_id', company_id);
    data.append('broken_diamond_carat', broken_qty_carat);
    data.append('broken_diamond_qty', broken_qty_diamond);
    data.append('packet_dimond_caret', total_carat);
    data.append('packet_dimond_qty', quantity);
    data.append('pending_process_diamond_carat', pending_process_qty_carat);
    data.append('pending_process_diamond_qty', pending_process_qty_diamond);
    data.append('cube_qty', cube_qty);
    data.append('cube_time', cube_time);
    data.append('price_per_carat', price_per_carat);

    $.ajax({
        url: base_url + 'Dashboard/updatePacket',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (data) { 
            showLoader();
        },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            alert('Something went wrong while Update packet ')
            localStorage.removeItem("selecteCompanyID");
            localStorage.removeItem("packet_id");
            hideLoader();
        },
        success: function (data) {
            hideLoader();
            data = JSON.parse(data);
            localStorage.removeItem("selecteCompanyID");
            localStorage.removeItem("packet_id");
            
            if (data.success) {
                Swal.fire({
                    title: '',
                    text: `${data.message}`,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    if (result.isConfirmed) {
                        localStorage.removeItem("selecteCompanyID");
                        localStorage.removeItem("packet_id");
                        // fetchPacketData();
                        let UpdatedPacket = {
                            "packet_id": id,
                            "company_id": company_id,
                            "date": selectedDate,
                            "packet_no": packetNum,
                            "packet_dimond_caret": total_carat,
                            "packet_dimond_qty": quantity,
                            "pending_process_diamond_qty": pending_process_qty_diamond,
                            "pending_process_diamond_carat": pending_process_qty_carat,
                            "broken_diamond_qty": broken_qty_diamond,
                            "broken_diamond_carat": broken_qty_carat,
                            "cube_qty": cube_qty,
                            "cube_time": cube_time,
                            "price_per_carat": price_per_carat,
                            "is_delete": 0,
                            "company_name": company_name
                        }
                        //Find index of specific object using findIndex method.
                        let findPacket = prevPacketData.findIndex(packet => packet.packet_id == Number(id));
                        prevPacketData[findPacket] = UpdatedPacket;
                        dataBind(prevPacketData, "prevPacketData");
                        window.location = "packet";
                    }
                })
            }
            else {
                Swal.fire(`${data.message}`);
            }
        },
    })
}



$(document).on("click", "#packet_id", function (event) {
    let id = $(this).attr('packet_id');
    let data = new FormData()
    data.append("packet_id", id)

    $.ajax({
        url: base_url + 'Dashboard/print_invoice',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (data) { 
            showLoader();
        },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
            hideLoader();
        },
        success: function (data) {
            hideLoader();
            window.location = "invoice"
            data = JSON.parse(data)
        }
    })


});

$('#packet_details_submit').click((e) => {

    let id = localStorage.getItem("packet_id");
    let selectedDate = $("#selected_date").val();
   // console.log("selectedDate1", selectedDate); 
    selectedDate = selectedDate.split("/").reverse().join("-");
    //console.log("selectedDate2", selectedDate); 
    // var mydate = new Date(selectedDate);
    //         year = mydate.getFullYear();
    //         month = (mydate.getMonth() + 1).toString().padStart(2, "0");
    //         day = mydate.getDate().toString().padStart(2, "0");
    //         selectedDate = year+ '/' + month + '/' + day;

    // console.log("mydate", mydate); 
    // console.log("selected date", selectedDate);
    let company_id = localStorage.getItem("selecteCompanyID");
    let company_name = $("#selectedCompanyName").val()
    let packetNum = $("#number_of_packet").val();
    let quantity = $("#number_of_qty").val();
    let total_carat = $("#total_number_of_carat").val();
    let pending_process_qty_diamond = $("#pending_process_qty").val();
    let pending_process_qty_carat = $("#pending_process_carat").val();
    let broken_qty_diamond = $("#broken_qty").val();
    let broken_qty_carat = $("#broken_carat").val();
    let cube_qty = $("#cube_qty").val();
    let cube_time = $("#cube_time").val();
    let price_per_carat = $("#price_per_carat").val();


    if (id != '' && id != undefined) {
        updatePacket(id, selectedDate, company_name,company_id, packetNum, quantity, total_carat, pending_process_qty_diamond, pending_process_qty_carat, broken_qty_diamond, broken_qty_carat, cube_qty, cube_time, price_per_carat);
    }
    else {
        if (selectedDate == "" || selectedDate == null) {
            alert('Please select date')
            return false
        }
        else if (packetNum == '' || packetNum == null) {
            alert('Please Enter packet Number')
            return false
        }
        else if (company_name == '' || company_name == null) {
            alert('Please select company name')
            return false
        }
        else if (quantity == '' || quantity == null) {
            alert('Please Enter total piece ')
            return false
        }
        else if (total_carat == '' || total_carat == null) {
            alert('Please Enter total carat')
            return false
        }
        else if (pending_process_qty_diamond == null || pending_process_qty_diamond == '') {
            alert('Please Enter pending diamond quantity ')
        }
        else if (pending_process_qty_carat == null || pending_process_qty_carat == '') {
            alert('Please Enter pending carat quantity ')
        }
        else if (broken_qty_diamond == null || broken_qty_diamond == '') {
            alert('Please Enter broken diamond quantity ')
        }
        else if (broken_qty_carat == null || broken_qty_carat == '') {
            alert('Please Enter broken carat quantity ')
        }
        else if (price_per_carat == null || price_per_carat == '') {
            alert('Please Enter price per carat ')
        }

        else {
            addCaratDetails(selectedDate, company_id, packetNum, quantity, total_carat, pending_process_qty_diamond, pending_process_qty_carat, broken_qty_diamond, broken_qty_carat, cube_qty, cube_time, price_per_carat);
        }
    }
})

const addCaratDetails = (selectedDate, company_id, packetNum, quantity, total_carat, pending_process_qty_diamond, pending_process_qty_carat, broken_qty_diamond, broken_qty_carat, cube_qty, cube_time, price_per_carat) => {

    let data = new FormData()
    data.append('selectedDate', selectedDate)
    data.append('company_id', company_id)
    data.append('packetNum', packetNum)
    data.append('quantity', quantity)
    data.append('total_carat', total_carat)
    data.append('pending_process_qty_diamond', pending_process_qty_diamond)
    data.append('pending_process_qty_carat', pending_process_qty_carat)
    data.append('broken_qty_diamond', broken_qty_diamond)
    data.append('broken_qty_carat', broken_qty_carat)
    data.append('cube_qty', cube_qty)
    data.append('cube_time', cube_time)
    data.append('price_per_carat', price_per_carat)

    $.ajax({
        url: base_url + 'Dashboard/addPacketData',
        data: data,
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        dataType: false,
        beforeSend: function (data) { 
            // showLoader();
        },
        complete: function (data) { 
            // hideLoader();
        },
        error: function (e) {
            alert('Failed to Data Add.')
            // hideLoader();
        },
        success: function (data) {
            // hideLoader();
            data = JSON.parse(data);
            if (data.success) {
                Swal.fire({
                    title: '',
                    text: `${data.message}`,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    if (result.isConfirmed) {
                        localStorage.removeItem("packet_id");
                        fetchPacketData();
                        window.location = "packet";
                    }
                })

                resetForm();

            }
            else {
                Swal.fire(`${data.message}`);
            }
        },
    })
}

$("#packet_details_reset").on("click", () => {
    resetForm();
})

const resetForm = () => {
    $("#selectedCompanyName").val('');
    $("#number_of_qty").val('');
    $("#total_number_of_carat").val('');
    $("#pending_process_qty").val('0');
    $("#pending_process_carat").val('0');
    $("#broken_qty").val('0');
    $("#broken_carat").val('0');
    $("#price_per_carat").val('0');
    isFilter = false;
    currentPage = 1;

}

const autoIncPacketNum = () => {

    $.ajax({
        url: base_url + 'Dashboard/autoIncPacketNum',
        method: 'get',
        processData: false,
        contentType: false,
        beforeSend: function (data) { },
        complete: function (data) {
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            data = JSON.parse(data)
            if (data.success) {

                data.packet.forEach(function (currentPacket, index) {
                    $("#number_of_packet").val(currentPacket.packet_count + 1)
                    localStorage.setItem("last_packet_no",currentPacket.packet_count);
                })
            }
        }
    })
}


const autoIncPacketNumExport = (json_data) => {

    $.ajax({
        url: base_url + 'Dashboard/autoIncPacketNum',
        method: 'get',
        processData: false,
        contentType: false,
        beforeSend: function (data) { },
        complete: function (data) {
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            data = JSON.parse(data)
            if (data.success) {

                data.packet.forEach(function (currentPacket, index) {

                    let count = currentPacket.packet_count;
                     
                    json_data.forEach(function (obj) {
                    count++;
                   
                    var date = new Date(obj.Date);
                    obj.Date = date.getFullYear() + '-' + (date.getMonth() + 1).toString().padStart(2, '0') + '-' + date.getDate().toString().padStart(2, '0');
                    obj.packet_no = count;


                });
                
                
                sendJSON(json_data)

                    
                })
            }
        }
    })
}
$(document).on("click", "#back_to_pkg_btn", function (event) {
    localStorage.removeItem("Invoice_date");
    localStorage.removeItem("invoice_company");
    localStorage.removeItem("Invoice_data_arr");
    localStorage.removeItem("last_downloaded_invoice");
    localStorage.removeItem("Invoice_num");
    localStorage.removeItem("FilterSelecteCompanyName");
    localStorage.removeItem("FilterSelecteCompanyID");
    

    window.location = "packet";
})

// $(document).on("click", "#InvoiceBtn", function (event) {

//     $.ajax({
//         url: base_url + 'Dashboard/show_invoice',
//         method: 'post',
//         processData: false,
//         contentType: false,
//         beforeSend: function (data) { },
//         complete: function (data) {
//         },
//         error: function (data) {
//             alert('Something went wrong')
//         },
//         success: function (data) {
//             //data = JSON.parse(data)
//             //console.log("invoice :", data);
           
            

//             window.location = 'invoice_form';
//         }
//     })
// })

getSelectedInvoiceData = () => {
    

      $.ajax({
        url: base_url + 'Dashboard/show_invoice',
        method: 'post',
        processData: false,
        contentType: false,
        beforeSend: function (data) { 
            showLoader();
         },
        complete: function (data) {
            hideLoader();
        },
        error: function (data) {
            alert('Something went wrong');
            hideLoader();
        },
        success: function (data) {
            hideLoader();
            data = JSON.parse(data);
            if (data.success) {
                if (data.packet.length >0)
                {
                    //console.log("invoice :", data.packet[0].challan_no);
                    localStorage.setItem("Invoice_num", data.packet[0].challan_no);
                    localStorage.setItem("last_downloaded_invoice", 0);
                    var today = new Date();
                    // console.log("current date",today);
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();

                    today = dd + '/' + mm + '/' + yyyy;
                    localStorage.setItem("Invoice_date", today);
                }
            }
            
            // window.location = 'invoice_form';

            var invoice_data_arr=[];
            localStorage.removeItem("Invoice_data_arr");
            var oTable = $('#packet_list').dataTable();
            var rowcollection =  oTable.$("#cb1:checked", {"page": "all"});
            rowcollection.each(function(index,elem){
                var checkbox_value = $(elem).val();
                invoice_data_arr.push(checkbox_value);
            });
            localStorage.setItem("Invoice_data_arr", JSON.stringify(invoice_data_arr));
            
            let selected_value = localStorage.getItem("FilterSelecteCompanyID");
            
            if(selected_value == "-1" || selected_value == null) 
            {
                alert("Please Filter data for specific company to create invoice");

            }
            else
            {
                if( invoice_data_arr.length == 0)
                {
                    alert("Please select packet's entry to create invoice");
                }
                else if( invoice_data_arr.length > 25)
                {
                    alert("You cannot select more than 25 packets");
                }
                else
                {
                    window.location = 'invoice_form';
                }
                

            }
            

            


        }
    })

    //return val1;
  }

//   BindInvoiceData = () => {
    
//     $(".invoice_cname").text(localStorage.getItem("invoice_company"));
//     $(".invoice_cno").text(Number(localStorage.getItem("Invoice_num"))+ 1) ;
//     $(".invoice_date").text(localStorage.getItem("Invoice_date"));

//     invoice_data_arr = JSON.parse(localStorage.getItem("Invoice_data_arr"));

//     let tableRef = document.getElementById('invoice_table').getElementsByTagName('tbody')[0];
//     let tableRef2 = document.getElementById('invoice_table_2').getElementsByTagName('tbody')[0];
//     let total_pcs=0;
//     let total_weight=0;
//     let None_Process_Piece=0;
//     let None_Process_Carat=0;
//     for(i = 0; i < invoice_data_arr.length; i++)
//     {
//         result = invoice_data_arr[i].split(',');
//         total_pcs+=Number(result[2]);
//         total_weight+=Number(result[3]);
//         None_Process_Piece+=Number(result[4]);
//         None_Process_Carat+=Number(result[5]);

//         // <th class="per70 text-center">No.</th>
//         // <th class="per5 text-center">Pcs</th>
//         // <th class="per25 text-center">Carat</th>
//         // <th class="per70 text-center">None Process Piece</th>
//         // <th class="per70 text-center">None Process Carat</th>
//         // <th class="per25 text-center">Rate</th>
//         // <th class="per25 text-center">Amount</th>

//         tableRef.insertRow().innerHTML = 
//         "<td class='text-center'>" + (i+1).toString()+ "</td>" + 
//         "<td class='text-center'>" +result[2]+ "</td>"+
//         "<td class='text-center'>" +result[3]+ "</td>"+ 
//         "<td class='text-center'>" +result[4]+ "</td>"+
//         "<td class='text-center'>" +result[5]+ "</td>"+
//         "<td></td>"+
//         "<td></td>";
        
//         tableRef2.insertRow().innerHTML = 
//         "<td class='text-center'>" + (i+1).toString()+ "</td>" + 
//         "<td class='text-center'>" +result[2]+ "</td>"+
//         "<td class='text-center'>" +result[3]+ "</td>"+ 
//         "<td class='text-center'>" +result[4]+ "</td>"+
//         "<td class='text-center'>" +result[5]+ "</td>"+
//         "<td></td>"+
//         "<td></td>";
        
//         $(".sub_total_pcs").text(total_pcs);
//         $(".sub_total_Weight").text(total_weight.toFixed(2));
//         $(".none_process_piece").text(None_Process_Piece);
//         $(".none_process_caret").text(None_Process_Carat.toFixed(2));

//         $(".total_pcs").text(total_pcs-None_Process_Piece);
//         $(".total_Weight").text(((total_weight.toFixed(2))-(None_Process_Carat.toFixed(2))).toFixed(2));

//     }

//   }
//   BindInvoiceData = () => {
    
//     $("#invoice_cname").text(localStorage.getItem("invoice_company"));
//     $("#invoice_cno").text(Number(localStorage.getItem("Invoice_num"))+ 1) ;
//     $("#invoice_date").text(localStorage.getItem("Invoice_date"));

//     invoice_data_arr = JSON.parse(localStorage.getItem("Invoice_data_arr"));

//     let tableRef = document.getElementById('invoice_table').getElementsByTagName('tbody')[0];
//     let total_pcs=0;
//     let total_weight=0;
//     let None_Process_Piece=0;
//     let None_Process_Carat=0;
//     for(i = 0; i < invoice_data_arr.length; i++)
//     {
//         result = invoice_data_arr[i].split(',');
//         total_pcs+=Number(result[2]);
//         total_weight+=Number(result[3]);
//         None_Process_Piece+=Number(result[4]);
//         None_Process_Carat+=Number(result[5]);

//         // <th class="per70 text-center">No.</th>
//         // <th class="per5 text-center">Pcs</th>
//         // <th class="per25 text-center">Carat</th>
//         // <th class="per70 text-center">None Process Piece</th>
//         // <th class="per70 text-center">None Process Carat</th>
//         // <th class="per25 text-center">Rate</th>
//         // <th class="per25 text-center">Amount</th>

//         tableRef.insertRow().innerHTML = 
//         "<td class='text-center'>" + (i+1).toString()+ "</td>" + 
//         "<td class='text-center'>" +result[2]+ "</td>"+
//         "<td class='text-center'>" +result[3]+ "</td>"+ 
//         "<td class='text-center'>" +result[4]+ "</td>"+
//         "<td class='text-center'>" +result[5]+ "</td>"+
//         "<td></td>"+
//         "<td></td>";


        
//         $("#sub_total_pcs").text(total_pcs);
//         $("#sub_total_Weight").text(total_weight.toFixed(2));
//         $("#none_process_piece").text(None_Process_Piece);
//         $("#none_process_caret").text(None_Process_Carat.toFixed(2));

//         $("#total_pcs").text(total_pcs-None_Process_Piece);
//         $("#total_Weight").text(((total_weight.toFixed(2))-(None_Process_Carat.toFixed(2))).toFixed(2));

//     }

//   }


BindInvoiceData = () => {
    showLoader();
    $("#invoice_cname").text(localStorage.getItem("invoice_company"));
    $("#invoice_cno").text(Number(localStorage.getItem("Invoice_num"))+ 1) ;
    $("#invoice_date").text(localStorage.getItem("Invoice_date"));

    invoice_data_arr = JSON.parse(localStorage.getItem("Invoice_data_arr"));

    let tableRef = document.getElementById('invoice_table').getElementsByTagName('tbody')[0];
    let total_pcs=0;
    let total_weight=0;
    let None_Process_Piece=0;
    let None_Process_Carat=0;
    
    let recordCount = 0; // Counter variable to keep track of the number of records added to the table
    
    // Add empty rows to the table
    for (let j = 0; j < 10; j++) {
        tableRef.insertRow().innerHTML = 
        "<td class='text-center'>" + (j+1).toString()+ "</td>" + 
        "<td class='text-center'></td>"+
        "<td class='text-center'></td>"+ 
        "<td class='text-center'></td>"+
        "<td class='text-center'></td>"+
        "<td></td>"+
        "<td></td>";
    }
    
    for(i = 0; i < invoice_data_arr.length; i++)
    {
        result = invoice_data_arr[i].split(',');
        total_pcs+=Number(result[2]);
        total_weight+=Number(result[3]);
        None_Process_Piece+=Number(result[4]);
        None_Process_Carat+=Number(result[5]);
    
        // Insert data into empty rows
        for (let j = recordCount*10; j < (recordCount+1)*10; j++) {
            if (tableRef.rows[j].cells[1].innerHTML === "") {
                tableRef.rows[j].cells[1].innerHTML = result[2];
                tableRef.rows[j].cells[2].innerHTML = result[3];
                tableRef.rows[j].cells[3].innerHTML = result[4];
                tableRef.rows[j].cells[4].innerHTML = result[5];
                break;
            }
        }
    
        // recordCount++;
    
        $("#sub_total_pcs").text(total_pcs);
        $("#sub_total_Weight").text(total_weight.toFixed(2));
        $("#none_process_piece").text(None_Process_Piece);
        $("#none_process_caret").text(None_Process_Carat.toFixed(2));

        $("#total_pcs").text(total_pcs-None_Process_Piece);
        $("#total_Weight").text(((total_weight.toFixed(2))-(None_Process_Carat.toFixed(2))).toFixed(2));

    }
    hideLoader();
}

  convertHtmlToPdf = () => {
    var doc = new jsPDF();
  
    var specialElementHandlers = {
        '#editor': function (element, renderer) {
            return true;
        }
    };
    doc.fromHTML($('#invoice_content').html(), 15, 15, {
        'width': 170,
            'elementHandlers': specialElementHandlers
    });
    doc.save('sample-file.pdf');
  }


  function CreatePDFfromHTML() {
    var HTML_Width = 794; // A4 width in pixels at 72 DPI
    var HTML_Height = 1123; // A4 height in pixels at 72 DPI

    // var HTML_Width = ($("#invoice_content").width());
    // var HTML_Height = ($("#invoice_content").height());
    var top_left_margin = 15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;



    html2canvas($("#invoice_content")[0], { scale: 2,scrollY: 0 }).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        //var pdf = new jsPDF('p', 'pt', 'A4');

        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save("Invoice.pdf");
        // $(".container").hide();
    });
}

function generatePDF() {
    // Choose the element that your content will be rendered to.
    const element = document.getElementById('invoice-content');
    // Choose the element and save the PDF for your user.
    html2pdf().from(element).save();
}

$(document).on("click", "#download_btn", function (event) {
    // generatePDF();
    CreatePDFfromHTML();
    if((Number(localStorage.getItem("Invoice_num"))+ 1) != (Number(localStorage.getItem("last_downloaded_invoice"))))
    {
        updateChallanNo(Number(localStorage.getItem("Invoice_num"))+ 1)
    }

    invoiceEntry();
    // convertHtmlToPdf();
})


function invoiceEntry() {
    let company_id = localStorage.getItem("FilterSelecteCompanyID");
    let delivery_date = localStorage.getItem("Invoice_date");
    let challan_no = (Number(localStorage.getItem("Invoice_num")) + 1);

    let str = JSON.parse(localStorage.getItem('Invoice_data_arr'));
    let packetNos = [];

    for (let i = 0; i < str.length; i++) {
        let packetNo = str[i].split(',')[0];
        packetNos.push(packetNo);
    }

    // console.log(packetNos);

    let invoice_entry_obj = {
        data: []
    };

    for (let i = 0; i < packetNos.length; i++) {
        let packet_no = packetNos[i];

        let invoice_entry_packet_obj = {
            company_id: company_id,
            delivery_date: delivery_date,
            challan_no: challan_no,
            packet_no: packet_no
        };

        invoice_entry_obj.data.push(invoice_entry_packet_obj);
    }

    let jsonString = JSON.stringify(invoice_entry_obj);
    // console.log("jsonString :",jsonString);

    let response = new FormData();
    response.append("data", jsonString);

    $.ajax({
        url: base_url + 'Dashboard/invoice_entry',
        type: "POST",
        data: response,
        processData: false,
        contentType: false,
        beforeSend: function (response) { },
        complete: function (response) {
        },
        error: function (response) {
        },
        success: function (response) {
            // console.log("response :",response);
        }
    })
}

function updateChallanNo(challan_no) {
    
    let data = new FormData();
    data.append('challan_no', challan_no);
    localStorage.setItem("last_downloaded_invoice", challan_no);
    
    $.ajax({
        url: base_url + 'Dashboard/updateChallanNo',
        data: data,
        type: "POST",
        cache: false,
        processData: false,
        contentType: false,
        dataType: false,
        beforeSend: function (data) {
        },
        complete: function (data) {
        },
        error: function (e) {
            localStorage.setItem("last_downloaded_invoice", 0);
            alert("Failed to Data Add.");
        },
        success: function (data) {
            
            
        },
    });
}


// // Convert HTML content to PDF
// function Convert_HTML_To_PDF() {
//     window.jsPDF = window.jspdf.jsPDF;
//     var doc = new jsPDF();
	
//     // Source HTMLElement or a string containing HTML.
//     var elementHTML = document.querySelector("#contentToPrint");

//     doc.html(elementHTML, {
//         callback: function(doc) {
//             // Save the PDF
//             doc.save('document-html.pdf');
//         },
//         margin: [10, 10, 10, 10],
//         autoPaging: 'text',
//         x: 0,
//         y: 0,
//         width: 190, //target width in the PDF document
//         windowWidth: 675 //window width in CSS pixels
//     });
// }






