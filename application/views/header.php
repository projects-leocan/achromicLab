<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Achromic Lab</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- Popup Style -->
    <link rel="stylesheet" type="text/css" href="assets/css/popupstyle.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- favicon -->
    <link rel="icon" type="image/png" href="favicon.png">


    <style>

    .nav-item:hover{
        cursor:pointer;
    }

    .ScrollStyle {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: calc(100vh - 30px - 30px)
    }

    .active2 {
        background-color: #F28123;
        color: black;
    }

    .swal2-styled.swal2-confirm {
        background-color: #0062cc !important;
    }

    .swal-modal {
        width: 300px;
    }

    .swal-title {
        color: green;
        text-transform: none;
        position: relative;
        display: block;
        padding: 13px 16px;
        font-size: 18px;
        line-height: normal;
        text-align: center;
        margin-bottom: 0;
    }

    .swal-button {
        background-color: #228e22;
        color: #fff;
        border: none;
        box-shadow: none;
        border-radius: 3px;
        font-size: 12px;
        padding: 7px 19px;
        cursor: pointer;
    }

    .swal2-title {
        position: relative;
        max-width: 100%;
        margin: 0;
        padding: 0.8em 1em 0;
        color: inherit;
        font-size: 1.125em !important;
        font-weight: 400 !important;
        text-align: center;
        text-transform: none;
        word-wrap: break-word;
    }
    .btn-center{
        display:flex;
        align-items: center;
        justify-content: center;
        text-align:center;
    }
    .invoice-btn{
        cursor:pointer;
        text-decoration:underline;
    }
    .side-h1{
        position:relative;
        right:82px;
    }

    /* loader  */
    

    .semipolar-spinner, .semipolar-spinner * {
      box-sizing: border-box;
    }

    .semipolar-spinner {
        height: 150px;
        /* width: 150px; */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
}

/* 
    .semipolar-spinner {
      height: 150px;
      width: 150px;
      position: relative;
      margin:auto;
    } */

    .semipolar-spinner .ring {
      border-radius: 50%;
      position: absolute;
      border: calc(150px * 0.05) solid transparent;
      border-top-color: #007bff;
      border-left-color: #007bff;
      animation: semipolar-spinner-animation 2s infinite;
    }

    .semipolar-spinner .ring:nth-child(1) {
      height: calc(150px - 150px * 0.2 * 0);
      width: calc(150px - 150px * 0.2 * 0);
      top: calc(150px * 0.1 * 0);
      left: calc(150px * 0.1 * 0);
      animation-delay: calc(2000ms * 0.1 * 4);
      z-index: 5;
    }

    .semipolar-spinner .ring:nth-child(2) {
      height: calc(150px - 150px * 0.2 * 1);
      width: calc(150px - 150px * 0.2 * 1);
      top: calc(150px * 0.1 * 1);
      left: calc(150px * 0.1 * 1);
      animation-delay: calc(2000ms * 0.1 * 3);
      z-index: 4;
    }

    .semipolar-spinner .ring:nth-child(3) {
      height: calc(150px - 150px * 0.2 * 2);
      width: calc(150px - 150px * 0.2 * 2);
      top: calc(150px * 0.1 * 2);
      left: calc(150px * 0.1 * 2);
      animation-delay: calc(2000ms * 0.1 * 2);
      z-index: 3;
    }

    .semipolar-spinner .ring:nth-child(4) {
      height: calc(150px - 150px * 0.2 * 3);
      width: calc(150px - 150px * 0.2 * 3);
      top: calc(150px * 0.1 * 3);
      left: calc(150px * 0.1 * 3);
      animation-delay: calc(2000ms * 0.1 * 1);
      z-index: 2;
    }

    .semipolar-spinner .ring:nth-child(5) {
      height: calc(150px - 150px * 0.2 * 4);
      width: calc(150px - 150px * 0.2 * 4);
      top: calc(150px * 0.1 * 4);
      left: calc(150px * 0.1 * 4);
      animation-delay: calc(2000ms * 0.1 * 0);
      z-index: 1;
    }

    @keyframes semipolar-spinner-animation {
      50% {
        transform: rotate(360deg) scale(0.7);
      }
    }
    </style>

</head>


<body class="hold-transition sidebar-mini">