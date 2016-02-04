
<?php

function renommer($file_to_rename, $new_file_name)
{
  $new_file_already_exists = file_exists ($new_file_name); // boolean

  if ($new_file_already_exists) {
    $str_temp =  substr($new_file_name, 0, -4).'_temp.jpg';
    // renomme le fichier en supprimant l'extension, puis rajoutant '_temp' et ajoutant l'extension
    rename ($new_file_name , $str_temp);
    // le fichier $new_file_name (qu'on s'apprète à écraser) est renommé avant
  }

  rename ($file_to_rename , $new_file_name);
  // renomme le fichier $file_to_rename en  $new_file_name : id sera écrasé s'il existait

  if ($new_file_already_exists) {
    rename ($str_temp, ($file_to_rename));
    // on renomme le fichier temporaire avec le nom de l'image initaliement à renommer
    // -> l'opération est finalement une simple permutation entre les deux noms
  }
}

$str = $_POST['src_image'];
$id_right_exo = $_POST['id_right_exo'];
//$n = strlen(strval($id_right_exo)); // $n = nombre de crarctère dans l'identifiant de l'exercice
if ($id_right_exo <= 0) {
  $str_right = substr($str, 0, -6).'00'.$id_right_exo.".jpg"; // on renomme $str_light à partie de $str
}
elseif ($id_right_exo <= 9)
{
  $str_right = substr($str, 0, -6).'0'.$id_right_exo.".jpg"; // on renomme $str_light à partie de $str
} else {
  $str_right = substr($str, 0, -6).$id_right_exo.".jpg"; // on renomme $str_light à partie de $str
}

unset($_POST["id_right_exo"]);
unset($_POST["src_image"]);

renommer($str, $str_right);
?>
