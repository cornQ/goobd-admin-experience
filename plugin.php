<?php
/*
Plugin Name: goo.bd Admin Experience
Plugin URI: https://goo.bd/
Description: CORNQ-aligned branding for YOURLS admin/login with a 30-day Remember Me option.
Version: 1.24.7
Author: CORNQ
Author URI: https://cornq.com/
*/

if ( ! defined( 'YOURLS_ABSPATH' ) ) {
    die();
}

if ( ! defined( 'GOOBD_AE_VERSION' ) ) {
    define( 'GOOBD_AE_VERSION', '1.24.7' );
}

if ( ! defined( 'GOOBD_AE_REMEMBER_DAYS' ) ) {
    define( 'GOOBD_AE_REMEMBER_DAYS', 30 );
}

/**
 * Limit signature-reset handling and presentation to the native Tools page.
 */
function goobd_ae_is_tools_request() {
    if ( empty( $_SERVER['SCRIPT_NAME'] ) ) {
        return false;
    }

    return basename( (string) $_SERVER['SCRIPT_NAME'] ) === 'tools.php';
}

/**
 * Return the next non-whitespace/comment token index.
 */
function goobd_ae_next_config_token( $tokens, $index ) {
    $count = count( $tokens );

    while ( $index < $count ) {
        $token = $tokens[ $index ];
        if (
            ! is_array( $token )
            || ! in_array( $token[0], array( T_WHITESPACE, T_COMMENT, T_DOC_COMMENT ), true )
        ) {
            return $index;
        }
        $index++;
    }

    return false;
}

/**
 * Read a quoted PHP string token without evaluating config code.
 */
function goobd_ae_config_string_value( $literal ) {
    if ( strlen( $literal ) < 2 ) {
        return null;
    }

    $quote = $literal[0];
    if ( ( $quote !== "'" && $quote !== '"' ) || substr( $literal, -1 ) !== $quote ) {
        return null;
    }

    $value = substr( $literal, 1, -1 );
    if ( $quote === "'" ) {
        return str_replace( array( "\\\\", "\\'" ), array( "\\", "'" ), $value );
    }

    return stripcslashes( $value );
}

/**
 * Replace exactly one literal YOURLS_COOKIEKEY definition in config.php.
 */
function goobd_ae_replace_cookie_key( $contents, $new_key ) {
    $tokens  = token_get_all( $contents );
    $texts   = array();
    $offsets = array();
    $offset  = 0;

    foreach ( $tokens as $index => $token ) {
        $text              = is_array( $token ) ? $token[1] : $token;
        $texts[ $index ]   = $text;
        $offsets[ $index ] = $offset;
        $offset           += strlen( $text );
    }

    $matches = array();
    $count   = count( $tokens );

    for ( $index = 0; $index < $count; $index++ ) {
        $token = $tokens[ $index ];
        if ( ! is_array( $token ) || $token[0] !== T_STRING || strcasecmp( $token[1], 'define' ) !== 0 ) {
            continue;
        }

        $open = goobd_ae_next_config_token( $tokens, $index + 1 );
        if ( $open === false || $texts[ $open ] !== '(' ) {
            continue;
        }

        $name = goobd_ae_next_config_token( $tokens, $open + 1 );
        if (
            $name === false
            || ! is_array( $tokens[ $name ] )
            || $tokens[ $name ][0] !== T_CONSTANT_ENCAPSED_STRING
            || goobd_ae_config_string_value( $texts[ $name ] ) !== 'YOURLS_COOKIEKEY'
        ) {
            continue;
        }

        $comma = goobd_ae_next_config_token( $tokens, $name + 1 );
        $value = $comma === false ? false : goobd_ae_next_config_token( $tokens, $comma + 1 );
        if (
            $comma === false
            || $texts[ $comma ] !== ','
            || $value === false
            || ! is_array( $tokens[ $value ] )
            || $tokens[ $value ][0] !== T_CONSTANT_ENCAPSED_STRING
        ) {
            continue;
        }

        $matches[] = $value;
    }

    if ( count( $matches ) !== 1 ) {
        return array(
            'success' => false,
            'message' => count( $matches )
                ? 'Multiple YOURLS_COOKIEKEY definitions were found. Reset it manually to avoid changing the wrong configuration.'
                : 'A literal YOURLS_COOKIEKEY definition was not found. Reset it manually in your YOURLS config file.',
        );
    }

    $value_index = $matches[0];
    $replacement = var_export( $new_key, true );
    $updated     = substr( $contents, 0, $offsets[ $value_index ] )
        . $replacement
        . substr( $contents, $offsets[ $value_index ] + strlen( $texts[ $value_index ] ) );

    return array( 'success' => true, 'contents' => $updated );
}

/**
 * Atomically rotate YOURLS_COOKIEKEY while preserving the config file mode.
 */
function goobd_ae_rotate_cookie_key() {
    if ( ! defined( 'YOURLS_CONFIGFILE' ) ) {
        return 'The active YOURLS config file could not be located.';
    }

    $config_file = YOURLS_CONFIGFILE;
    if ( ! is_file( $config_file ) || ! is_readable( $config_file ) ) {
        return 'The active YOURLS config file is not readable.';
    }
    if ( ! is_writable( $config_file ) ) {
        return 'The active YOURLS config file is not writable. Update its permissions temporarily or reset the key manually.';
    }

    $contents = file_get_contents( $config_file );
    if ( $contents === false ) {
        return 'The active YOURLS config file could not be read.';
    }

    try {
        $new_key = bin2hex( random_bytes( 32 ) );
    } catch ( Exception $exception ) {
        yourls_debug_log( 'goo.bd Admin Experience could not generate a new YOURLS_COOKIEKEY.' );
        return 'A cryptographically secure key could not be generated on this server.';
    }

    $replacement = goobd_ae_replace_cookie_key( $contents, $new_key );
    if ( empty( $replacement['success'] ) ) {
        return $replacement['message'];
    }

    $directory = dirname( $config_file );
    $temp_file = tempnam( $directory, '.goobd-cookiekey-' );
    if ( $temp_file === false ) {
        return 'A temporary config file could not be created safely.';
    }

    $permissions = fileperms( $config_file );
    $written     = file_put_contents( $temp_file, $replacement['contents'], LOCK_EX );
    if ( $written !== strlen( $replacement['contents'] ) ) {
        @unlink( $temp_file );
        return 'The updated configuration could not be written completely.';
    }

    if ( $permissions !== false ) {
        @chmod( $temp_file, $permissions & 0777 );
    }

    $saved = file_get_contents( $temp_file );
    if ( $saved === false || ! hash_equals( $replacement['contents'], $saved ) ) {
        @unlink( $temp_file );
        return 'The updated configuration could not be verified.';
    }

    if ( ! @rename( $temp_file, $config_file ) ) {
        @unlink( $temp_file );
        return 'The server could not replace the config file atomically. No key was changed.';
    }

    clearstatcache( true, $config_file );
    if ( function_exists( 'opcache_invalidate' ) ) {
        @opcache_invalidate( $config_file, true );
    }
    return true;
}

