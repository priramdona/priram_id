<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Clean UI Admin Template Modular</title>
    <link href="../../modules/core/common/img/favicon.ico" rel="shortcut icon">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i" rel="stylesheet">


    <!-- v2.0.0 -->
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/bootstrap/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/perfect-scrollbar/css/perfect-scrollbar.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/ladda//dist/ladda-themeless.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/bootstrap-select//dist/css/bootstrap-select.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/select2//dist/css/select2.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/fullcalendar//dist/fullcalendar.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/bootstrap-sweetalert//dist/sweetalert.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/summernote//dist/summernote.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/owl.carousel//dist/assets/owl.carousel.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/ionrangeslider/css/ion.rangeSlider.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/datatables/media/css/dataTables.bootstrap4.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/c3/c3.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/chartist//dist/chartist.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/nprogress/nprogress.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/jquery-steps/demo/css/jquery.steps.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/dropify//dist/css/dropify.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/font-linearicons/style.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/font-icomoon/style.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/font-awesome/css/font-awesome.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/cleanhtmlaudioplayer/src/player.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/cleanhtmlvideoplayer/src/player.css') !!}">

    <script src="{!! asset('/dist/vendors/jquery//dist/jquery.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/popper.js//dist/umd/popper.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/bootstrap/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/jquery-mousewheel/jquery.mousewheel.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/perfect-scrollbar/js/perfect-scrollbar.jquery.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/spin.js/spin.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/ladda//dist/ladda.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/bootstrap-select//dist/js/bootstrap-select.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/select2//dist/js/select2.full.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/html5-form-validation//dist/jquery.validation.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/jquery-typeahead//dist/jquery.typeahead.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/jquery-mask-plugin//dist/jquery.mask.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/autosize//dist/autosize.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/bootstrap-show-password/bootstrap-show-password.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/moment/min/moment.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/fullcalendar//dist/fullcalendar.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/bootstrap-sweetalert//dist/sweetalert.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/remarkable-bootstrap-notify//dist/bootstrap-notify.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/summernote//dist/summernote.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/owl.carousel//dist/owl.carousel.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/ionrangeslider/js/ion.rangeSlider.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/nestable/jquery.nestable.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/datatables/media/js/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/datatables/media/js/dataTables.bootstrap4.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/datatables-fixedcolumns/js/dataTables.fixedColumns.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/datatables-responsive/js/dataTables.responsive.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/editable-table/mindmup-editabletable.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/d3/d3.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/c3/c3.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/chartist//dist/chartist.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/peity/jquery.peity.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/chartist-plugin-tooltip//dist/chartist-plugin-tooltip.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/jquery-countTo/jquery.countTo.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/nprogress/nprogress.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/jquery-steps/build/jquery.steps.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/chart.js//dist/Chart.bundle.min.js') !!}"></script>
    <script src="{!! asset('/dist/vendors/dropify//dist/js/dropify.min.js') !!}"></script>

    <!-- CLEAN UI ADMIN TEMPLATE MODULES-->
    <!-- v2.0.0 -->
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/core/common/core.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/vendors/common/vendors.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/layouts/common/layouts-pack.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/themes/common/themes.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/menu-left/common/menu-left.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/menu-right/common/menu-right.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/top-bar/common/top-bar.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/footer/common/footer.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/pages/common/pages.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/ecommerce/common/ecommerce.cleanui.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/apps/common/apps.cleanui.css') !!}">
    <script src="{!! asset('/dist/modules/menu-left/common/menu-left.cleanui.js') !!}"></script>
    <script src="{!! asset('/dist/modules/menu-right/common/menu-right.cleanui.js') !!}"></script>
    @yield('css')
</head>
<body class="cat__config--horizontal cat__menu-left--colorful">

<div class="cat__content">
<!-- START: components/mail-templates -->
<section class="card">
    <div class="card-header">
        <span class="cat__core__title">
            <strong>Kasir Mulia - Hubungi Kami</strong>
        </span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-6 col-lg-12">
                <div class="mb-5">
                    <!-- Start Letter -->
                    <div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                        <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <img src="{{ asset('images/logo-dark.png') }}" alt="Kasir Mulia" style="height: 40px" />
                                    </td>
                                    <td style="text-align: right; vertical-align: middle;">
                                        <span style="color: #a09bb9;">
                                            Layanan Hubungi Kami
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <div style="padding: 40px 40px 20px 40px; background: #fff;">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody><tr>
                                        <td>
                                            <h4>Hi <strong>{{ $name }}</strong>,</h4>
                                            <p>Terima kasih telah menghubungi kami, admin kami akan membalas segera dan memproses permintaan anda dengan informasi sebagai berikut :</p>

                                            <p><strong>Kategori :</strong> {{ $category }}</p>
                                            <p><strong>Nama :</strong> {{ $name }}</p>
                                            <p><strong>Email :</strong> {{ $email }}</p>
                                            <p><strong>Isi Pesan :</strong></p>
                                            <p>{{ $contactMessage }}</p>

                                          <p>Sukses selalu dan Sehat Selalu <strong>{{ $name }}</strong>.</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align: center; font-size: 12px; color: #a09bb9; margin-top: 20px">
                                <p>
                                    Kasir Mulia

                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- End Start Letter -->
                </div>
            </div>

        </div>

    </div>
</section>
<!-- END: components/mail-templates -->

</div>
</body>
</html>

