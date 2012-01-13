<?php

$config = array(
	'login' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required|valid_email'
		),
		array(
			'field' => 'password',
			'name' => 'Password',
			'rules' => 'required'
		)
	),
	'register' => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'required'
		)
	)
);
