<?php

$db = 'balances.json';

if (file_exists($db)) {
	$balances = json_decode(file_get_contents($db), true);
} else {
	$balances = ['thigato' => 1000000];
	file_put_contents($db, json_encode($balances));
}


if ($_SERVER['PATH_INFO'] == '/balance') {
	if(!isset($_GET['user'])) {
		http_response_code(404);
		print 'Digitar usuario';
		return;
	}

	$user = strtolower($_GET['user']);

	if (!isset($balances[$user])) {
		http_response_code(404);
		print 'c nao existe zé';
		return;
	}

	
	printf("usuario %s tem %d gaiacoins", $user, $balances[$user] ?? 0);
	return;
}


if ($_SERVER['PATH_INFO'] == '/user' && $_SERVER['REQUEST_METHOD'] == 'POST') {
	$user = strtolower($_POST['user']);
	if (isset($balances[$user])) {
		http_response_code(404);
		print 'nao pode inserrir um usuario já cadastrado';
		return;
	}

	$balances[$user] = 0;
	file_put_contents($db, json_encode($balances));
	print 'conseguiu meu consagrado';
	return;
}

if ($_SERVER['PATH_INFO'] == '/transfer' && $_SERVER['REQUEST_METHOD'] == 'POST') {
	$from = strtolower($_POST['from']); //DE
	if (!isset($balances[$from])) {
		http_response_code(404);
		print 'from nao encontrado';
		return;
	}

	$to = strtolower($_POST['to']); //PARA
	if (!isset($balances[$to])) {
		http_response_code(404);
		print 'to nao encontrado';
		return;
	}

	$amount = (int) $_POST['amount'];
	if ($balances[$from] < $amount) {
		http_response_code(404);
		print 'sem saldo';
		return;
	}

	$balances[$from] -= $amount;
	$balances[$to] += $amount;
	file_put_contents($db, json_encode($balances));
	print 'transferido com sucesso!';
	return;
}