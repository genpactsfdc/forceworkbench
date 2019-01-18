<?php
require_once "shared.php";
require_once "session.php";
require_once "controllers/LoginController.php";


$c = new LoginController();
if (isset($_POST['uiLogin'])
    || !empty($_REQUEST["pw"])
    || !empty($_REQUEST["sid"])
    || isset($_POST["oauth_Login"])
    || isset($_GET["code"])
    || isset($_POST["signed_request"])
) {
    $c->processRequest();
}

require_once "header.php";
?>

<p>
    <?php if (count($c->getErrors()) > 0) displayError($c->getErrors()) ?>
</p>

<div id="loginBlockContainer">
    <form id="login_form" action="login.php" method="post">
        <?php print getCsrfFormTag(); ?>
        <input type="hidden" id="startUrl" name="startUrl" value="<?php print htmlspecialchars($c->getStartUrl(), ENT_QUOTES); ?>">
        <div id="login_type_selection" style="text-align: right; <?php if ($c->isOAuthRequired()) { print "display:none;"; } ?>">
            <input type="radio" id="loginType_std" name="loginType" value="std"/>
            <label for="loginType_std">Standard</label>

            <input type="radio" id="loginType_adv" name="loginType" value="adv"/>
            <label for="loginType_adv">Advanced</label>

             <?php if ($c->isOAuthEnabled()) { ?>
            <input type="radio" id="loginType_oauth" name="loginType" value="oauth"/>
            <label for="loginType_oauth">OAuth</label>
            <?php } ?>
        </div>

        <div class="loginType_oauth">
            <p>
                <label for="inst">Environment:</label>
                <select id="oauth_env" name="oauth_host" style="width: 200px;">
                    <?php printSelectOptions($c->getOauthHostSelectOptions()); ?>
                </select>
            </p>

            <p>
                <label for="api">API Version:</label>
                <select id="oauth_apiVersion" name="oauth_apiVersion" style="width: 200px;">
                    <?php printSelectOptions($c->getApiVersionSelectOptions(), $c->getApiVersion()); ?>
                </select>
            </p>
        </div>

        <div class="loginType_std loginType_adv">
            <p>
                <label for="un">Username:</label>
                <input type="text" id="un" name="un"size="55" value="<?php print htmlspecialchars($c->getUsername()); ?>"/>
            </p>

            <p>
                <label for="pw">Password:</label>
                <input type="password" id="pw" name="pw" size="55"/>
            </p>

            <div style="margin-left: 95px;">
                <input type="checkbox" id="rememberUser" name="rememberUser" <?php if ($c->isUserRemembered()) print "checked='checked'" ?> />
                <label for="rememberUser">Remember username</label>
                <span id="pwcaps" style="visibility: hidden; color: red; font-weight: bold; margin-left: 65px;">Caps lock is on!</span>
            </div>
        </div>

        <div class="loginType_adv">
            <p>
                <em>- OR -</em>
            </p>

            <p>
                <label for="sid">Session ID:</label>
                <input type="text" id="sid" name="sid" size="55">
            </p>

            <p>&nbsp;</p>

            <p>
                <label for="serverUrl">Server URL:</label>
                <input type="text" name="serverUrl" id="serverUrl" size="55" />
            </p>

            <p>
                <label for="inst">QuickSelect:</label>
                <select id="inst" name="inst">
                    <?php printSelectOptions($c->getSubdomainSelectOptions(), $c->getSubdomain()); ?>
                </select>
                &nbsp;
                <select id="api" name="api">
                    <?php printSelectOptions($c->getApiVersionSelectOptions(), $c->getApiVersion()); ?>
                </select>
            </p>
        </div>

        <div class="loginType_std loginType_oauth loginType_adv">
            <?php if ($c->getTermsFile()) { ?>
            <div style="margin-left: 95px;">
                <input type="checkbox" id="termsAccepted" name="termsAccepted"/>
                <label for="termsAccepted"><a href="terms.php" target="_blank">I agree to the terms of service</a></label>
            </div>
            <?php } ?>

            <p>
                <strong>Genpact Workbench is deployed for internal Genpact employees only.</strong>
            </p>
            <p>
                Whitelist following Dynamic IP in your profile before logging in:
            </p>
            <p>
                Server IP : <?php echo $_SERVER['SERVER_ADDR'];?>
            </p>

            <p>
                <div style="text-align: right;">
                    <input type="submit" id="loginBtn" name="uiLogin" value="Login">
                </div>
            </p>
        </div>
    </form>
