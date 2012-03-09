<?
    $globals = array();

    //set timezone to pacific (server is eastern)
    //date_default_timezone_set('America/Los_Angeles');
    putenv("TZ=US/Pacific");

    session_start();

    //$globals['domain_name'] = "unitedhero.loudcanvas";
    $globals['domain_name'] = "unitedhero";
    $globals['domain_ext'] = ".com";
    $globals['site_year'] = "2008";
    $globals['site_name'] = $globals['domain_name'] . $globals['domain_ext'];

    $globals['site_root_dir'] = $_SERVER['DOCUMENT_ROOT'] . "/";
    $globals['html_root_dir'] = "/";

    $globals['webmaster_email'] = 'sdempsey@loudcanvas.com';
    $globals['sales_email'] = "sales@" . $globals['site_name'];

    $globals['contact_email'] = 'sdempsey@loudcanvas.com';
    //$globals['contact_email'] = 'mattd@unitedhero.com';
    $globals['contact_email'] = "info@" . $globals[site_name];

	//emails from the website
	$globals['emails_from'] = "noreply@" . $globals[site_name];
    $globals['contact_email_name'] = "United Hero";
    $globals['emails_from'] = $globals['contact_email_name'] . "<" . $globals['emails_from'] . ">";

    $error_msg = array();
    $report_msg = array();

    global $globals;

    require_once("includes/database.php");
    require_once('includes/uh.php');
    require_once('includes/emailing.php');

    require_once("/home/loudcanv/public_html/includes/functions.php");
    require_once("/home/loudcanv/public_html/includes/header_functions.php");
    require_once("/home/loudcanv/public_html/includes/form_functions.php");
    require_once("/home/loudcanv/public_html/includes/uploading.php");

    //require_once("/home/loudcanv/public_html/includes/emailing.php");
    //require_once('includes/ajax_functions.php');
?>