<?php // Dashboard Widgets Suite - User Notes Widget

if (!defined('ABSPATH')) exit;

function dashboard_widgets_suite_notes_user() {
	
	echo dashboard_widgets_suite_notes_user_content();
	
}

function dashboard_widgets_suite_notes_user_content() {
	
	$data = get_option('dws_notes_user_data') ? get_option('dws_notes_user_data') : array();
	
	$data = apply_filters('dashboard_widgets_suite_notes_user_data_form', array_reverse($data));
	
	do_action('dashboard_widgets_suite_notes_user', $data);
	
	return dashboard_widgets_suite_notes_user_form($data);
	
}

function dashboard_widgets_suite_notes_user_message() {
	
	global $dws_options_notes_user;
	
	$message = isset($dws_options_notes_user['widget_notes_message']) ? $dws_options_notes_user['widget_notes_message'] : '';
	
	$message = apply_filters('dashboard_widgets_suite_notes_user_message', $message);
	
	return $message;
}

function dashboard_widgets_suite_notes_user_example() {
	
	list($date, $time) = dashboard_widgets_suite_get_date();
	
	$example = array(
		array(
			'date'  => $date, 
			'id'    => 1,
			'name'  => esc_html__('Pat Smith', 'dashboard-widgets-suite'), 
			'note'  => esc_html__('Make sure you do something..', 'dashboard-widgets-suite'), 
			'role'  => 'all', 
			'time'  => $time, 
			'title' => esc_html__('Example Note', 'dashboard-widgets-suite'),
		)
	);
	
	$example = apply_filters('dashboard_widgets_suite_notes_user_example', $example);
	
	return $example;
	
}

function dashboard_widgets_suite_notes_user_style() {
	
	global $dws_options_notes_user;
	
	$height = isset($dws_options_notes_user['widget_notes_height']) ? intval($dws_options_notes_user['widget_notes_height']) : 0;
		
	if ($height > 0) $style = 'style="height:'. $height .'px;"';
	else             $style = 'style="min-height:77px;"';
	
	$style = apply_filters('dashboard_widgets_suite_notes_user_style', $style);
	
	return $style;
	
}

function dashboard_widgets_suite_notes_user_form($data) {
	
	global $dws_options_notes_user;
	
	$allowed_tags = dashboard_widgets_suite_allowed_tags();
	
	$count = isset($dws_options_notes_user['widget_notes_count']) ? $dws_options_notes_user['widget_notes_count'] : 0;
	$edit  = isset($dws_options_notes_user['widget_notes_edit'])  ? $dws_options_notes_user['widget_notes_edit']  : null;
	
	$notes = count($data);
	
	$i = 0;
	
	$return = '<div id="dws-notes-user">';
	
	foreach ($data as $key => $value) {
		
		if ($i === $count) break;
		
		$id    = isset($value['id'])    ? intval($value['id']) : '';
		$date  = isset($value['date'])  ? sanitize_text_field($value['date'])  : '';
		$name  = isset($value['name'])  ? sanitize_text_field($value['name'])  : '';
		$role  = isset($value['role'])  ? sanitize_text_field($value['role'])  : '';
		$time  = isset($value['time'])  ? sanitize_text_field($value['time'])  : '';
		$title = isset($value['title']) ? sanitize_text_field(stripslashes_deep($value['title'])) : '';
		$note  = isset($value['note'])  ? wp_kses(stripslashes_deep($value['note']), $allowed_tags) : '';
		
		if (dashboard_widgets_suite_check_role($edit)) {
			
			$return .= dashboard_widgets_suite_notes_user_form_edit($id, $date, $name, $role, $time, $title, $note, $key);
			
			$i++;
			
		} elseif (dashboard_widgets_suite_check_role($role)) {
			
			$return .= dashboard_widgets_suite_notes_user_form_view($id, $date, $name, $time, $title, $note);
			
			$i++;
			
		}
		
	}
	
	if ($i === 0) {
		
		$return .= '<div class="dws-notes-user-default">';
		
		if ($count === 0 && $notes > 0) {
			
			$return .= esc_html__('To view your notes, adjust the setting &ldquo;Number of Notes&rdquo;.', 'dashboard-widgets-suite');
			
		} else {
			
			$return .= dashboard_widgets_suite_notes_user_message();
			
		}
		
		$return .= '</div>';
		
	}
	
	if (dashboard_widgets_suite_check_role($edit)) {
		
		$return .= '<div class="dws-notes-user-button-add">';
		$return .= '<span class="fa fa-plus-circle"></span> <a href="#dws-notes-user-add">'. esc_html__('Add Note', 'dashboard-widgets-suite') .'</a>';
		$return .= '</div>';
		
		$return .= dashboard_widgets_suite_notes_user_form_add();
		
	}
	
	$return .= '</div>';
	
	return $return;
	
}

