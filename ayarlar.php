<?php
    ob_start();

    $data = $db -> prepare("SELECT * FROM uyeler WHERE
        uye_id=?
      ");
      $data -> execute([
        $_SESSION["uye_id"]
      ]);
      $_data = $data -> fetch(PDO::FETCH_ASSOC);

  ?>
  <center><h1 class="girisimzel">Epostamı Değiştir</h1></center>
  <?php

    if(isset($_POST["epostaguncelle"])){
        $eposta = htmlspecialchars( $_POST["eposta"] );

        if(
            empty( $eposta )
        ){
            echo '<p class="alert alert-warning">Lütfen epostanızı boş bırakmayınız!</p>';
        }else{
            $veriguncelle = $db->prepare("UPDATE uyeler SET uye_eposta=? WHERE uye_id=?");
            $veriguncelle ->execute([
                $eposta, $_SESSION["uye_id"]
            ]);

            if($veriguncelle){
              echo '<p class="alert alert-success">Epostanız başarıyla güncellendi! :)</p>
<meta http-equiv="refresh" content="1; url=ayarlar.php">';
              exit;
            }else{
              echo '<p class="alert alert-danger">Epostanız başarıyla güncellenemedi! :(</p>
<meta http-equiv="refresh" content="1; url=ayarlar.php">';
              exit;
            }
        }
    }

  ?>
  <form action="" method="post">
    <b>Eposta:</b>
    <input type="email" name="eposta" value="<?=$_data["uye_eposta"]?>" class="form-control">
    <input type="hidden" name="_token" value="<?=@$_SESSION["_token"]?>">
    <button type="submit" name="epostaguncelle" class="black btn-dark text-white mt-3">Epostamı Güncelle</button> 
  </form>

  <br><hr><br>

  <center><h1 class="girisimzel">Şifremi Değiştir</h1></center>
  <?php

      if(isset($_POST["sifreguncelle"])){
          $msifre = htmlspecialchars( $_POST["msifre"] );
          $ysifre = htmlspecialchars( $_POST["ysifre"] );
          $tsifre = htmlspecialchars( $_POST["tsifre"] );

          $sifrele = md5(sha1( $tsifre ));
          $msifre_sifrele = md5(sha1( htmlspecialchars( $_POST["msifre"] ) ));

          if(
              empty( $msifre ) ||
              empty( $ysifre ) ||
              empty( $tsifre )
          ){
              echo '<p class="alert alert-warning">Lütfen şifreleri boş bırakmayınız!</p>';
          }else{
              if(
                $msifre_sifrele == $_data["uye_sifre"]
              ){
                  if(
                    $ysifre == $tsifre
                  ){
                      $veriguncelle = $db->prepare("UPDATE uyeler SET uye_sifre=? WHERE uye_id=?");
                      $veriguncelle ->execute([
                          $sifrele, $_SESSION["uye_id"]
                      ]);

                      if($veriguncelle){
                        echo '<p class="alert alert-success">Şifreniz başarıyla güncellendi! :)</p>
      <meta http-equiv="refresh" content="1; url=ayarlar.php">';
                        exit;
                      }else{
                        echo '<p class="alert alert-danger">Şifreniz başarıyla güncellenemedi! :(</p>
      <meta http-equiv="refresh" content="1; url=ayarlar.php">';
                        exit;
                      }
                  }else{
                    echo '<p class="alert alert-danger">Yeni şifre ile şifre tekrarı birbirine uyuşmuyor!</p>';
                  }
              }else{
                echo '<p class="alert alert-danger">Mevcut şifreniz yanlış!</p>';
              }
          }
      }

    ?>
    <form action="" method="post">
      <b>Mevcut Şifrem:</b>
      <input type="password" name="msifre" class="form-control">
      <b>Yeni Şifrem:</b>
      <input type="password" name="ysifre" class="form-control">
      <b>Yeni Şifremin Tekrarı:</b>
      <input type="password" name="tsifre" class="form-control">
      <input type="hidden" name="_token" value="<?=@$_SESSION["_token"]?>">
      <button type="submit" name="sifreguncelle" class="black btn-dark text-white mt-3">Şifremi Güncelle</button> 
    </form>

    <br><hr><br>

    <a href="uyelik.php?p=cikis" class="black btn-dark text-white mt-3">Çıkış Yap</a>
