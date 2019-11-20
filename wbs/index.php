<?php
  date_default_timezone_set("America/Sao_Paulo");
  header('Access-Control-Allow-Origin: *');

  ini_set('display_errors',1);
  ini_set('display_startup_erros',1);
  error_reporting(E_ALL);

  require_once __DIR__ . '/bin/functions/mutations.functions.php';
  require_once __DIR__ . '/bin/class/bootstrap.php';
  require_once __DIR__ . '/bin/init/conn.init.php';
  require_once __DIR__ . '/bin/init/permission.init.php';
  require_once __DIR__ . '/bin/functions/init.functions.php';

  // print_r($_POST);
  // print_r($_FILES);

  // $q = new query($mysql, 'budget');

  // $resp = $q->read("cod,cache,event,status,tags,uid.cod,uid.avatar,uid.user_information.name,uid.user_information.cpf,uid.evaluation,uid.type,fid,gid,sgid")
  //   ->toArray();

  //   print_r($resp);

  // exit();

  if( !isset( $_POST['token'] ) || empty($_POST['token'])){
    response::error("Acesso inválido!");
  }else{

    //Declaração de variaveis
    $pToken = '';
    $pSession = '';

    //Catalogando e limpando o token
    $token = $_POST['token'];
    unset($_POST['token']);

    if( strlen($token) == 32 ){
      $pToken = $token;
    } else if( strlen($token) == 65 && strpos($token, '.') !== false ){
      $request = explode('.', $token);
      $pToken = $request[0];
      $pSession = $request[1];
    }else {
      response::error("Token inválido!");
    }

    $pedido = get_data_permission($pToken);

    if($pedido == null){
      response::error("Token não cadastrado!");
    } else {
      $info = explode('-', $pedido['tag']);
      if( $info[0] == "public" ){
        require_once __DIR__ . "/bin/index.php";
      }else if($info[0] == "protected"){
        if($pSession == ''){
          response::error("Sessão indefinida!");
        }else{
          $uid = flash_query(
            $mysql,
            "SELECT user FROM `sessions` WHERE cod = '$pSession' limit 1"
          );
          if( isset($uid['user']) ){
            require_once __DIR__ . "/bin/index.php";
          }else{
            response::error("Sessão inexistente!");
          }
        }
        // echo $pSession;
      }else if($info[0] == "private"){
        if($pSession == ''){
          response::error("Sessão indefinida!");
        }else{
          $uid = flash_query(
            $mysql,
            "SELECT user FROM `sessions` WHERE cod = '$pSession' limit 1"
          );

          if( isset($uid['user']) ){
            $profile = flash_query(
              $mysql,
              "SELECT `perfil` FROM `login` WHERE cod = '{$uid['user']}' limit 1"
            );
            if( !empty($profile) ){
              $uPerm = flash_query(
                $mysql,
                "SELECT `licenses` FROM `profiles` WHERE cod = '{$profile['perfil']}' limit 1"
              );

              if( !empty($profile) ){
                $uPerm = json_decode($uPerm['licenses']);
                if(permission_exists($uPerm, $pedido['hash'][0])) {

                  require_once __DIR__ . "/bin/index.php";
                }else{
                  response::error("Acesso negado!");
                }
              }else {
                response::error("Não existe permissão para esse usuário!");
              }

            }else{
              response::error("Usuário informado inexistente!");
            }

          }else{
            response::error("Sessão inexistente!");
          }
        }

      }else{
        response::error("Ação do tipo {$info[0]} precisam informar a hash de sessão!");
      }
    }

  }

  exit( response::out() );

?>
