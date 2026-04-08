<?php
require_once '../includes/functions.php';
Auth::logout();
header('Location: login.php');
exit;