/**
 * Handle the reset only after YOURLS has authenticated the current admin.
 */
yourls_add_action( 'login', 'goobd_ae_handle_signature_reset', 99 );
function goobd_ae_handle_signature_reset() {
    if (
        ! goobd_ae_is_tools_request()
        || empty( $_SERVER['REQUEST_METHOD'] )
        || strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) !== 'POST'
        || empty( $_POST['goobd_ae_action'] )
        || ! is_string( $_POST['goobd_ae_action'] )
        || $_POST['goobd_ae_action'] !== 'reset_signature'
    ) {
        return;
    }

    $nonce = isset( $_POST['goobd_ae_signature_nonce'] ) && is_string( $_POST['goobd_ae_signature_nonce'] )
        ? $_POST['goobd_ae_signature_nonce']
        : '';
    yourls_verify_nonce( 'goobd_ae_reset_signature', $nonce );

    if ( empty( $_POST['goobd_ae_confirm_reset'] ) || $_POST['goobd_ae_confirm_reset'] !== '1' ) {
        $GLOBALS['goobd_ae_signature_reset_error'] = 'Confirm the reset impact before continuing.';
        return;
    }

    $result = goobd_ae_rotate_cookie_key();
    if ( $result !== true ) {
        $GLOBALS['goobd_ae_signature_reset_error'] = $result;
        return;
    }

    // The in-memory constant still contains the old key, so never render the old token again.
    yourls_store_cookie( '' );
    $redirect = yourls_add_query_arg(
        'goobd_signature_reset',
        '1',
        yourls_admin_url( 'tools.php' )
    );
    yourls_redirect( $redirect );
    exit;
}

/**
 * Render a progressively enhanced reset card; JavaScript moves it into the API section.
 */
yourls_add_action( 'admin_notices', 'goobd_ae_signature_reset_panel' );
function goobd_ae_signature_reset_panel() {
    if ( ! goobd_ae_is_tools_request() || ! yourls_is_private() ) {
        return;
    }

    $error    = isset( $GLOBALS['goobd_ae_signature_reset_error'] )
        ? (string) $GLOBALS['goobd_ae_signature_reset_error']
        : '';
    $success  = isset( $_GET['goobd_signature_reset'] ) && $_GET['goobd_signature_reset'] === '1';
    $writable = defined( 'YOURLS_CONFIGFILE' ) && is_writable( YOURLS_CONFIGFILE );
    ?>
    <section id="goobd-signature-reset" class="goobd-signature-reset" aria-labelledby="goobd-signature-reset-title">
        <div class="goobd-signature-reset-copy">
            <h3 id="goobd-signature-reset-title">Reset API signature token</h3>
            <p>Rotate the secret used by YOURLS and immediately invalidate every existing API signature, admin session, and nonce.</p>
        </div>

        <?php if ( $success ) { ?>
            <p class="goobd-signature-status goobd-signature-status-success" role="status">The signature token was reset successfully. The token shown above is the new token.</p>
        <?php } ?>

        <?php if ( $error ) { ?>
            <p class="goobd-signature-status goobd-signature-status-error" role="alert"><?php echo yourls_esc_html( $error ); ?></p>
        <?php } elseif ( ! $writable ) { ?>
            <p class="goobd-signature-status goobd-signature-status-error" role="alert">Automatic reset is unavailable because the active YOURLS config file is not writable.</p>
        <?php } ?>

        <form class="goobd-signature-reset-form" action="<?php echo yourls_esc_url( yourls_admin_url( 'tools.php' ) ); ?>" method="post">
            <input type="hidden" name="goobd_ae_action" value="reset_signature" />
            <?php yourls_nonce_field( 'goobd_ae_reset_signature', 'goobd_ae_signature_nonce' ); ?>
            <label class="goobd-signature-confirm">
                <input type="checkbox" name="goobd_ae_confirm_reset" value="1" required <?php echo $writable ? '' : 'disabled'; ?> />
                <span>I understand that API integrations must be updated and all admins will need to sign in again.</span>
            </label>
            <div class="goobd-signature-reset-actions">
                <button type="submit" class="button goobd-signature-reset-button" <?php echo $writable ? '' : 'disabled'; ?>>Reset signature token</button>
            </div>
        </form>
    </section>
    <?php
}

yourls_add_filter( 'html_title', 'goobd_ae_html_title' );
function goobd_ae_html_title( $title, $context = '' ) {
    if ( $context === 'login' ) {
        return 'Sign in | goo.bd';
    }
    if ( $context === 'index' ) {
        return 'Dashboard | goo.bd';
    }
    return 'goo.bd | URL Shortener';
}

