<?php
	session_start();
	include 'ayar.php';
	include 'ukas.php';

	ukas_cikis("ayar.php", "test.php");
	// Ayar dosyası, Çıkış yaptıktan sonra yönlendirilecek adres
?>