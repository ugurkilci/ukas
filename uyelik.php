<?php
          session_start();
          include 'ayar.php';
          include 'ukas.php';

          $p = @$_GET["p"];

          $sorgu = $db->prepare("SELECT COUNT(*) FROM uyeler");
          $sorgu->execute();
          $say = $sorgu->fetchColumn();

        switch ($p) {
            case 'cikis':
                if ($_SESSION["uye_id"]) {
                    ukas_cikis("index.php");
                }else{
                    header("LOCATION:index.php");
                }
                break;

            case 'kayit':
                if ($_SESSION["uye_id"]) {
                    header("LOCATION:index.php");
                }else{
                    ukas_kayit("<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Böyle bir eposta mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-warning'>Böyle bir kullanıcı adı mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-success'>Başarıyla kaydoldun! :)</p>", $devam, "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>", "<p class='text-danger'>Kayıt başarısız!</p>", "<p>Şifreniz bir birine eşleşmiyor!</p>", "<p>Lütfen gerçek bir eposta giriniz!</p>");
                    echo '<h1 class="text-center"><strong>Şimdi Kayıt Ol!</strong></h1>
                    <form action="" method="POST">
                        <strong>Ad Soyad:</strong>
                        <input type="text" class="form-control" name="adsoyad">
                        <strong>Kullanıcı adı:</strong>
                        <input type="text" class="form-control" name="kadi">
                        <strong>Şifre:</strong>
                        <input type="password" class="form-control" name="sifre">
                        <strong>Şifre (Tekrar):</strong>
                        <input type="password" class="form-control" name="sifret">
                        <strong>E-Posta:</strong>
                        <input type="text" class="form-control" name="eposta"><br />
                        <input type="hidden" name="_token" value="'.$_SESSION["_token"].'">
                        <input type="submit" class="btn btn-block btn-dark" name="kayit" value="Kayıt Ol">
                    </form>
                    <hr>
                    <a href="uyelik.php?p=giris" class="btn btn-block btn-secondary">Şimdi giriş yap!</a><br />
                    <a href="index.php" class="text-dark"><small>&larr; Anasayfaya dön</small></a>';
                }
                break;

            default:
                if ($_SESSION["uye_id"]) {
                    header("LOCATION:index.php");
                }else{
                    ukas_giris("index.php", "<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>");

                    echo '<h1 class="text-center"><strong>Giriş Yap</strong></h1>
                    <br /><form action="" method="POST">
                        <strong>Kullanıcı Adı:</strong>
                        <input type="text" class="form-control" name="kadi">
                        <strong>Şifre:</strong>
                        <input type="password" class="form-control" name="sifre"><br />
                        <input type="hidden" name="_token" value="'.$_SESSION["_token"].'">
                        <input type="submit" class="btn btn-block btn-dark" name="giris" value="Giriş Yap">
                    </form>
                    <a href="uyelik.php?p=kayit" class="btn btn-block btn-secondary mt-3">Şimdi kayıt ol!</a>
                    <hr>
                    <a href="index.php" class="text-dark"><small>&larr; Anasayfaya dön</small></a>';
                    }
                break;
        }

      ?>
