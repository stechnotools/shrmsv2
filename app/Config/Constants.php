<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);


/*
|--------------------------------------------------------------------------
| AIO ADMIN Version
|--------------------------------------------------------------------------
|
| Defines the version number for aio admin
|
*/
define('AIOADMIN_VERSION', '2.0.0');


define('DIR_PUBLIC',  ROOTPATH . '/public/');
define('DIR_UPLOAD',   ROOTPATH . '/public/uploads/');
define('IMAGE_CACHE',   'image-cache');
define('DIR_ADMIN_MODULE', ROOTPATH . '/admin/');



$packages = array(
    'superfish' => array(
        'javascript' => array(
            'plugins/superfish/js/superfish.min.js',
        ),
        'stylesheet' => array(
            'plugins/superfish/css/superfish.css',
        ),
    ),
    'tablednd' => array(
        'javascript' => array(
            'plugins/TableDnD/js/jquery.tablednd.js',
        ),
        'stylesheet' => array(
            'plugins/tablednd/tablednd.css',
        ),
    ),
    'datetimepicker' => array(
        'javascript' => array(
            'plugins/datetimepicker/moment.js',
            'plugins/datetimepicker/bootstrap-datetimepicker.min.js',
        ),
        'stylesheet' => array(
            'plugins/datetimepicker/bootstrap-datetimepicker.min.css',

        ),
    ),
    'timepicker' => array(
        'javascript' => array(
            'plugins/datetimepicker/moment.js',
            'plugins/timepicker/bootstrap-timepicker.min.js',
        ),
        'stylesheet' => array(
            'plugins/timepicker/bootstrap-timepicker.min.css',

        ),
    ),
    'datepicker' => array(
        'javascript' => array(
            'plugins/datetimepicker/moment.js',
            'plugins/bootstrap-datepicker/bootstrap-datepicker.min.js',
        ),
        'stylesheet' => array(
            'plugins/bootstrap-datepicker/bootstrap-datepicker.css',

        ),
    ),
    'daterangepicker' => array(
        'javascript' => array(
            'plugins/daterangepicker/moment.min.js',
            'plugins/daterangepicker/daterangepicker.js',
        ),
        'stylesheet' => array(
            'plugins/daterangepicker/daterangepicker.css',

        ),
    ),
    'select2' => array(
        'javascript' => array(
            'plugins/select2/select2.min.js',
        ),
        'stylesheet' => array(
            'plugins/select2/select2.min.css'
        ),
    ),
    'ladda' => array(
        'javascript' => array(
            'plugins/Ladda/dist/spin.min.js',
            'plugins/Ladda/dist/ladda.min.js',
        ),
        'stylesheet' => array(
            'plugins/Ladda/dist/ladda.min.css',
        ),
    ),
    'sweetalert' => array(
        'javascript' => array(
            'plugins/sweetalert2/sweetalert2.min.js',
        ),
        'stylesheet' => array(
            'plugins/sweetalert2/sweetalert2.min.css',
        ),
    ),
    'elevatezoom' => array(
        'javascript' => array(
            'plugins/elevatezoom/jquery.elevatezoom.js',
        ),

    ),
    'jsmediatags' => array(
        'javascript' => array(
            'plugins/jsmediatags/dist/jsmediatags.min.js',
        ),
    ),
    'nprogress' => array(
        'javascript' => array(
            'plugins/nprogress/nprogress.js',
        ),
        'stylesheet' => array(
            'plugins/nprogress/nprogress.css',
        ),
    ),
    'colorpicker' => array(
        'javascript' => array(
            'plugins/colorpicker/bootstrap-colorpicker.js',
        ),
        'stylesheet' => array(
            'plugins/colorpicker/colorpicker.css',
        ),
    ),
    'tags' => array(
        'javascript' => array(
            'plugins/tagsinput/jquery.tagsinput.min.js',
        ),
        'stylesheet' => array(
            'plugins/tagsinput/jquery.tagsinput.css',
        ),
    ),

    'notification' => array(
        'javascript' => array(
            'plugins/notifyjs/dist/notify.min.js',
            'plugins/notifications/notify-metro.js',
            'plugins/notifications/notifications.js',
        ),
        'stylesheet' => array(
            'plugins/notifications/notification.css',
        ),
    ),

    'toastr' => array(
        'javascript' => array(
            'plugins/toastr/toastr.min.js',
        ),
        'stylesheet' => array(
            'plugins/toastr/toastr.min.css',
        ),
    ),

    'selectize' => array(
        'javascript' => array(
            'plugins/selectize/dist/js/standalone/selectize.min.js',
        ),
        'stylesheet' => array(
            'plugins/selectize/dist/css/selectize.css',
        ),
    ),

    'jqueryform' => array(
        'javascript' => array(
            'plugins/jquery_form/jquery.form.js',
        )
    ),

    'mediaelement' => array(
        'javascript' => array(
            'plugins/mediaelement/build/mediaelement-and-player.min.js',
        ),
        'stylesheet' => array(
            'plugins/mediaelement/build/mediaelementplayer.min.css',
        ),
    ),

    'jplayer' => array(
        'javascript' => array(
            'plugins/jPlayer/dist/jplayer/jquery.jplayer.min.js',
        )
    ),

    'jqueryupload' => array(
        'javascript' => array(
            'plugins/JavaScript-Load-Image/js/load-image.all.min.js',
            'plugins/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js',
            'plugins/jQuery-File-Upload/js/jquery.iframe-transport.js',
            'plugins/jQuery-File-Upload/js/jquery.fileupload.js',
            'plugins/jQuery-File-Upload/js/jquery.fileupload-process.js',
            'plugins/jQuery-File-Upload/js/jquery.fileupload-image.js',
            'plugins/jQuery-File-Upload/js/jquery.fileupload-audio.js',
            'plugins/jQuery-File-Upload/js/jquery.fileupload-video.js',
            'plugins/jQuery-File-Upload/js/jquery.fileupload-validate.js',
            'plugins/jQuery-File-Upload/js/jquery.fileupload-ui.js',
        ),
        'stylesheet' => array(
            'plugins/jQuery-File-Upload/css/jquery.fileupload.css',
            /*'plugins/jQuery-File-Upload/css/jquery.fileupload-ui.css',*/
        ),
    ),

    'pace' => array(
        'javascript' => array(
            'plugins/pace/pace.min.js',
        ),
        'stylesheet' => array(
            'plugins/pace/pace.css',
        ),
    ),
    'ckeditor' => array(
        'javascript' => array(
            'plugins/ckeditor/ckeditor.js',
            'plugins/ckeditor/adapters/jquery.js',
        ),
    ),
    'ckfinder' => array(
        'javascript' => array(
            'plugins/ckfinder/ckfinder.js',
        ),
    ),
    'colorbox' => array(
        'javascript' => array(
            'plugins/colorbox/jquery.colorbox.js',
        ),
        'stylesheet' => array(
            'plugins/colorbox/colorbox.css',
        ),
    ),
    'datatable-old' => array(
        'javascript' => array(
            'plugins/datatables/jquery.dataTables.min.js',
            'plugins/datatables/dataTables.bootstrap4.min.js',
            'plugins/datatables/dataTables.buttons.min.js',
            'plugins/datatables/buttons.bootstrap4.min.js',
            'plugins/datatables/jszip.min.js',
            'plugins/datatables/pdfmake.min.js',
            'plugins/datatables/vfs_fonts.js',
            'plugins/datatables/buttons.html5.min.js',
            'plugins/datatables/buttons.print.min.js',
            'plugins/datatables/dataTables.fixedHeader.min.js',
            'plugins/datatables/dataTables.keyTable.min.js',
            'plugins/datatables/dataTables.scroller.min.js',
            'plugins/datatables/dataTables.responsive.min.js',
            'plugins/datatables/responsive.bootstrap4.min.js',
        ),
        'stylesheet' => array(
            'plugins/datatables/jquery.dataTables.min.css',
            'plugins/datatables/buttons.bootstrap4.min.css',
            'plugins/datatables/fixedHeader.bootstrap4.min.css',
            'plugins/datatables/responsive.bootstrap4.min.css',
            'plugins/datatables/dataTables.bootstrap4.min.css',
            'plugins/datatables/scroller.bootstrap4.min.css',
        ),
    ),
    'table_export' => array(
        'javascript' => array(
            'plugins/jquery-table2excel/dist/jquery.table2excel.min.js',
        ),

    ),
    'datatable' => array(
        'javascript' => array(
            'plugins/datatables/datatables.min.js',
        ),
        'stylesheet' => array(
            'plugins/datatables/datatables.min.css',
        ),
    ),
    'datatable_export' => array(
        'javascript' => array(
            'plugins/datatables/dataTables.buttons.min.js',
            'plugins/datatables/buttons.bootstrap.min.js',
            'plugins/datatables/jszip.min.js',
            'plugins/datatables/pdfmake.min.js',
            'plugins/datatables/vfs_fonts.js',
            'plugins/datatables/buttons.html5.min.js',
            'plugins/datatables/buttons.print.min.js',
            'plugins/datatables/dataTables.scroller.min.js',
        ),
        'stylesheet' => array(
            'plugins/datatables/buttons.bootstrap.min.css',
            'plugins/datatables/scroller.bootstrap.min.css',
        ),
    ),



    'morris_chart' => array(
        'javascript' => array(
            'plugins/morris.js/morris.min.js',
            'plugins/raphael/raphael-min.js',
        ),
        'stylesheet' => array(
            'plugins/morris.js/morris.css',
        ),
    ),

    'sliderPro' => array(
        'javascript' => array(
            'plugins/slider-pro-master/js/jquery.sliderPro.min.js',
        ),

    ),

    'isotope' => array(
        'javascript' => array(
            'plugins/isotope/js/isotope.pkgd.min.js',
            'plugins/imagesloaded/js/imagesloaded.pkgd.js',
            'plugins/magnific-popup/js/magnific-popup.min.js',
        )

    ),

    'magnific-popup'=>array(
        'javascript' => array(
            'plugins/magnific-popup/js/magnific-popup.min.js',
        ),
        'stylesheet' => array(
            'plugins/magnific-popup/css/magnific-popup.css',
        ),
    ),

    'fancybox' => array(
        'javascript' => array(
            'plugins/fancybox/dist/jquery.fancybox.min.js',
        ),
        'stylesheet' => array(
            'plugins/fancybox/dist/jquery.fancybox.min.css',
        ),
    ),

    'owlcarousel' => array(
        'javascript' => array(
            'plugins/owl.carousel/js/owl.carousel.min.js',
        ),
        'stylesheet' => array(
            'plugins/owl.carousel/css/owl.carousel.min.css',
        ),
    ),

    'bdropdown' => array(
        'javascript' => array(
            'admin/storage/js/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        ),
    ),
    'slimscroll' => array(
        'javascript' => array(
            'admin/storage/js/plugins/jquery.slimscroll/jquery.slimscroll.min.js',
        ),
    ),
    'sidebar' => array(
        'javascript' => array(
            'admin/storage/js/sidebar.js',
        ),
    ),
    'panels' => array(
        'javascript' => array(
            'admin/storage/js/panels.js',
        ),
    ),

    'app' => array(
        'javascript' => array(
            'admin/storage/js/app.js',
        ),
    ),




    'admin_jqueryui' => array(
        'javascript' => array(
            'admin/storage/js/jqueryui/jquery-ui-1.10.4.custom.js',
            'admin/storage/js/jquery-ui-timepicker-addon.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/jqueryui/smoothness/jquery-ui-1.10.4.custom.css',
        ),
    ),
    'icheck' => array(
        'javascript' => array(
            'admin/storage/js/plugins/icheck/icheck.min.js',
        ),
        'stylesheet' => array(
            'admin/storage/css/plugins/icheck/square/grey.css',
        ),
    ),

    'helpers' => array(
        'javascript' => array(
            'admin/storage/js/helpers.js',
        ),
    ),



    'nestedSortable' => array(
        'javascript' => array(
            'admin/storage/js/nested_sortable/jquery.ui.nestedSortable.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/nested_sortable/jquery.ui.nestedSortable.css',
        ),
    ),
    'jquerynestable' => array(
        'javascript' => array(
            'plugins/jquery_nestable/jquery.nestable.js',
        ),
        'stylesheet' => array(
            'plugins/jquery_nestable/jquery.nestable.css',
        ),
    ),
    'codemirror' => array(
        'javascript' => array(
            'admin/storage/js/codemirror-2.25/lib/codemirror.js',
            'admin/storage/js/codemirror-2.25/mode/xml/xml.js',
            'admin/storage/js/codemirror-2.25/mode/javascript/javascript.js',
            'admin/storage/js/codemirror-2.25/mode/css/css.js',
            'admin/storage/js/codemirror-2.25/mode/clike/clike.js',
            'admin/storage/js/codemirror-2.25/mode/php/php.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/codemirror-2.25/lib/codemirror.css',
        ),
    ),

);
define('PACKAGES', serialize($packages));