/** Use product language while preserving the native dashboard URL and menu key. */
yourls_add_filter( 'admin_links', 'goobd_ae_admin_links' );
function goobd_ae_admin_links( $links ) {
    if ( isset( $links['admin'] ) && is_array( $links['admin'] ) ) {
        $links['admin']['anchor'] = 'Dashboard';
        $links['admin']['title']  = 'Go to the dashboard';
    }

    return $links;
}

yourls_add_filter( 'html_footer_text', 'goobd_ae_footer_text' );
function goobd_ae_footer_text( $footer ) {
    $year = date( 'Y' );
    return '&copy; ' . $year . ' goo.bd powered by '
        . '<a href="https://cornq.com/" target="_blank" rel="noopener noreferrer"><strong>CORNQ</strong></a>';
}

/**
 * Product header. YOURLS prints this hook directly after its native logo,
 * so the native logo can be hidden without touching core files.
 */
yourls_add_action( 'html_logo', 'goobd_ae_brand_header' );
function goobd_ae_brand_header() {
    $admin_url = yourls_admin_url( 'index.php' );
    ?>
    <header id="goobd-brand" class="goobd-brand" aria-label="goo.bd by CORNQ">
        <div class="goobd-brand-inner">
            <a class="goobd-brand-link" href="<?php echo yourls_esc_url( $admin_url ); ?>" aria-label="goo.bd dashboard">
                <span class="goobd-product-name">goo.bd</span>
                <span class="goobd-brand-attribution">by CORNQ</span>
            </a>
            <span class="goobd-tagline">Short links. Made simple.</span>
        </div>
    </header>
    <?php
}

/** Compact login heading. Branding lives in the shared page header. */
yourls_add_action( 'login_form_top', 'goobd_ae_login_intro' );
function goobd_ae_login_intro() {
    ?>
    <?php if ( isset( $_GET['goobd_signature_reset'] ) && $_GET['goobd_signature_reset'] === '1' ) { ?>
        <p class="goobd-login-security-notice" role="status">The API signature token was reset. Sign in again to continue.</p>
    <?php } ?>
    <div class="goobd-login-intro">
        <h1>Sign in</h1>
    </div>
    <?php
}

yourls_add_action( 'login_form_bottom', 'goobd_ae_remember_field', 99 );
function goobd_ae_remember_field() {
    ?>
    <p class="goobd-remember-row">
        <label class="goobd-remember-label" for="goobd_remember">
            <input type="checkbox" id="goobd_remember" name="goobd_remember" value="1" />
            <span>Keep me signed in for <?php echo (int) GOOBD_AE_REMEMBER_DAYS; ?> days</span>
        </label>
    </p>
    <?php
}

yourls_add_filter( 'get_cookie_life', 'goobd_ae_cookie_life' );
function goobd_ae_cookie_life( $life ) {
    if (
        isset( $_SERVER['REQUEST_METHOD'] )
        && strtoupper( $_SERVER['REQUEST_METHOD'] ) === 'POST'
        && isset( $_POST['goobd_remember'] )
        && $_POST['goobd_remember'] === '1'
    ) {
        return 60 * 60 * 24 * GOOBD_AE_REMEMBER_DAYS;
    }
    return $life;
}

