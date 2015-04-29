<?php
/* For licensing terms, see /license.txt */

/**
 * This script displays error messages on fatal errors during initialization.
 *
 * @package chamilo.include
 * @author Ivan Tcholakov, 2009-2010
 */

$Organisation = '<a href="http://www.chamilo.org" target="_blank">Chamilo Homepage</a>';
$PoweredBy = 'Platform <a href="http://www.chamilo.org" target="_blank"> Chamilo </a> &copy; '.date('Y');

/**
 * English language variables.
 */

// Sections.
$SectionSystemRequirementsProblem = 'System requirements problem';
$SectionInstallation = 'Installation';
$SectionDatabaseUnavailable = 'Database is unavailable';
$SectionTechnicalIssues = 'Technical issues';
$SectionProtection = 'Protection measure';

// Error code.
$ErrorCode = 'Error code';

// Error code 1.
$IncorrectPhpVersionTitle = 'Incorrect PHP version';
$IncorrectPhpVersionDescription = 'Warning: we have detected that your version of PHP is %s1. To install Chamilo, you need to have PHP %s2 or superior. If you don\'t know what we\'re talking about, please contact your hosting provider or your support team.
    %s3 Read the installation guide.';

// Error code 2.
$InstallationTitle = 'Chamilo has not been installed';
$InstallationDescription = 'Click to INSTALL Chamilo %s or read the installation guide';

// Error code 3.
// Error code 4.
// Error code 5.
$DatabaseUnavailableTitle = 'Database is unavailable';
$DatabaseUnavailableDescription = 'This portal is currently experiencing database issues. Please report this to the portal administrator. Thank you for your help.';

// Error code 6.
$AlreadyInstalledTitle = 'Chamilo has already been installed';
$AlreadyInstalledDescription = 'The system has already been installed. In order to protect its contents, we have to prevent you from starting the installation script again. Please return to the main page.';

// Unspecified error.
$TechnicalIssuesTitle = 'Technical issues';
$TechnicalIssuesDescription = 'This portal is currently experiencing technical issues. Please report this to the portal administrator. Thank you for your help.';