function dashboard_widgets_suite_notes_user_form_edit($id, $date, $name, $role, $time, $title, $note, $key) { 
	
	$user_role = ($role === 'all') ? esc_attr__('Any', 'dashboard-widgets-suite') : ucfirst($role);
	
	$form  = '<div class="dws-notes-user">';
	$form .= '<form method="post" action="">';
	
	$form .= '<div class="dws-notes-user-meta">';
	$form .= '<span class="fa fa-pad fa-file-text"></span> ';
	$form .= '<strong class="dws-info" title="'. esc_attr__('Note ID: ', 'dashboard-widgets-suite') . $id . esc_attr__(', User Role: ', 'dashboard-widgets-suite') . $user_role .'">'. $title .'</strong> ';
	$form .= '<em>'. esc_html__(' by ', 'dashboard-widgets-suite') . $name .', <span class="dws-info" title="'. $time .'">'. $date .'</span></em>';
	$form .= '</div>';
	
	$form .= '<label for="note">'. esc_html__('Note', 'dashboard-widgets-suite') .'</label>';
	$form .= '<textarea name="dws-notes-user[note]"'. dashboard_widgets_suite_notes_user_style() .' data-key="'. intval($key + 1) .'" data-rows="3" rows="3" cols="50" ';
	$form .= 'class="dws-hidden" placeholder="'. esc_attr__('Enter some notes..', 'dashboard-widgets-suite') .'">'. $note .'</textarea>';
	$form .= '<div '. dashboard_widgets_suite_notes_user_style() .' class="dws-notes-user-note" data-key="'. intval($key + 1) .'"></div>';
	
	$form .= '<div class="dws-notes-user-buttons dws-hidden">';
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[edit]" value="'. esc_attr__('Save Changes', 'dashboard-widgets-suite') .'">';
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[delete]" value="'. esc_attr__('Delete Note', 'dashboard-widgets-suite') .'">';
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[cancel]" value="'. esc_attr__('Cancel', 'dashboard-widgets-suite') .'" data-key="'. intval($key + 1) .'">';
	$form .= '</div>';
	
	$form .= '<input type="hidden" name="dws-notes-user[id]" value="'. $id .'">';
	$form .= '<input type="hidden" name="dws-notes-user[name]" value="'. $name .'">';
	$form .= '<input type="hidden" name="dws-notes-user[title]" value="'. $title .'">';
	
	$form .= wp_nonce_field('dws-notes-user-nonce', 'dws-notes-user[nonce]', false, false);
	
	$form .= '</form>';
	$form .= '</div>';
	
	return $form;
	
}

function dashboard_widgets_suite_notes_user_form_view($id, $date, $name, $time, $title, $note) { 
	
	$form  = '<div class="dws-notes-user">';
	
	$form .= '<div class="dws-notes-user-meta">';
	$form .= '<span class="fa fa-pad fa-file-text-o"></span> ';
	$form .= '<strong class="dws-info" title="'. esc_attr__('Note ID: ', 'dashboard-widgets-suite') . $id .'">'. $title .'</strong> ';
	$form .= '<em>'. esc_html__(' &ndash; ', 'dashboard-widgets-suite') . $name .', <span class="dws-info" title="'. $time .'">'. $date .'</span></em>';
	$form .= '</div>';
	
	$form .= '<div '. dashboard_widgets_suite_notes_user_style() .' class="dws-notes-user-note">'. $note .'</div>';
	
	$form .= '</div>';
	
	return $form;
	
}

function dashboard_widgets_suite_notes_user_form_add() {
	
	$form  = '<div id="dws-notes-user-add" class="dws-notes-user dws-hidden">';
	$form .= '<form method="post" action="">';
	
	$form .= '<div class="dws-notes-user-meta">';
	$form .= '<label for="dws-notes-user[title]">'. esc_html__('Title', 'dashboard-widgets-suite') .'</label>';
	$form .= '<input name="dws-notes-user[title]" type="text" size="40" value="" placeholder="'. esc_attr__('Title', 'dashboard-widgets-suite') .'" autofocus="autofocus">';
	$form .= '<label for="dws-notes-user[name]">'. esc_html__('Name', 'dashboard-widgets-suite') .'</label>';
	$form .= '<input name="dws-notes-user[name]" type="text" size="40" value="" placeholder="'. esc_attr__('Name', 'dashboard-widgets-suite') .'">';
	$form .= '</div>';
	
	$form .= '<label for="dws-notes-user[note]">'. esc_html__('Note', 'dashboard-widgets-suite') .'</label>';
	$form .= '<textarea name="dws-notes-user[note]" data-key="0" data-rows="3" rows="3" cols="50" placeholder="'. esc_attr__('Enter some notes..', 'dashboard-widgets-suite') .'"></textarea>';
	
	$form .= '<div class="dws-notes-user-buttons">';
	$form .= dashboard_widgets_suite_notes_user_roles();
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[add]" value="'. esc_attr__('Add Note', 'dashboard-widgets-suite') .'">';
	$form .= '</div>';
	
	$form .= wp_nonce_field('dws-notes-user-nonce', 'dws-notes-user[nonce]', false, false);
	
	$form .= '</form>';
	$form .= '</div>';
	
	return $form;
	
}

function dashboard_widgets_suite_notes_user_roles() {
	
	global $dws_options_notes_user;
	
	$default_role = isset($dws_options_notes_user['widget_notes_view']) ? $dws_options_notes_user['widget_notes_view'] : 'all';
	
	$roles = dashboard_widgets_suite_user_roles();
	
	$field  = '<select name="dws-notes-user[role]">';
	$field .= '<option value="'. $default_role .'">'. esc_html__('Role required to view this note..', 'dashboard-widgets-suite') .'</option>';
	
	foreach ($roles as $key => $value) {
		
		$text = ucfirst($key);
		
		if ($key === 'all') $text = esc_html__('Any Role', 'dashboard-widgets-suite');
		
		$field .= '<option value="'. $key .'">'. $text .'</option>';
		
	}
	
	$field .= '</select> <label for="dws-notes-user[role]">'. esc_html__('Role', 'dashboard-widgets-suite') .'</label>';
	
	return $field;
	
}
