<? 

global $bp;

do_action( 'bp_before_profile_edit_content' );

$profile_group = intval(bp_get_current_profile_group_id());
if ($profile_group == 0)
  $profile_group = 1;

 ?>

<? if ( bp_has_profile( "profile_group_id=$profile_group" )) : 
  while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

<form action="<? bp_the_profile_group_edit_form_action() ?>" method="post" id="profile-edit-form" class="standard-form <? bp_the_profile_group_slug() ?>">

	<? do_action( 'bp_before_profile_field_content' ) ?>

		<div class="clear"></div>

		<? while ( bp_profile_fields() ) : bp_the_profile_field(); 

// Don't edit NAME here.
if (bp_get_the_profile_field_name() == 'Name') continue;

 ?>

			<div<? bp_field_css_class( 'editfield' ) ?>>

				<? if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

					<label for="<? bp_the_profile_field_input_name() ?>"><? bp_the_profile_field_name() ?> <? if ( bp_get_the_profile_field_is_required() ) : ?><? _e( '(required)', 'buddypress' ) ?><? endif; ?></label>
					<input type="text" name="<? bp_the_profile_field_input_name() ?>" id="<? bp_the_profile_field_input_name() ?>" value="<? bp_the_profile_field_edit_value() ?>" />

				<? endif; ?>

				<? if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

					<label for="<? bp_the_profile_field_input_name() ?>"><? bp_the_profile_field_name() ?> <? if ( bp_get_the_profile_field_is_required() ) : ?><? _e( '(required)', 'buddypress' ) ?><? endif; ?></label>
					<textarea rows="5" cols="40" name="<? bp_the_profile_field_input_name() ?>" id="<? bp_the_profile_field_input_name() ?>"><? bp_the_profile_field_edit_value() ?></textarea>

				<? endif; ?>

				<? if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

					<label for="<? bp_the_profile_field_input_name() ?>"><? bp_the_profile_field_name() ?> <? if ( bp_get_the_profile_field_is_required() ) : ?><? _e( '(required)', 'buddypress' ) ?><? endif; ?></label>
					<select name="<? bp_the_profile_field_input_name() ?>" id="<? bp_the_profile_field_input_name() ?>">
						<? bp_the_profile_field_options() ?>
					</select>

				<? endif; ?>

				<? if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

					<label for="<? bp_the_profile_field_input_name() ?>"><? bp_the_profile_field_name() ?> <? if ( bp_get_the_profile_field_is_required() ) : ?><? _e( '(required)', 'buddypress' ) ?><? endif; ?></label>
					<select name="<? bp_the_profile_field_input_name() ?>" id="<? bp_the_profile_field_input_name() ?>" multiple="multiple">
						<? bp_the_profile_field_options() ?>
					</select>

					<? if ( !bp_get_the_profile_field_is_required() ) : ?>
						<a class="clear-value" href="javascript:clear( '<? bp_the_profile_field_input_name() ?>' );"><? _e( 'Clear', 'buddypress' ) ?></a>
					<? endif; ?>

				<? endif; ?>

				<? if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

					<div class="radio">
						<span class="label"><? bp_the_profile_field_name() ?> <? if ( bp_get_the_profile_field_is_required() ) : ?><? _e( '(required)', 'buddypress' ) ?><? endif; ?></span>

						<? bp_the_profile_field_options() ?>

						<? if ( !bp_get_the_profile_field_is_required() ) : ?>
							<a class="clear-value" href="javascript:clear( '<? bp_the_profile_field_input_name() ?>' );"><? _e( 'Clear', 'buddypress' ) ?></a>
						<? endif; ?>
					</div>

				<? endif; ?>

				<? if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>

					<div class="checkbox">
						<span class="label"><? bp_the_profile_field_name() ?> <? if ( bp_get_the_profile_field_is_required() ) : ?><? _e( '(required)', 'buddypress' ) ?><? endif; ?></span>

						<? bp_the_profile_field_options() ?>
					</div>

				<? endif; ?>

				<? if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

					<div class="datebox">
						<label for="<? bp_the_profile_field_input_name() ?>_day"><? bp_the_profile_field_name() ?> <? if ( bp_get_the_profile_field_is_required() ) : ?><? _e( '(required)', 'buddypress' ) ?><? endif; ?></label>

						<select name="<? bp_the_profile_field_input_name() ?>_day" id="<? bp_the_profile_field_input_name() ?>_day">
							<? bp_the_profile_field_options( 'type=day' ) ?>
						</select>

						<select name="<? bp_the_profile_field_input_name() ?>_month" id="<? bp_the_profile_field_input_name() ?>_month">
							<? bp_the_profile_field_options( 'type=month' ) ?>
						</select>

						<select name="<? bp_the_profile_field_input_name() ?>_year" id="<? bp_the_profile_field_input_name() ?>_year">
							<? bp_the_profile_field_options( 'type=year' ) ?>
						</select>
					</div>

				<? endif; ?>

				<? do_action( 'bp_custom_profile_edit_fields' ) ?>

        <?
          $desc = bp_get_the_profile_field_description();
          if (!empty($desc)) {
            ?><p class="description"><?= $desc ?></p><?
          }
        ?>
			</div>

		<? endwhile; ?>

	<? do_action( 'bp_after_profile_field_content' ) ?>

</form>

<? endwhile; endif; ?>

<? do_action( 'bp_after_profile_edit_content' ) ?>