yourls_add_action( 'html_head', 'goobd_ae_head' );
function goobd_ae_head( $context = '' ) {
    $plugin_dir = basename( dirname( __FILE__ ) );
    $css_url = rtrim( yourls_site_url( false ), '/' )
        . '/user/plugins/' . rawurlencode( $plugin_dir )
        . '/assets/admin.css?v=' . rawurlencode( GOOBD_AE_VERSION );
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="theme-color" content="#182c45" />
    <meta name="application-name" content="goo.bd" />
    <meta name="robots" content="noindex, nofollow, noarchive" />
    <link rel="stylesheet" href="<?php echo yourls_esc_url( $css_url ); ?>" type="text/css" media="screen" />
    <script>
    (function(){
      var pendingDelete = null;

      function showStatusToast(message){
        var region = document.getElementById('goobd-status-region');
        if(!region){
          region = document.createElement('div');
          region.id = 'goobd-status-region';
          region.className = 'goobd-status-region';
          region.setAttribute('aria-live','polite');
          region.setAttribute('aria-atomic','true');
          document.body.appendChild(region);
        }

        var toast = document.createElement('div');
        toast.className = 'goobd-status-toast goobd-status-toast-success';
        toast.setAttribute('role','status');
        toast.textContent = message;
        region.appendChild(toast);
        window.requestAnimationFrame(function(){
          toast.classList.add('goobd-status-toast-visible');
        });
        window.setTimeout(function(){
          toast.classList.remove('goobd-status-toast-visible');
          window.setTimeout(function(){
            if(toast.parentNode) toast.parentNode.removeChild(toast);
          }, 220);
        }, 2800);
      }

      function enhanceDeleteFeedback(){
        var dialog = document.getElementById('delete-confirm-dialog');
        if(!dialog || dialog.dataset.goobdFeedbackReady === '1') return;
        var confirmButton = dialog.querySelector('.button-group input[type="button"]');
        var idField = dialog.querySelector('input[name="keyword_id"]');
        if(!confirmButton || !idField) return;

        dialog.dataset.goobdFeedbackReady = '1';
        confirmButton.addEventListener('click', function(){
          var id = idField.value;
          var row = id ? document.getElementById('id-' + id) : null;
          var keyword = row ? row.querySelector('td.keyword > a') : null;
          pendingDelete = {
            id: id,
            label: keyword ? keyword.textContent.trim() : ''
          };

          window.setTimeout(function(){
            if(pendingDelete && pendingDelete.id === id) pendingDelete = null;
          }, 15000);
        });
      }

      function announceCompletedDelete(){
        if(!pendingDelete || !pendingDelete.id) return;
        if(document.getElementById('id-' + pendingDelete.id)) return;

        var message = pendingDelete.label
          ? 'Deleted short link: ' + pendingDelete.label
          : 'Short link deleted.';
        pendingDelete = null;
        showStatusToast(message);
      }

      function scrollToSharePanel(){
        var panel = document.getElementById('shareboxes');
        if(!panel) return;

        window.setTimeout(function(){
          if(!panel.isConnected || window.getComputedStyle(panel).display === 'none') return;
          var reducedMotion = window.matchMedia
            && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

          try {
            panel.scrollIntoView({
              behavior: reducedMotion ? 'auto' : 'smooth',
              block: 'start'
            });
          } catch(error) {
            panel.scrollIntoView(true);
          }
        }, 340);
      }

      function legacyCopyText(text){
        return new Promise(function(resolve, reject){
          var field = document.createElement('textarea');
          field.value = text;
          field.setAttribute('readonly','');
          field.style.position = 'fixed';
          field.style.left = '-9999px';
          field.style.opacity = '0';
          document.body.appendChild(field);
          field.select();
          field.setSelectionRange(0, field.value.length);

          try {
            if(document.execCommand('copy')) resolve();
            else reject(new Error('Copy command failed'));
          } catch(error) {
            reject(error);
          } finally {
            document.body.removeChild(field);
          }
        });
      }

      function copyText(text){
        if(navigator.clipboard && window.isSecureContext){
          return navigator.clipboard.writeText(text).catch(function(){
            return legacyCopyText(text);
          });
        }
        return legacyCopyText(text);
      }

      function closeActionMenus(exceptCell){
        document.querySelectorAll('#main_table td.actions.goobd-actions-open').forEach(function(cell){
          if (cell === exceptCell) return;
          cell.classList.remove('goobd-actions-open');
          var button = cell.querySelector('.goobd-actions-toggle');
          if (button) button.setAttribute('aria-expanded','false');
        });
      }

      function closePluginMenu(){
        var item = document.getElementById('admin_menu_plugins_link');
        if(!item) return;
        item.classList.remove('goobd-plugin-menu-open');
        var trigger = item.querySelector(':scope > a');
        if(trigger) trigger.setAttribute('aria-expanded','false');
      }

      function closeAdminMenu(){
        var nav = document.querySelector('#wrap > nav[role="navigation"]');
        if(!nav) return;
        nav.classList.remove('goobd-admin-menu-open');
        var toggle = nav.querySelector('.goobd-menu-toggle');
        if(toggle){
          toggle.setAttribute('aria-expanded','false');
          toggle.setAttribute('aria-label','Open admin menu');
        }
      }

      function enhanceAdminMenu(){
        var nav = document.querySelector('#wrap > nav[role="navigation"]');
        var menu = nav ? nav.querySelector('#admin_menu') : null;
        if(!nav || !menu || nav.dataset.goobdMobileMenuReady === '1') return;

        nav.dataset.goobdMobileMenuReady = '1';
        nav.classList.add('goobd-mobile-menu-ready');
        menu.id = 'admin_menu';

        var toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'goobd-menu-toggle';
        toggle.setAttribute('aria-controls','admin_menu');
        toggle.setAttribute('aria-expanded','false');
        toggle.setAttribute('aria-label','Open admin menu');
        toggle.innerHTML = '<span class="goobd-menu-toggle-label">Menu</span>'
          + '<span class="goobd-menu-toggle-icon" aria-hidden="true">'
          + '<span></span><span></span><span></span></span>';

        nav.insertBefore(toggle, menu);

        toggle.addEventListener('click', function(e){
          e.preventDefault();
          e.stopPropagation();
          var open = nav.classList.toggle('goobd-admin-menu-open');
          toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
          toggle.setAttribute('aria-label', open ? 'Close admin menu' : 'Open admin menu');
          if(!open) closePluginMenu();
        });

        menu.addEventListener('click', function(e){
          var link = e.target.closest('a');
          if(!link || window.innerWidth > 700) return;
          if(link.parentElement && link.parentElement.id === 'admin_menu_plugins_link') return;
          closeAdminMenu();
        });

        nav.addEventListener('keydown', function(e){
          if(e.key === 'Escape' && nav.classList.contains('goobd-admin-menu-open')){
            e.preventDefault();
            closeAdminMenu();
            closePluginMenu();
            toggle.focus();
          }
        });

        window.addEventListener('resize', function(){
          if(window.innerWidth > 700) closeAdminMenu();
        });
      }

      function enhanceDashboardSummary(){
        if(!document.body.classList.contains('index')) return;
        var overall = document.getElementById('overall_tracking');
        var range = overall ? overall.previousElementSibling : null;
        if(!overall || !range || range.tagName !== 'P') return;
        if(overall.dataset.goobdSummaryReady === '1') return;

        overall.dataset.goobdSummaryReady = '1';
        range.classList.add('goobd-summary-range');
        overall.classList.add('goobd-summary-overall');
        range.title = range.textContent.trim();
        overall.title = overall.textContent.trim();

        var rangeValues = Array.prototype.slice.call(range.querySelectorAll('strong'));
        if(rangeValues.length >= 3){
          range.textContent = '';
          range.appendChild(document.createTextNode('Showing '));
          range.appendChild(rangeValues[0]);
          range.appendChild(document.createTextNode('\u2013'));
          range.appendChild(rangeValues[1]);
          range.appendChild(document.createTextNode(' of '));
          range.appendChild(rangeValues[2]);
          range.appendChild(document.createTextNode(' URLs'));
          if(rangeValues[3]){
            range.classList.add('goobd-summary-filtered');
            range.appendChild(document.createTextNode(' \u00b7 '));
            range.appendChild(rangeValues[3]);
            range.appendChild(document.createTextNode(' clicks'));
          }
        }

        var overallValues = Array.prototype.slice.call(overall.querySelectorAll('strong'));
        if(overallValues.length >= 2){
          overall.textContent = '';
          overall.appendChild(document.createTextNode('Total '));
          overall.appendChild(overallValues[0]);
          overall.appendChild(document.createTextNode(' links \u00b7 '));
          overall.appendChild(overallValues[1]);
          overall.appendChild(document.createTextNode(' clicks'));
        }

        var summary = document.createElement('div');
        summary.className = 'goobd-dashboard-summary';
        range.parentNode.insertBefore(summary, range);
        summary.appendChild(range);
        summary.appendChild(overall);

        var previous = summary.previousElementSibling;
        if(previous && previous.tagName === 'P' && !previous.textContent.trim()){
          previous.classList.add('goobd-empty-summary-line');
        }
      }

      function rememberDashboardLocation(){
        if(!document.body.classList.contains('index') || document.body.classList.contains('infos')) return;
        try {
          window.sessionStorage.setItem('goobdLastDashboardUrl', window.location.href);
        } catch(e) {}
      }

      function getStatsBackUrl(){
        var fallback = new URL(<?php echo json_encode( yourls_admin_url( 'index.php' ) ); ?>, window.location.href);
        fallback.search = '';
        fallback.hash = '';
        var candidates = [];

        try {
          candidates.push(window.sessionStorage.getItem('goobdLastDashboardUrl'));
        } catch(e) {}
        candidates.push(document.referrer);

        for(var i = 0; i < candidates.length; i++){
          if(!candidates[i]) continue;
          try {
            var candidate = new URL(candidates[i], window.location.href);
            var isDashboard = candidate.origin === window.location.origin
              && candidate.pathname === fallback.pathname
              && !candidate.searchParams.has('id');
            if(isDashboard) return candidate.href;
          } catch(e) {}
        }

        return fallback.href;
      }

      function enhanceStatsPage(){
        if(!document.body.classList.contains('infos')) return;
        var title = document.getElementById('informations');
        var tabs = document.getElementById('tabs');
        if(!title || !tabs || tabs.dataset.goobdStatsReady === '1') return;

        tabs.dataset.goobdStatsReady = '1';
        var shortRow = title.nextElementSibling;
        var longRow = shortRow ? shortRow.nextElementSibling : null;
        var hero = document.createElement('section');
        hero.className = 'goobd-stats-hero';
        hero.setAttribute('aria-labelledby','informations');
        title.parentNode.insertBefore(hero, title);

        var backLink = document.createElement('a');
        backLink.className = 'goobd-stats-back';
        backLink.href = getStatsBackUrl();
        backLink.setAttribute('aria-label','Back to URL list');
        var backIcon = document.createElement('span');
        backIcon.className = 'goobd-stats-back-icon';
        backIcon.setAttribute('aria-hidden','true');
        backIcon.textContent = '\u2190';
        var backText = document.createElement('span');
        backText.textContent = 'Back to links';
        backLink.appendChild(backIcon);
        backLink.appendChild(backText);
        hero.appendChild(backLink);
        hero.appendChild(title);

        if(shortRow && shortRow.tagName === 'H3'){
          var links = document.createElement('div');
          links.className = 'goobd-stats-links';
          shortRow.classList.add('goobd-stats-link-row','goobd-stats-short-row');
          links.appendChild(shortRow);
          if(longRow && longRow.tagName === 'H3'){
            longRow.classList.add('goobd-stats-link-row','goobd-stats-long-row');
            links.appendChild(longRow);
          }
          hero.appendChild(links);
        }

        var headers = document.getElementById('headers');
        if(headers){
          headers.classList.add('goobd-stats-tablist');
          headers.setAttribute('aria-label','Statistics sections');
          if(headers.parentElement) headers.parentElement.classList.add('goobd-stats-tabs-wrap');
          var headerLinks = Array.prototype.slice.call(headers.querySelectorAll('a[href^="#stat_tab_"]'));
          var mobileNav = document.createElement('div');
          mobileNav.className = 'goobd-stats-mobile-nav';
          var mobileLabel = document.createElement('label');
          mobileLabel.setAttribute('for','goobd-stats-section');
          mobileLabel.textContent = 'Statistics section';
          var mobileSelect = document.createElement('select');
          mobileSelect.id = 'goobd-stats-section';
          mobileSelect.setAttribute('aria-label','Choose statistics section');
          headerLinks.forEach(function(link){
            var option = document.createElement('option');
            option.value = link.getAttribute('href');
            option.textContent = link.textContent.trim();
            mobileSelect.appendChild(option);
          });
          mobileNav.appendChild(mobileLabel);
          mobileNav.appendChild(mobileSelect);
          tabs.insertBefore(mobileNav, headers.parentElement || tabs.firstChild);

          var syncActiveTab = function(activeLink){
            headerLinks.forEach(function(link){
              if(link === activeLink) link.setAttribute('aria-current','page');
              else link.removeAttribute('aria-current');
            });
            if(activeLink) mobileSelect.value = activeLink.getAttribute('href');
          };
          headerLinks.forEach(function(link){
            link.addEventListener('click', function(){ syncActiveTab(link); });
          });
          mobileSelect.addEventListener('change', function(){
            var link = headers.querySelector('a[href="' + mobileSelect.value + '"]');
            if(link) link.click();
          });
          syncActiveTab(headers.querySelector('a.selected') || headerLinks[0]);
        }

        tabs.querySelectorAll('.tab > table').forEach(function(table){
          table.classList.add('goobd-stats-layout');
          var tab = table.closest('.tab');
          if(tab && tab.id) table.classList.add('goobd-stats-layout-' + tab.id.replace('stat_tab_',''));
          if(table.rows.length){
            Array.prototype.slice.call(table.rows[0].cells).forEach(function(cell, index){
              cell.classList.add('goobd-stats-panel');
              cell.classList.add(index === 0 ? 'goobd-stats-panel-primary' : 'goobd-stats-panel-secondary');
            });
          }
        });

        tabs.querySelectorAll('.stats_line').forEach(function(line){
          line.classList.add('goobd-stats-chart-section');
          line.querySelectorAll('div[id]').forEach(function(chart){
            chart.classList.add('goobd-stats-chart-frame');
          });
        });
        [
          'stat_tab_location_pie',
          'stat_tab_location_map',
          'stat_tab_source_ref',
          'stat_tab_source_direct'
        ].forEach(function(id){
          var chart = document.getElementById(id);
          if(chart) chart.classList.add('goobd-stats-chart-frame');
        });
      }

      function enhanceToolsPage(){
        if(!document.body.classList.contains('tools')) return;
        var main = document.querySelector('main.sub_wrap');
        if(!main || main.dataset.goobdToolsReady === '1') return;

        var headings = Array.prototype.slice.call(main.children).filter(function(node){
          return node.tagName === 'H2';
        });
        if(!headings.length) return;
        main.dataset.goobdToolsReady = '1';
        main.classList.add('goobd-tools-page');

        headings.forEach(function(heading, index){
          var nextHeading = headings[index + 1] || null;
          var section = document.createElement('section');
          var sectionNames = ['bookmarklets','prefix','api'];
          section.className = 'goobd-tools-section goobd-tools-' + (sectionNames[index] || 'extra');
          if(!heading.id) heading.id = 'goobd-tools-heading-' + (index + 1);
          section.setAttribute('aria-labelledby', heading.id);
          heading.parentNode.insertBefore(section, heading);

          var node = heading;
          while(node && node !== nextHeading){
            var next = node.nextSibling;
            section.appendChild(node);
            node = next;
          }
        });

        var bookmarklets = main.querySelector('.goobd-tools-bookmarklets');
        if(bookmarklets){
          var table = bookmarklets.querySelector('table.tblSorter');
          if(table) table.classList.add('goobd-bookmarklet-table');
          bookmarklets.querySelectorAll(':scope > p').forEach(function(paragraph){
            if(paragraph.querySelector(':scope > a.bookmarklet')){
              paragraph.classList.add('goobd-tools-bookmarklet-actions');
            }
            if(paragraph.querySelector(':scope > strong') && /important note/i.test(paragraph.textContent)){
              paragraph.classList.add('goobd-tools-note');
            }
          });
        }

        var api = main.querySelector('.goobd-tools-api');
        if(api){
          var apiList = api.querySelector(':scope > ul');
          if(apiList) apiList.classList.add('goobd-tools-api-list');
          var token = api.querySelector(':scope > p strong > code');
          var tokenRow = token ? token.closest('p') : null;
          if(tokenRow) tokenRow.classList.add('goobd-tools-token');
          var resetPanel = document.getElementById('goobd-signature-reset');
          if(resetPanel){
            var apiHeading = api.querySelector(':scope > h2');
            var introRow = apiHeading ? apiHeading.nextElementSibling : null;
            var infoRow = tokenRow ? tokenRow.nextElementSibling : null;

            if(
              apiHeading
              && introRow
              && introRow.tagName === 'P'
              && tokenRow
              && infoRow
              && infoRow.tagName === 'P'
            ){
              var overview = document.createElement('div');
              overview.className = 'goobd-tools-api-overview';
              overview.setAttribute('aria-label','API signature overview');
              var summary = document.createElement('div');
              summary.className = 'goobd-tools-api-summary';
              apiHeading.insertAdjacentElement('afterend', overview);
              summary.appendChild(introRow);
              summary.appendChild(tokenRow);
              summary.appendChild(infoRow);
              overview.appendChild(summary);
              overview.appendChild(resetPanel);
            } else if(tokenRow) {
              tokenRow.insertAdjacentElement('afterend', resetPanel);
            } else {
              api.appendChild(resetPanel);
            }
          }
        }
      }

      function enhancePluginMenu(){
        var item = document.getElementById('admin_menu_plugins_link');
        if(!item || item.dataset.goobdDropdownReady === '1') return;
        var trigger = item.querySelector(':scope > a');
        var menu = item.querySelector(':scope > ul');
        if(!trigger || !menu) return;

        item.dataset.goobdDropdownReady = '1';
        item.classList.add('goobd-plugin-menu-ready');
        var pluginsUrl = trigger.href;

        trigger.setAttribute('aria-haspopup','menu');
        trigger.setAttribute('aria-expanded','false');
        trigger.setAttribute('aria-controls','goobd-plugin-submenu');
        menu.id = 'goobd-plugin-submenu';
        menu.setAttribute('role','menu');

        var allPluginsItem = document.createElement('li');
        allPluginsItem.className = 'admin_menu_sublevel goobd-all-plugins';
        allPluginsItem.setAttribute('role','none');
        var allPluginsLink = document.createElement('a');
        allPluginsLink.href = pluginsUrl;
        allPluginsLink.textContent = 'All Plugins';
        allPluginsLink.setAttribute('role','menuitem');
        allPluginsItem.appendChild(allPluginsLink);
        menu.insertBefore(allPluginsItem, menu.firstChild);

        menu.querySelectorAll('a').forEach(function(link){
          link.setAttribute('role','menuitem');
        });

        trigger.addEventListener('click', function(e){
          e.preventDefault();
          e.stopPropagation();
          var open = !item.classList.contains('goobd-plugin-menu-open');
          closePluginMenu();
          if(open){
            item.classList.add('goobd-plugin-menu-open');
            trigger.setAttribute('aria-expanded','true');
            window.setTimeout(function(){ allPluginsLink.focus(); }, 0);
          }
        });

        trigger.addEventListener('keydown', function(e){
          if(e.key === ' '){
            e.preventDefault();
            trigger.click();
          }
        });

        item.addEventListener('keydown', function(e){
          if(e.key === 'Escape' && item.classList.contains('goobd-plugin-menu-open')){
            e.preventDefault();
            e.stopPropagation();
            closePluginMenu();
            trigger.focus();
          }
        });
      }

      function enhancePluginsPage(){
        if(!document.body.classList.contains('plugins')) return;
        var summary = document.getElementById('plugin_summary');
        var table = document.getElementById('main_table');
        if(!summary || !table || table.dataset.goobdPluginsReady === '1') return;

        table.dataset.goobdPluginsReady = '1';
        var rows = Array.prototype.slice.call(table.querySelectorAll('tbody tr.plugin'));
        var originalSummary = summary.textContent.trim();
        summary.title = originalSummary;

        var toolbar = document.createElement('div');
        toolbar.className = 'goobd-plugin-toolbar';
        summary.parentNode.insertBefore(toolbar, summary);
        toolbar.appendChild(summary);

        var filterWrap = document.createElement('div');
        filterWrap.className = 'goobd-plugin-filter';
        var filterLabel = document.createElement('label');
        filterLabel.className = 'goobd-plugin-filter-label';
        filterLabel.setAttribute('for','goobd-plugin-status');
        filterLabel.textContent = 'Filter';
        var filterSelect = document.createElement('select');
        filterSelect.id = 'goobd-plugin-status';
        filterSelect.setAttribute('aria-label','Filter plugins by activation status');
        [
          ['all','All'],
          ['active','Activated'],
          ['inactive','Deactivated']
        ].forEach(function(optionData){
          var option = document.createElement('option');
          option.value = optionData[0];
          option.textContent = optionData[1];
          filterSelect.appendChild(option);
        });
        filterWrap.appendChild(filterLabel);
        filterWrap.appendChild(filterSelect);
        toolbar.appendChild(filterWrap);

        var nativeToggle = document.getElementById('toggle_plugins');
        if(nativeToggle){
          nativeToggle.hidden = true;
          nativeToggle.setAttribute('aria-hidden','true');
        }

        filterSelect.addEventListener('change', function(){
          var status = filterSelect.value;
          rows.forEach(function(row){
            var visible = status === 'all' || row.classList.contains(status);
            row.hidden = !visible;
          });
        });

        rows.forEach(function(row){
          var name = row.querySelector('.plugin_name');
          if(name) row.setAttribute('aria-label', name.textContent.trim());
        });

        var node = table.nextElementSibling;
        while(node && node.tagName === 'SCRIPT') node = node.nextElementSibling;
        var warning = node && node.tagName === 'P' ? node : null;
        var moreHeading = warning ? warning.nextElementSibling : null;
        var moreCopy = moreHeading && moreHeading.tagName === 'H3'
          ? moreHeading.nextElementSibling
          : null;
        if(warning){
          var footer = document.createElement('div');
          footer.className = 'goobd-plugin-footer';
          warning.parentNode.insertBefore(footer, warning);

          var recovery = document.createElement('section');
          recovery.className = 'goobd-plugin-footer-card goobd-plugin-recovery';
          var recoveryHeading = document.createElement('h3');
          recoveryHeading.textContent = 'Plugin recovery';
          recovery.appendChild(recoveryHeading);
          recovery.appendChild(warning);
          footer.appendChild(recovery);

          if(moreHeading && moreHeading.tagName === 'H3'){
            var discover = document.createElement('section');
            discover.className = 'goobd-plugin-footer-card goobd-plugin-discover';
            discover.appendChild(moreHeading);
            if(moreCopy && moreCopy.tagName === 'P') discover.appendChild(moreCopy);
            footer.appendChild(discover);
          }
        }
      }

      function enhanceActionMenus(){
        document.querySelectorAll('#main_table td.actions').forEach(function(cell){
          if(cell.dataset.goobdMenuReady === '1') return;
          var links = Array.prototype.slice.call(cell.querySelectorAll(':scope > a.button'));
          if(!links.length) return;
          cell.dataset.goobdMenuReady = '1';

          var toggle = document.createElement('button');
          toggle.type = 'button';
          toggle.className = 'goobd-actions-toggle';
          toggle.setAttribute('aria-label','Open actions');
          toggle.setAttribute('aria-haspopup','menu');
          toggle.setAttribute('aria-expanded','false');
          toggle.innerHTML = '&#8942;';

          var menu = document.createElement('div');
          menu.className = 'goobd-actions-menu';
          menu.setAttribute('role','menu');
          var copyButton = document.createElement('button');
          copyButton.type = 'button';
          copyButton.className = 'button goobd-copy-button';
          copyButton.textContent = 'Copy';
          copyButton.setAttribute('role','menuitem');
          copyButton.setAttribute('title','Copy short URL');
          copyButton.setAttribute('aria-label','Copy short URL');
          copyButton.setAttribute('aria-live','polite');

          var copyInserted = false;
          links.forEach(function(link){
            link.setAttribute('role','menuitem');
            if(link.classList.contains('button_share') || link.id.indexOf('share-button-') === 0){
              link.addEventListener('click', scrollToSharePanel);
              menu.appendChild(link);
              menu.appendChild(copyButton);
              copyInserted = true;
              return;
            }
            menu.appendChild(link);
          });
          if(!copyInserted) menu.insertBefore(copyButton, menu.firstChild);

          copyButton.addEventListener('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            if(copyButton.classList.contains('disabled')) return;

            var row = cell.closest('tr');
            var shortLink = row ? row.querySelector('td.keyword > a') : null;
            if(!shortLink || !shortLink.href) return;

            copyText(shortLink.href).then(function(){
              copyButton.textContent = 'Copied';
              copyButton.setAttribute('aria-label','Short URL copied');
              copyButton.classList.add('goobd-copy-success');
              window.setTimeout(function(){
                cell.classList.remove('goobd-actions-open');
                toggle.setAttribute('aria-expanded','false');
              }, 700);
              window.setTimeout(function(){
                copyButton.textContent = 'Copy';
                copyButton.setAttribute('aria-label','Copy short URL');
                copyButton.classList.remove('goobd-copy-success');
              }, 1600);
            }).catch(function(){
              copyButton.textContent = 'Try again';
              copyButton.setAttribute('aria-label','Could not copy short URL');
              window.setTimeout(function(){
                copyButton.textContent = 'Copy';
                copyButton.setAttribute('aria-label','Copy short URL');
              }, 1600);
            });
          });
          cell.appendChild(toggle);
          cell.appendChild(menu);

          toggle.addEventListener('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            closeActionMenus(cell);
            var open = cell.classList.toggle('goobd-actions-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if(open){
              var first = menu.querySelector('.button');
              if(first) setTimeout(function(){ first.focus(); }, 0);
            }
          });

          menu.addEventListener('click', function(e){
            e.stopPropagation();
            var action = e.target.closest('a.button');
            if(action){
              setTimeout(function(){
                cell.classList.remove('goobd-actions-open');
                toggle.setAttribute('aria-expanded','false');
              }, 0);
            }
          });

          cell.addEventListener('keydown', function(e){
            if(e.key === 'Escape' && cell.classList.contains('goobd-actions-open')){
              e.preventDefault();
              cell.classList.remove('goobd-actions-open');
              toggle.setAttribute('aria-expanded','false');
              toggle.focus();
            }
          });
        });
      }

      function makeFilterField(grid, labelText, controls, className){
        controls = controls.filter(Boolean);
        if(!controls.length) return;
        var field = document.createElement('div');
        field.className = 'goobd-filter-field ' + (className || '');
        var label = document.createElement('div');
        label.className = 'goobd-filter-label';
        label.textContent = labelText;
        var controlsWrap = document.createElement('div');
        controlsWrap.className = 'goobd-filter-controls';
        controls.forEach(function(control){ controlsWrap.appendChild(control); });
        field.appendChild(label);
        field.appendChild(controlsWrap);
        grid.appendChild(field);
      }

      function enhanceFilterForm(){
        var filter = document.getElementById('filter_form');
        if(!filter || filter.dataset.goobdFilterReady === '1') return;
        var options = filter.querySelector('#filter_options');
        if(!options) return;
        filter.dataset.goobdFilterReady = '1';
        var buttons = options.querySelector('#filter_buttons');

        var grid = document.createElement('div');
        grid.className = 'goobd-filter-grid';

        makeFilterField(grid, 'Search', [
          options.querySelector('[name="search"]'),
          options.querySelector('[name="search_in"]')
        ], 'goobd-filter-search');
        makeFilterField(grid, 'Sort', [
          options.querySelector('[name="sort_by"]'),
          options.querySelector('[name="sort_order"]')
        ], 'goobd-filter-sort');
        makeFilterField(grid, 'Rows', [
          options.querySelector('[name="perpage"]')
        ], 'goobd-filter-rows');
        makeFilterField(grid, 'Clicks', [
          options.querySelector('[name="click_filter"]'),
          options.querySelector('[name="click_limit"]')
        ], 'goobd-filter-clicks');
        makeFilterField(grid, 'Created', [
          options.querySelector('[name="date_filter"]'),
          options.querySelector('[name="date_first"]'),
          options.querySelector('[name="date_second"]')
        ], 'goobd-filter-created');

        options.innerHTML = '';
        options.appendChild(grid);
        if(buttons) options.appendChild(buttons);
      }

      function movePaginationBelowTable(){
        var table = document.getElementById('main_table');
        var pagination = document.getElementById('pagination');
        if(!table || !pagination || pagination.dataset.goobdPositionReady === '1') return;

        pagination.dataset.goobdPositionReady = '1';
        pagination.classList.add('goobd-pagination-bottom');
        table.insertAdjacentElement('afterend', pagination);
        document.body.classList.add('goobd-pagination-relocated');
      }

      function moveFilterAboveTable(){
        var table = document.getElementById('main_table');
        var filter = document.getElementById('filter_form');
        if(!table || !filter || filter.dataset.goobdPositionReady === '1') return;

        filter.dataset.goobdPositionReady = '1';
        filter.classList.add('goobd-filter-above-table');
        table.insertAdjacentElement('beforebegin', filter);
        document.body.classList.add('goobd-filter-relocated');
      }


      function enhanceUrlForm(){
        var form = document.getElementById('new_url_form');
        if(!form || form.dataset.goobdUrlReady === '1') return;
        var host = form.querySelector(':scope > div');
        var url = form.querySelector('#add-url');
        var keyword = form.querySelector('#add-keyword');
        var button = form.querySelector('#add-button');
        var urlLabel = form.querySelector('label[for="add-url"]');
        var keywordLabel = form.querySelector('label[for="add-keyword"]');
        if(!host || !url || !keyword || !button || !urlLabel || !keywordLabel) return;
        form.dataset.goobdUrlReady = '1';

        var grid = document.createElement('div');
        grid.className = 'goobd-url-grid';

        var urlField = document.createElement('div');
        urlField.className = 'goobd-url-field goobd-url-main';
        urlField.appendChild(urlLabel);
        urlField.appendChild(url);

        var keywordField = document.createElement('div');
        keywordField.className = 'goobd-url-field goobd-url-keyword';
        keywordField.appendChild(keywordLabel);
        keywordField.appendChild(keyword);

        var action = document.createElement('div');
        action.className = 'goobd-url-action';
        action.appendChild(button);

        Array.prototype.slice.call(form.querySelectorAll('input[type="hidden"]')).forEach(function(hidden){
          grid.appendChild(hidden);
        });
        grid.appendChild(urlField);
        grid.appendChild(keywordField);
        grid.appendChild(action);
        host.innerHTML = '';
        host.appendChild(grid);
      }

      document.addEventListener('DOMContentLoaded', function(){
        enhanceAdminMenu();
        rememberDashboardLocation();
        enhanceDashboardSummary();
        enhanceStatsPage();
        enhanceToolsPage();
        enhancePluginMenu();
        enhancePluginsPage();
        enhanceActionMenus();
        enhanceDeleteFeedback();
        enhanceFilterForm();
        enhanceUrlForm();
        moveFilterAboveTable();
        movePaginationBelowTable();
        var table=document.getElementById('main_table');
        if(table){
          new MutationObserver(function(){
            enhanceActionMenus();
            announceCompletedDelete();
          }).observe(table,{childList:true,subtree:true});
        }
      });

      document.addEventListener('click', function(){
        closeActionMenus(null);
        closePluginMenu();
        closeAdminMenu();
      });
    })();
    </script>
    <?php
}
