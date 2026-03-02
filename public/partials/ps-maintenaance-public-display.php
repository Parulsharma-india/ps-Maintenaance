<?php
/**
 * The template for displaying maintenance mode
 *
 * This template can be overridden by copying it to yourtheme/ps-maintenaance/ps-maintenaance-public-display.php.
 *
 * @package PS_Maintenaance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<?php
/**
 * The template for displaying maintenance mode
 *
 * This template can be overridden by copying it to yourtheme/ps-maintenaance/ps-maintenaance-public-display.php.
 *
 * @package PS_Maintenaance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="ps-maintenaance">

    <?php if ( ! empty( $context['logo'] ) ) : ?>
        <div class="ps-maintenaance-logo">
            <img 
                src="<?php echo esc_url( $context['logo'] ); ?>" 
                alt="<?php echo esc_attr( $context['title'] ?? 'Logo' ); ?>"
                <?php if ( ! empty( $context['logo_width'] ) ) : ?>
                    width="<?php echo esc_attr( $context['logo_width'] ); ?>"
                <?php endif; ?>
                <?php if ( ! empty( $context['logo_height'] ) ) : ?>
                    height="<?php echo esc_attr( $context['logo_height'] ); ?>"
                <?php endif; ?>
                style="<?php echo ( ( $context['logo_shape'] ?? 'circle' ) === 'circle' ) ? 'border-radius:50%;' : 'border-radius:0;'; ?>"
            >
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $context['title'] ) ) : ?>
        <h1 class="ps-maintenaance-title">
            <?php echo esc_html( $context['title'] ); ?>
        </h1>
    <?php endif; ?>

    <?php if ( ! empty( $context['description'] ) ) : ?>
        <div class="ps-maintenaance-description">
            <?php echo wp_kses_post( wpautop( $context['description'] ) ); ?>
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $context['is_preview'] ) ) : ?>
        <p class="ps-preview-note">Preview mode</p>
    <?php endif; ?>

</div>

<?php if ( ! empty( $context['background'] ) || ! empty( $context['background_color'] ) ) : ?>
    <div class="ps-maintenaance-background"
        style="
            <?php if ( ! empty( $context['background'] ) ) : ?>
                background-image: url('<?php echo esc_url( $context['background'] ); ?>');
            <?php endif; ?>
            <?php if ( ! empty( $context['background_color'] ) ) : ?>
                background-color: <?php echo esc_attr( $context['background_color'] ); ?>;
            <?php endif; ?>
        ">
    </div>
<?php endif; ?>
