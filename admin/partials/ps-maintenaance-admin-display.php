<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Admin Settings View
 * @package Ace_Maintenance_Page
 */

$ps_maintenance_opts        = isset( $context['opts'] ) ? $context['opts'] : [];
$ps_maintenance_preview_url = add_query_arg(
    [
        'ps_preview'       => 1,
        'ps_preview_nonce' => wp_create_nonce( 'ps_preview' ),
    ],
    home_url()
);
?>

<div class="wrap">
    <h1>PS Maintenance Settings</h1>

    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">

        <?php wp_nonce_field( 'ps_maintenaance_save_action', 'ps_maintenaance_nonce' ); ?>
        <input type="hidden" name="action" value="ps_maintenaance_save">
        <input type="hidden" name="logo_old" value="<?php echo esc_attr( $ps_maintenance_opts['logo'] ?? '' ); ?>">
        <input type="hidden" name="background_old" value="<?php echo esc_attr( $ps_maintenance_opts['background'] ?? '' ); ?>">

        <table class="form-table">

            <!-- Enable -->
            <tr>
                <th scope="row">Enable Maintenance Mode</th>
                <td>
                    <label>
                        <input type="checkbox" name="enabled"
                            <?php checked( 1, $ps_maintenance_opts['enabled'] ?? 0 ); ?>>
                        Enable
                    </label>

                    <p style="<?php echo ! empty( $ps_maintenance_opts['enabled'] ) ? '' : 'display:none;'; ?>">
                        <a href="<?php echo esc_url( $ps_maintenance_preview_url ); ?>" target="_blank">
                            Preview Maintenance
                        </a>
                    </p>
                </td>
            </tr>

            <!-- Heading -->
            <tr>
                <th scope="row">Heading</th>
                <td>
                    <input type="text" name="title" class="regular-text"
                        value="<?php echo esc_attr( $ps_maintenance_opts['title'] ?? '' ); ?>">
                </td>
            </tr>

            <!-- Description -->
            <tr>
                <th scope="row">Description</th>
                <td>
                    <?php
                    $ps_maintenance_content = $ps_maintenance_opts['description'] ?? '';

                    wp_editor(
                        $ps_maintenance_content,
                        'ps_description',
                        [
                            'textarea_name' => 'description',
                            'media_buttons' => true,
                            'tinymce'       => [
                                'toolbar1' => 'bold italic underline | bullist numlist | link unlink | undo redo',
                                'toolbar2' => '',
                            ],
                            'quicktags'     => true,
                            'editor_height' => 200,
                        ]
                    );
                    ?>
                </td>
            </tr>

            <!-- Logo Upload -->
            <tr>
                <th scope="row">Logo</th>
                <td>
                    <input type="file" name="logo_file" accept="image/*">

                    <?php if ( ! empty( $ps_maintenance_opts['logo'] ) ) : ?>
                        <div style="margin-top:10px;">
                            <img src="<?php echo esc_url( $ps_maintenance_opts['logo'] ); ?>"
                                 style="max-width:120px;">
                        </div>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- Background -->
            <tr>
                <th scope="row">Background</th>
                <td>
                    <input type="file" name="background_file" accept="image/*">

                    <?php if ( ! empty( $ps_maintenance_opts['background'] ) ) : ?>
                        <div style="margin-top:10px;">
                            <img src="<?php echo esc_url( $ps_maintenance_opts['background'] ); ?>"
                                 style="max-width:160px;">
                            <br>
                            <label>
                                <input type="checkbox" name="remove_background" value="1">
                                Remove current background
                            </label>
                        </div>
                    <?php endif; ?>

                    <br><br>

                    <input type="text" name="background_color"
                        value="<?php echo esc_attr( $ps_maintenance_opts['background_color'] ?? '' ); ?>"
                        class="regular-text">

                </td>
            </tr>

            <!-- Exclude Pages -->
            <tr>
                <th scope="row">Exclude Pages</th>
                <td>
                    <textarea name="exclude_pages" rows="3" class="large-text"><?php
                        echo esc_textarea( $ps_maintenance_opts['exclude_pages'] ?? '' );
                    ?></textarea>
                </td>
            </tr>

            <!-- Logo Width -->
            <tr>
                <th scope="row">Logo Width (px)</th>
                <td>
                    <input type="number" name="logo_width" class="small-text"
                        value="<?php echo esc_attr( $ps_maintenance_opts['logo_width'] ?? '' ); ?>">
                </td>
            </tr>

            <!-- Logo Height -->
            <tr>
                <th scope="row">Logo Height (px)</th>
                <td>
                    <input type="number" name="logo_height" class="small-text"
                        value="<?php echo esc_attr( $ps_maintenance_opts['logo_height'] ?? '' ); ?>">
                </td>
            </tr>

            <!-- Logo Shape -->
            <tr>
                <th scope="row">Logo Shape</th>
                <td>
                    <label>
                        <input type="radio" name="logo_shape" value="circle"
                            <?php checked( $ps_maintenance_opts['logo_shape'] ?? 'circle', 'circle' ); ?>>
                        Circle
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="logo_shape" value="box"
                            <?php checked( $ps_maintenance_opts['logo_shape'] ?? 'circle', 'box' ); ?>>
                        Box
                    </label>
                </td>
            </tr>

        </table>

        <?php submit_button( 'Save Changes' ); ?>

    </form>
</div>