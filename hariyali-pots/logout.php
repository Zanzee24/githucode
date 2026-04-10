<?php
require_once __DIR__ . '/core/helpers.php';
session_destroy();
redirect('index.php');
