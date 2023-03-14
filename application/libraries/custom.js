const base_url = 'https://leocan.co/subFolder/achromicLab/';


$(() => {

})

$('.basicAutoComplete').autoComplete();

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
                        window.location = 'company';
                    }
                })
            }
        }
    });

}

// +++++++++++++++++++++++++ Sign out +++++++++++++++++++++++++++++++++

$("#btn_Log_Out").on('click', () => {
    signOut();
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


// Categories
const fetchAllCategories = () => {

    // $('.category').val('')
    $.ajax({
        url: base_url + 'Dashboard/fetchAllCategories',
        // data: {
        // },
        method: 'get',
        processData: false,
        contentType: false,
        beforeSend: function (data) { },
        complete: function (data) {
            // $('#category_name').val('')
        },
        error: function (data) {
            alert('Something went wrong while fatching categories')
        },
        success: function (data) {
            // console.log("succcc")
            let table = $('#category_list').DataTable()
            table.clear().draw()
            data = JSON.parse(data)
            // console.log()
            if (data.success) {
                data.list.forEach(function (list, index) {
                    let count = index + 1
                    let category = list.name
                    $('#category_list').DataTable().row.add([
                        count, category,
                        '<a class="btn btn-success" id="category_edit" cat_id="' + list.id + '" category_name="' + category + '" >EDIT</a>' + ' ' +
                        '<a class="btn btn-danger" id="category_delete" category_id="' + list.id + '">DELETE</a>'

                    ]).draw()
                })
            }
        }
    })
}




const fatchAllProducts = () => {
    // console.log('Fatching Products')
    $.ajax({
        url: base_url + 'Dashboard/fetchAllProducts',
        // data: {
        // },
        method: 'get',
        processData: false,
        contentType: false,
        beforeSend: function (data) { },
        complete: function (data) { },
        error: function (data) {
            alert('Something went wrong while fatching products')
            console.log(data)
        },
        success: function (data) {
            let table = $('#product_list').DataTable()
            table.clear().draw()
            data = JSON.parse(data)
            if (data.success) {
                data.list.forEach(function (list, index) {
                    // console.log(list)
                    let count = index + 1
                    let category = list.category
                    let name = list.name
                    let price = list.price
                    let weight = list.weight
                    let description = list.description

                    let imgHtml = '<img>'
                    imgHtml = '<a class="popimg" href="' + list.image + '"><img id="image" src="' + list.image + '" style="height:100px; width:100px;" class="flower_img"/></a>'

                    // console.log(image_url);

                    // console.log(imgHtml)
                    $('#product_list').DataTable().row.add([
                        count, imgHtml, category, name, price, weight, description,
                        '<a class="btn btn-success"id="product_edit" product_id="' + list.id + '" category="' + category + '" pro_cat_id="' + list.pro_cat_id + '" name="' + name + '" price="' + price + '" weight="' + weight + '" description="' + description + '" image="' + list.image + '">EDIT</a>' + ' ' +
                        '<a class="btn btn-danger" id="product_delete" product_id="' + list.id + '">DELETE</a>'

                    ]).draw()
                })
            } else {
                // success false.
            }
        }
    })
}



$('#packet_details_submit').click((e) => {
    let selectedDate = $("#selected_date").val();
    let inputedCompanyName = $("#inputedCompanyName").val();
    let NumOfPacket = $("#number_of_packet").val();
    let NumOfCarat = $("#number_of_carat").val();
    


})


$("#inputedCompanyName").click(function () {

    $.ajax({
        url: base_url + 'Dashboard/fatchAllCompanyName',
        type: 'get',
        // cache: false,
        processData: false,
        contentType: false,

        beforeSend: function (data) {

        },
        complete: function (data) {

        },
        error: function (data) {
            alert("Something went wrong while updating")
        },

        success: function (data) {
            data = JSON.parse(data);
            if (data.success) {
                data.CompanyNames.forEach((currentCompanyName) => {
                    $("#company_name").append(`<option class="w-100">${currentCompanyName.company_name}</option>`);
                })
            }


        }

    })

})


$('#category_submit').click((e) => {
        addCategories()

})




const addCategories = () => {
    let categoryName = $('#category_name').val()

    if (categoryName == '' || categoryName == null) {
        alert('Please Enter Company name')
        return false
    } else {
        addCategoriesData(categoryName)
    }
}

const addCategoriesData = (categoryName) => {

    let data = new FormData()
    data.append('categoryName', categoryName)
    $.ajax({
        url: base_url + 'Dashboard/addCategories',
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
            console.log(data)
        },
        success: function (data) {
            alert('Data added Successfully.')
        }
    })
}






// add Producst
const addProduct = () => {
    let cat_name_dropDown = $('#category_list_dropdown').val()
    let productName = $('#product_name').val()
    let productPrice = $('#product_price').val()
    let productWeight = $('#product_weight').val()
    let productDesc = $('#product_description').val()

    let image = $('#customFile').prop('files')[0]
    let img = $('#customFile').val()


    // console.log(`category name :${cat_name_dropDown},prodcut name : ${productName}, price :${productPrice},weight : ${productWeight},description : ${productDesc}, img : ${image}`);
    // console.log("image", image);
    // console.log(`image: ${image} img : ${img}`);

    if (cat_name_dropDown == -1) {
        alert('Please select your category')
        return false
    }
    else if (productName == '') {
        alert('Please Enter your product name')
        return false
    }
    else if (productPrice == '') {
        alert('Please Enter your product Price')
        return false
    }
    else if (productWeight == '') {
        alert('Please Enter your product Weight')
        return false
    }
    else if (productDesc == '') {
        alert('Please Enter your product description')
        return false
    }
    else if (image == null || image == '') {
        alert('Image is required field.')
    } else {
        addProductData(cat_name_dropDown, productName, productPrice, productWeight, productDesc, image);
    }
}

const addProductData = (cat_name_dropDown, productName, productPrice, productWeight, productDesc, image) => {
    let data = new FormData()
    data.append('cat_name_dropDown', cat_name_dropDown)
    data.append('productName', productName)
    data.append('productPrice', productPrice)
    data.append('productWeight', productWeight)
    data.append('productDesc', productDesc)
    data.append('image', image)

    // console.log(`info : ${cat_name_dropDown},${productName},${productPrice},${productWeight},${productDesc},${image}`);

    $.ajax({
        url: base_url + 'Dashboard/addProduct',
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
            console.log(data)
        },
        success: function (data) {
            console.log(data);
            alert('Data added Successfully')
            // $("#category_list_dropdown").val() = -1;
            $("#product_name").val() = "";
            $("#product_price").val() = "";
            $("#product_weight").val() = "";
            $("#product_description").val() = "";

            fatchAllProducts();
        }

    })
}









