<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Credit card Payment Method</title>
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
</head>
<body class="cat__config--horizontal cat__menu-left--colorful">

<!-- START: ecommerce/cart-checkout -->
<section class="card">
    <div class="card-header">
        <span class="cat__core__title">
            <strong>Cart</strong>
        </span>
    </div>
    <div class="card-body">
        <div class="cat__ecommerce__cart">
            <div id="cart-checkout" class="cat__wizard mb-5">
                <h3>
                    <i class="icmn-cart cat__wizard__steps__icon"></i>
                    <span class="cat__wizard__steps__title">Cart</span>
                </h3>
                <section>
                    <div>
                        <table class="table table-hover text-right">
                            <thead class="thead-default">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Description</th>
                                <th class="text-right">Quantity</th>
                                <th class="text-right">Unit Cost</th>
                                <th class="text-right">Total</th>
                                <th><!-- --></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="text-left">
                                    <a href="javascript: void(0);" class="cat__core__link--underlined">Server hardware purchase</a>
                                </td>
                                <td class="text-right">2
                                </td>
                                <td class="text-right">$75.00</td>
                                <td>$2,152.00</td>

                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="text-left">
                                    <a href="javascript: void(0);" class="cat__core__link--underlined">Office furniture purchase</a>
                                </td>
                                <td class="text-right">3
                                </td>
                                <td class="text-right">$169.00</td>
                                <td>$4,169.00</td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td class="text-left">
                                    <a href="javascript: void(0);" class="cat__core__link--underlined">Company Anual Dinner Catering</a>
                                </td>
                                <td class="text-right">4
                                </td>
                                <td class="text-right">$49.00</td>
                                <td>$1,260.00</td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td class="text-left">
                                    <a href="javascript: void(0);" class="cat__core__link--underlined">Payment for Jan 2016</a>
                                </td>
                                <td class="text-right">5
                                </td>
                                <td class="text-right">$12.00</td>
                                <td>$866.00</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right clearfix">
                        <div class="pull-right">
                            <p>
                                Sub - Total amount: <strong><span>$5,700.00</span></strong>
                            </p>
                            <p>
                                VAT: <strong><span>$57.00</span></strong>
                            </p>
                            <p class="page-invoice-amount">
                                <strong>Grand Total: <span>$5,757.00</span></strong>
                            </p>
                            <br />
                        </div>
                    </div>
                </section>

                <h3>
                    <i class="icmn-price-tag cat__wizard__steps__icon"></i>
                    <span class="cat__wizard__steps__title">Billing Info</span>
                </h3>
                <section>
                    <div class="row">
                        <div class="col-md-8">
                            <form>
                                <h4 class="text-black mb-3">
                                    <strong>Information Details</strong>
                                </h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="l33">First Name</label>
                                            <input type="text" class="form-control" id="l33" placeholder="First Name" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="l34">Last Name</label>
                                            <input type="text" class="form-control" id="l34" placeholder="Last Name" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="l31">Email</label>
                                            <input type="text" class="form-control" id="l31" placeholder="Email" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="l32">Phone Number</label>
                                            <input type="text" class="form-control" id="l32" placeholder="Phone Number" required="">
                                        </div>
                                    </div>
                                </div>


                                <br />
                                <br />

                                <h4 class="text-black mb-3">
                                    <strong>Billing Details</strong>
                                </h4>
                                <div class="form-group">
                                    <label for="l41">Card Number</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icmn-credit-card"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Card Number" id="l41">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="l44">Expiration Date</label>
                                            <input type="text" class="form-control" id="l44" placeholder="MM / YY" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-5 pull-right">
                                        <div class="form-group">
                                            <label for="l43">CVC Code</label>
                                            <input type="text" class="form-control" id="l43" placeholder="CVC" required="">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-black mb-3">
                                <strong>General Info</strong>
                            </h4>
                            <h2>
                                <i class="fa fa-cc-visa text-primary"></i>
                                <i class="fa fa-cc-mastercard text-default"></i>
                                <i class="fa fa-cc-amex text-default"></i>
                            </h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p> <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
                        </div>
                    </div>
                </section>

                <h3>
                    <i class="icmn-checkmark cat__wizard__steps__icon"></i>
                    <span class="cat__wizard__steps__title">Confirmation</span>
                </h3>
                <section>

                    <div class="invoice-block">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>
                                    <img src="../../modules/ecommerce/common/img/amazon.jpg" height="50" alt="Amazon">
                                </h4>
                                <address>
                                    795 Folsom Ave, Suite 600
                                    <br>
                                    San Francisco, CA, 94107
                                    <br>
                                    <abbr title="Mail">E-mail:</abbr>&nbsp;&nbsp;example@amazon.com
                                    <br>
                                    <abbr title="Phone">Phone:</abbr>&nbsp;&nbsp;(123) 456-7890
                                    <br>
                                    <abbr title="Fax">Fax:</abbr>&nbsp;&nbsp;800-692-7753
                                    <br>
                                    <br>
                                </address>
                            </div>
                            <div class="col-md-6 text-right">
                                <p>
                                    <a class="font-size-20" href="javascript:void(0)">W32567-2352-4756</a>
                                    <br>
                                    <span class="font-size-20">Artour Arteezy</span>
                                </p>
                                <address>
                                    795 Folsom Ave, Suite 600
                                    <br> San Francisco, CA, 94107
                                    <br>
                                    <abbr title="Phone">P:</abbr>&nbsp;&nbsp;(123) 456-7890
                                    <br>
                                </address>
                                <span>Invoice Date: January 20, 2016</span>
                                <br>
                                <span>Due Date: January 22, 2016</span>
                                <br>
                                <br>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover text-right">
                                <thead class="thead-default">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Description</th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">Unit Cost</th>
                                    <th class="text-right">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-left">Server hardware purchase</td>
                                    <td>35</td>
                                    <td>$75.00</td>
                                    <td>$2,152.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="text-left">Office furniture purchase</td>
                                    <td>21</td>
                                    <td>$169.00</td>
                                    <td>$4,169.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td class="text-left">Company Anual Dinner Catering</td>
                                    <td>58</td>
                                    <td>$49.00</td>
                                    <td>$1,260.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td class="text-left">Payment for Jan 2016</td>
                                    <td>231</td>
                                    <td>$12.00</td>
                                    <td>$866.00</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right clearfix">
                            <div class="pull-left">
                                <button type="button" class="btn btn-default-outline margin-top-20">
                                    <i class="icmn-printer"></i>
                                    Print
                                </button>
                            </div>
                            <div class="pull-right">
                                <p>
                                    Sub - Total amount: <strong><span>$5,700.00</span></strong>
                                </p>
                                <p>
                                    VAT: <strong><span>$57.00</span></strong>
                                </p>
                                <p class="page-invoice-amount">
                                    <strong>Grand Total: <span>$5,757.00</span></strong>
                                </p>
                                <br>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>
</section>
<!-- END: ecommerce/cart-checkout -->

<!-- START: page scripts -->
<script>
    $(function() {

        $("#cart-checkout").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: 0,
            autoFocus: true
        });

    });
</script>
<!-- END: page scripts -->

</body>
</html>
