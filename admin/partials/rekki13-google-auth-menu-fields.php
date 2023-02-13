<?php
if ( isset( $args ) ) {
	$item_id            = $args['item_id'];
	$menu_item_selected = get_post_meta( $item_id, '_rekki13_nav_item_options',
		true );
}
?>
<p>
    <label for="menu-item-select-rekki13_google_auth"><?= __( 'Display for users:', 'rekki13-google-auth' ) ?></label>
</p>
<p>
    <select name="menu-item[<?php echo $item_id; ?>][rekki13_google_auth_id]" id="menu-item-select-rekki13_google_auth" class="menu-item-select-rekki13_google_auth">
        <option value="All"><?= __( 'All', 'rekki13-google-auth' ) ?></option>
        <option value="Logged" <?php selected( $menu_item_selected, 'Logged' ); ?>><?= __( 'Logged', 'rekki13-google-auth' ) ?></option>
        <option value="Non Logged" <?php selected( $menu_item_selected, 'Non Logged' ); ?>><?= __( 'Non Logged', 'rekki13-google-auth' ) ?></option>
    </select>
</p>