if (is_int($global_error_code) && $global_error_code > 0) {

    if (class_exists('Template')) {
        $theme = Template::getThemeFallback().'/';
    } else {
        $theme = 'chamilo';
    }

	$css_path = 'app/Resources/public/css/';
	$themePath = $css_path.'themes/'.$theme.'/';

	$css_web_path = 'web/assets/';
	$css_file = $themePath.'default.css';
	$bootstrap_file = $css_web_path.'bootstrap/dist/css/bootstrap.min.css';
	$css_base_file = $css_path.'base.css';

    $css_list = array($bootstrap_file, $css_base_file, $css_file);

	$root_sys = str_replace('\\', '/', realpath(dirname(__FILE__).'/../../')).'/';
	$root_rel = htmlentities($_SERVER['PHP_SELF']);
	if (!empty($root_rel)) {
		$pos = strrpos($root_rel, '/');
		$root_rel = substr($root_rel, 0, $pos - strlen($root_rel) + 1);
		if (strpos($root_rel, '/main/') !== false) {
			$pos = 0;
			while (($test_pos = strpos(substr($root_rel, $pos, strlen($root_rel)), '/main/')) !== false) {
				$pos = $test_pos + 1;
			}
			$root_rel = substr($root_rel, 0, $pos);
		} elseif (strpos($root_rel, '/courses/') !== false) {
			$pos = 0;
			while (($test_pos = strpos(substr($root_rel, $pos, strlen($root_rel)), '/courses/')) !== false) {
				$pos = $test_pos + 1;
			}
			$root_rel = substr($root_rel, 0, $pos);
		}
	}

	$installation_guide_url = $root_rel.'documentation/installation_guide.html';

	$css_def = '';
    foreach ($css_list as $css_item) {
        $css_base_chamilo_file = $root_sys.$css_item;
        if (file_exists($css_base_chamilo_file)) {
            $css_def .= @file_get_contents($css_base_chamilo_file);
        }
    }

	$global_error_message = array();

	switch ($global_error_code) {
		case 1:
			$global_error_message['section'] = $SectionSystemRequirementsProblem;
			$global_error_message['title'] = $IncorrectPhpVersionTitle;
			$php_version = function_exists('phpversion') ? phpversion() : (defined('PHP_VERSION') ? PHP_VERSION : '');
			$php_version = empty($php_version) ? '' : '(PHP '.$php_version.')';
			$IncorrectPhpVersionDescription = str_replace('%s1', $php_version, $IncorrectPhpVersionDescription);
			$IncorrectPhpVersionDescription = str_replace('%s2', REQUIRED_PHP_VERSION, $IncorrectPhpVersionDescription);
			$pos = strpos($IncorrectPhpVersionDescription, '%s3');
			if ($pos !== false) {
				$length = strlen($IncorrectPhpVersionDescription);
				$read_installation_guide = substr($IncorrectPhpVersionDescription, $pos + 3, $length);
				$IncorrectPhpVersionDescription = substr($IncorrectPhpVersionDescription, 0, $pos);
				$IncorrectPhpVersionDescription .= '<br /><a class="btn btn-default" href="'.$installation_guide_url.'" target="_blank">'.$read_installation_guide.'</a>';
			}
			$global_error_message['description'] = $IncorrectPhpVersionDescription;
			break;
		case 2:
			$global_error_message['section'] = $SectionInstallation;
			$global_error_message['title'] = $InstallationTitle;
			if (($pos = strpos($InstallationDescription, '%s')) === false) {
				$InstallationDescription = 'Click to INSTALL Chamilo %s or read the installation guide';
			}
			$read_installation_guide = substr($InstallationDescription, $pos + 2);
			$InstallationDescription = '<form action="'.$root_rel.'main/install/index.php" method="get">
										<div class="install-mascot">
                                        <p class="download-info">
                                            <button class="btn btn-primary btn-large" type="submit" value="INSTALL Chamilo" >INSTALL Chamilo</button>
                                            <a class="btn btn-default btn-large" href="'.$installation_guide_url.'" target="_blank">'.$read_installation_guide.'</a>
                                        </p>
                                        </div>
										</form>';
			$global_error_message['description'] = $InstallationDescription;
			break;
		case 3:
		case 4:
		case 5:
			$global_error_message['section'] = $SectionDatabaseUnavailable;
			$global_error_message['title'] = $DatabaseUnavailableTitle;
			$global_error_message['description'] = $DatabaseUnavailableDescription;
			break;
		case 6:
			$global_error_message['section'] = $SectionProtection;
			$global_error_message['title'] = $AlreadyInstalledTitle;
			$global_error_message['description'] = $AlreadyInstalledDescription;
			break;
		default:
			$global_error_message['section'] = $SectionTechnicalIssues;
			$global_error_message['title'] = $TechnicalIssuesTitle;
			$global_error_message['description'] = $TechnicalIssuesDescription;
			break;
	}

	$show_error_codes = defined('SHOW_ERROR_CODES') && SHOW_ERROR_CODES && $global_error_code != 2;
	$global_error_message['code'] = $show_error_codes ? $ErrorCode.': '.$global_error_code.'<br /><br />' : '';
	$global_error_message['details'] = empty($global_error_message['details']) ? '' : ($show_error_codes ? ': '.$global_error_message['details'] : $global_error_message['details']);

	$global_error_message['organisation'] = $Organisation;
	$global_error_message['powered_by'] = $PoweredBy;

	$global_error_message['encoding'] = 'UTF-8';
	$global_error_message['css'] = $css_def;
	$global_error_message['chamilo_logo'] = $css_web_path.'themes/'.$theme.'/images/header-logo.png';


// {ORGANISATION}	moved from the header
	$global_error_message_page =
<<<EOM
<!DOCTYPE html>
<html>
		<head>
			<title>{TITLE}</title>
            <meta charset="{ENCODING}" />
			<style type="text/css" media="screen, projection">
				/*<![CDATA[*/
				{CSS}
				/*]]>*/
			</style>
		</head>
		<body>
		<div id="page-wrap">
			<header>
				<section id="menu-bar">
					<nav class="navbar navbar-default">
					<div class="container">
						<div class="navbar-header">
						    <a class="navbar-brand" href="#">Chamilo</a>
							<button type="button" class="navbar-toggle btn" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="navbar-collapse collapse in" id="bs-example-navbar-collapse-1" aria-expanded="true">
							<ul class="nav navbar-nav">
								<li id="current" class="active tab-homepage">
								<a target="_top" href="index.php">Homepage</a>
								</li>
							</ul>
						</div><!-- /.navbar-collapse -->
					</div><!-- /.container-fluid -->
				</nav>
				</section>
                <!--
				<section id="breadcrumb-bar">
					<div class="container">
						<ul class="breadcrumb"> <li> <a href="#">{SECTION}</a></li></ul>
					</div>
				</section>
				!-->
			</header>
			<div id="top_main_content" class="container">
				<div class="col-md-12">
					<div style="text-align:center">
						{DESCRIPTION}
						{CODE}
					</div>
				</div>
			</div>
		</div>
		<footer>
            <div class="container">
                <div class="row">
                    <div style="text-align: center;">
                    &nbsp;<br />{POWERED_BY}
                    </div>
                </div>
            </div>
		</footer>
		</body>
</html>
EOM;
	foreach ($global_error_message as $key => $value) {
		$global_error_message_page = str_replace('{'.strtoupper($key).'}', $value, $global_error_message_page);
	}
	header('Content-Type: text/html; charset='.$global_error_message['encoding']);
	die($global_error_message_page);
}
