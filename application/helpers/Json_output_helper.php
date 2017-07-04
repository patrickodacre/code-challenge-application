<?php
	function json_output($response)
	{
		$ci =& get_instance();
		$ci->output->set_content_type('application/json');
		$ci->output->set_status_header($response['status']);
		$ci->output->set_output(json_encode($response));
	}