</div>
    
<?php
addFooterScript("<script type='text/javascript' src='" . getPathToStaticResource('/script/login.js') . "'></script>");
addFooterScript("<script type='text/javascript'>wbLoginConfig=" . $c->getJsConfig() ."</script>");
addFooterScript("<script type='text/javascript'>WorkbenchLogin.initializeForm(" . json_encode($c->getLoginType()) .");</script>");
require_once "footer.php";
?>
<style type='text/css'>
	.embeddedServiceHelpButton .helpButton .uiButton {
		background-color: #005290;
		font-family: "Salesforce Sans", sans-serif;
	}
	.embeddedServiceHelpButton .helpButton .uiButton:focus {
		outline: 1px solid #005290;
	}
	@font-face {
		font-family: 'Salesforce Sans';
		src: url('https://www.sfdcstatic.com/system/shared/common/assets/fonts/SalesforceSans/SalesforceSans-Regular.woff') format('woff'),
		url('https://www.sfdcstatic.com/system/shared/common/assets/fonts/SalesforceSans/SalesforceSans-Regular.ttf') format('truetype');
	}
</style>

<script type='text/javascript' src='https://service.force.com/embeddedservice/5.0/esw.min.js'></script>
<script type='text/javascript'>
	var initESW = function(gslbBaseURL) {
		embedded_svc.settings.displayHelpButton = true; //Or false
		embedded_svc.settings.language = ''; //For example, enter 'en' or 'en-US'

		//embedded_svc.settings.defaultMinimizedText = '...'; //(Defaults to Chat with an Expert)
		//embedded_svc.settings.disabledMinimizedText = '...'; //(Defaults to Agent Offline)

		//embedded_svc.settings.loadingText = ''; //(Defaults to Loading)
		//embedded_svc.settings.storageDomain = 'yourdomain.com'; //(Sets the domain for your deployment so that visitors can navigate subdomains during a chat session)

		// Settings for Live Agent
		//embedded_svc.settings.directToButtonRouting = function(prechatFormData) {
			// Dynamically changes the button ID based on what the visitor enters in the pre-chat form.
			// Returns a valid button ID.
		//};
		//embedded_svc.settings.prepopulatedPrechatFields = {}; //Sets the auto-population of pre-chat form fields
		//embedded_svc.settings.fallbackRouting = []; //An array of button IDs, user IDs, or userId_buttonId
		//embedded_svc.settings.offlineSupportMinimizedText = '...'; //(Defaults to Contact Us)

		embedded_svc.settings.enabledFeatures = ['LiveAgent'];
		embedded_svc.settings.entryFeature = 'LiveAgent';

		embedded_svc.init(
			'https://genpact--dev2.cs57.my.salesforce.com',
			'https://dev2-partner-genpact.cs57.force.com/',
			gslbBaseURL,
			'00D0k000000DpYq',
			'SFDCHelpdesk',
			{
				baseLiveAgentContentURL: 'https://c.la1-c1cs-ukb.salesforceliveagent.com/content',
				deploymentId: '5720k0000008OT3',
				buttonId: '5730k0000008OQJ',
				baseLiveAgentURL: 'https://d.la1-c1cs-ukb.salesforceliveagent.com/chat',
				eswLiveAgentDevName: 'SFDCHelpdesk',
				isOfflineSupportEnabled: true
			}
		);
	};

	if (!window.embedded_svc) {
		var s = document.createElement('script');
		s.setAttribute('src', 'https://genpact--dev2.cs57.my.salesforce.com/embeddedservice/5.0/esw.min.js');
		s.onload = function() {
			initESW(null);
		};
		document.body.appendChild(s);
	} else {
		initESW('https://service.force.com');
	}
</script>
