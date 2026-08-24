<?php
/**
 * Lightweight sanitizer regression test.
 * Run from the plugin directory with: php tests/branding-sanitizer-test.php
 */

if ( PHP_SAPI !== 'cli' ) {
    exit( 1 );
}

define( 'YOURLS_ABSPATH', __DIR__ );

function yourls_add_action() {}
function yourls_add_filter() {}
function yourls_debug_log() {}

function yourls_esc_attr( $text ) {
    return htmlspecialchars( (string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false );
}

function yourls_esc_html( $text ) {
    return htmlspecialchars( (string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false );
}

require dirname( __DIR__ ) . '/plugin.php';

$cases = array(
    'balances an unclosed tag' => array(
        '<strong>Brand',
        'header',
        '<strong>Brand</strong>',
    ),
    'normalizes misnested tags' => array(
        '<strong>A<em>B</strong>C',
        'header',
        '<strong>A<em>BC</em></strong>',
    ),
    'removes links from header content' => array(
        '<a href="https://example.com/"><strong>Home</strong></a>',
        'header',
        '<strong>Home</strong>',
    ),
    'removes unsafe footer attributes and protocols' => array(
        '<a href="jav&#x61;script:alert(1)" onclick="alert(1)" target="_blank">Bad</a>',
        'footer',
        '<a target="_blank" rel="noopener noreferrer">Bad</a>',
    ),
    'protects safe links opened in a new tab' => array(
        '<a href="https://example.com/" target="_blank" rel="nofollow">Safe</a>',
        'footer',
        '<a href="https://example.com/" target="_blank" rel="nofollow noopener noreferrer">Safe</a>',
    ),
    'allows an intentionally empty optional field' => array(
        '',
        'footer',
        '',
    ),
);

$failures = 0;
foreach ( $cases as $name => $case ) {
    list( $input, $context, $expected ) = $case;
    $actual = goobd_ae_sanitize_brand_html( $input, $context );
    if ( $actual !== $expected ) {
        $failures++;
        fwrite( STDERR, "FAIL: {$name}\nExpected: {$expected}\nActual:   {$actual}\n" );
    }
}

if ( $failures > 0 ) {
    exit( 1 );
}

fwrite( STDOUT, 'Branding sanitizer tests passed: ' . count( $cases ) . "\n" );
