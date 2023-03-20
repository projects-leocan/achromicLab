// live 
const base_url = 'https://leocan.co/subFolder/achromicLab/';

// local 
// const base_url = 'http://localhost/achromicLab/';

// ready function 
$(() => {

    if (window.location.href == base_url + 'company') {
        fetchAllComapany();
    }

    if (window.location.href == base_url + 'packet') {
        fetchPacketData();
        $('#inputedCompanyName').val("All Company");
        BindControls();
    }

    if (window.location.href == base_url + 'packet_form') {
        if (localStorage.getItem("packet_id") != "") {
            bindPacketData();
        }
        $("#selected_date").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: new Date()
        });
        $("#selected_date").datepicker('setDate', new Date());
        BindControls();
        autoIncPacketNum();



    }
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
    // console.log("formated number===", fixedNum);

    $("#price_per_carat").val(fixedNum);
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
        beforeSend: function (data) { },
        complete: function (data) {
        },
        error: function (data) {
            alert('Something went wrong while fatching company ')
        },
        success: function (data) {
            data = JSON.parse(data)
            let table = $('#category_list').DataTable()
            table.clear().draw()
            if (data.success) {
                data.CompanyNames.forEach(function (company_names, index) {
                    let count = index + 1
                    let names = company_names.company_name
                    $('#category_list').DataTable().row.add([
                        count, names,
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
        },
        complete: function () {
        },
        success: function (data) {
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
        company_name = toTitleCase(company_name)
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
        beforeSend: function (data) { },
        complete: function (data) { },
        error: function (e) {
            alert('something went wrong .')
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
                    beforeSend: function (data) { },
                    complete: function (data) { },
                    error: function (e) {
                        alert('something went wrong .')
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        console.log("data :", data);

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
        },
        complete: function (data) {

        },
        error: function (data) {
            alert("Something went wrong ")
        },

        success: function (data) {
            data = JSON.parse(data);
            let company_name = [{ id: -1, name: "All Company" }];
            let company_name_for_packet = [];

            data.CompanyNames.map((currentCompanyName) => {
                company_name.push({ id: currentCompanyName.company_id, name: currentCompanyName.company_name });
                company_name_for_packet.push({ id: currentCompanyName.company_id, name: currentCompanyName.company_name });

            })

            if (data.success) {
                $('#inputedCompanyName').autocomplete({
                    source: company_name.map(company => company.name),
                    minLength: 0,
                    scroll: true,
                    select: function (event, ui) {
                        var selectedCompany = company_name.find(company => company.name === ui.item.value);
                        var selectedCompanyId = selectedCompany ? selectedCompany.id : -1;
                        localStorage.setItem("FilterSelecteCompanyID", selectedCompanyId)
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

    let selected_value = localStorage.getItem("FilterSelecteCompanyID");
    if (selected_value == "-1") {
        fetchPacketData();
    }
    else {
        fatchSelectedCompnay();
    }
})

// +++++++++++++++++++++++++ packet +++++++++++++++++++++++++++++++++

const fetchPacketData = () => {
    $.ajax({
        url: base_url + 'Dashboard/fetchAllPackets',
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
            let table = $('#packet_list').DataTable()
            table.clear().draw()
            let totalQty = 0;
            let totalCarat = 0;
            let totalNoneProcess = 0;
            let totalBroken = 0;
            let finalPrice = 0;
            if (data.success) {
                data.packet.forEach(function (currentPacket, index) {
                    let count = index + 1;
                    let date = currentPacket.date;
                    let company_name = currentPacket.company_name;
                    let packet_no = currentPacket.packet_no;
                    let qty = currentPacket.packet_dimond_qty;
                    let carat = currentPacket.packet_dimond_caret;
                    let pending_process = currentPacket.pending_process_diamond_carat;
                    let broken = currentPacket.broken_diamond_carat;
                    let price = currentPacket.price_per_carat;
                    let invoice = `<a id="packet_id" packet_id=${currentPacket.packet_id} class="invoice-btn" >Invoice</a>`;


                    totalQty += parseInt(qty);
                    totalCarat += parseFloat(carat);
                    totalNoneProcess += parseFloat(pending_process);
                    totalBroken += parseFloat(broken);
                    finalPrice += parseFloat(price);
                    

                    $('#packet_list').DataTable().row.add([
                        count, date, company_name, packet_no, qty, carat, pending_process, broken, price, invoice,
                        `<a  id="packet_edit" packet_id="${currentPacket.packet_id}">
                        <i class="mx-2 fa fa-edit"></i></a>
                        <a id="packet_delete" packet_id="${currentPacket.packet_id}">  <i class="fa fa-trash"></i> </a>`

                    ]).draw()

                })

                let newTotalTR = `<tr>
                    <td colspan="4"> <b>Total <b></td>
                    <td> <b> ${totalQty} <b>  </td>
                    <td> <b> ${totalCarat.toFixed(2)} <b> </td>
                    <td> <b> ${totalNoneProcess.toFixed(2)} <b></td>
                    <td> <b> ${totalBroken.toFixed(2)} <b></td>
                    <td> <b> ${finalPrice.toFixed(2)} <b> </td>
                    <td colspan="2"> </td>
                
                </tr>`

                $('#packet_list').append(newTotalTR);
            }
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
                beforeSend: function (data) { },
                complete: function (data) {
                },
                error: function (data) {
                    alert('Something went wrong while fatching packet ')
                },
                success: function (data) {
                    data = JSON.parse(data)
                    Swal.fire({
                        title: '',
                        text: `${data.message}`,
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetchPacketData();
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
    let data = new FormData()
    data.append("packet_id", id);

    $.ajax({
        url: base_url + 'Dashboard/fatchPacketById',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (data) { },
        complete: function (data) {
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            data = JSON.parse(data);
            data.packet.map((pack) => {
                $("#selected_date").val(pack.date);
                $("#number_of_qty").val(pack.packet_dimond_qty);
                $("#total_number_of_carat").val(pack.packet_dimond_caret);
                $("#pending_process_qty").val(pack.pending_process_diamond_qty);
                $("#pending_process_carat").val(pack.pending_process_diamond_carat);
                $("#broken_qty").val(pack.broken_diamond_qty);
                $("#broken_carat").val(pack.broken_diamond_carat);
                $("#price_per_carat").val(pack.packet_dimond_caret - pack.pending_process_diamond_carat);
                $("#cube_qty").val(pack.cube_qty);
                $("#cube_time").val(pack.cube_time);

            })
        }
    })

}


function updatePacket(id, selectedDate, company_id, packetNum, quantity, total_carat, pending_process_qty_diamond, pending_process_qty_carat, broken_qty_diamond, broken_qty_carat, cube_qty, cube_time, price_per_carat) {

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
        beforeSend: function (data) { },
        complete: function (data) {
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
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
                        fetchPacketData();
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
        beforeSend: function (data) { },
        complete: function (data) {
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            window.location = "invoice"
            data = JSON.parse(data)
            console.log("data :", data);
        }
    })


});

$('#packet_details_submit').click((e) => {
    let id = localStorage.getItem("packet_id");
    let selectedDate = $("#selected_date").val();
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
        updatePacket(id, selectedDate, company_id, packetNum, quantity, total_carat, pending_process_qty_diamond, pending_process_qty_carat, broken_qty_diamond, broken_qty_carat, cube_qty, cube_time, price_per_carat);
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
            alert('Please Enter quantity ')
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
        beforeSend: function (data) { },
        complete: function (data) { },
        error: function (e) {
            alert('Failed to Data Add.')
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
}


const fatchSelectedCompnay = () => {

    let data = new FormData()
    let selected_value = localStorage.getItem("FilterSelecteCompanyID");
    data.append("company_id", selected_value)

    $.ajax({
        url: base_url + 'Dashboard/fatchSelectedCompanyData',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (data) { },
        complete: function (data) {
        },
        error: function (data) {
            alert('Something went wrong while fatching packet ')
        },
        success: function (data) {
            let table = $('#packet_list').DataTable()
            table.clear().draw()
            data = JSON.parse(data)
            if (data.success) {
                data.CompanyNames.forEach(function (currentPacket, index) {
                    let count = index + 1;
                    let date = currentPacket.date;
                    let company_name = currentPacket.company_name;
                    let packet_no = currentPacket.packet_no;
                    let qty = currentPacket.packet_dimond_qty;
                    let carat = currentPacket.packet_dimond_caret;
                    let pending_process = currentPacket.pending_process_diamond_carat;
                    let broken = currentPacket.broken_diamond_carat;
                    let price = currentPacket.price_per_carat;
                    let invoice = `<a href="#" style="text-decoration: underline;"> Invoice</a>`

                    $('#packet_list').DataTable().row.add([
                        count, date, company_name, packet_no, qty, carat, pending_process, broken, price, invoice,
                        `<a  id="packet_edit" packet_id="${currentPacket.packet_id}"  ><i class="mx-2 fa fa-edit"></i></a>
                        <a id="packet_delete" packet_id="${currentPacket.packet_id}">  <i class="fa fa-trash"></i> </a>`
                    ]).draw()
                })
            }
        }
    })
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
                })
            }
        }
    })
}










